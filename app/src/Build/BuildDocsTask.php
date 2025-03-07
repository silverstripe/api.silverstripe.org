<?php

namespace SilverStripe\ApiDocs\Build;

use RuntimeException;
use Doctum\Doctum;
use Doctum\Project;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ApiDocs\Data\ApiJsonStore;
use SilverStripe\ApiDocs\Data\Config;
use SilverStripe\ApiDocs\Inspections\RecipeFinder;
use SilverStripe\ApiDocs\Inspections\RecipeVersionCollection;
use SilverStripe\ApiDocs\RemoteRepository\SilverStripeRemoteRepository;
use SilverStripe\ApiDocs\Renderer\SilverStripeRenderer;
use SilverStripe\ApiDocs\Parser\SilverStripeNodeVisitor;
use SilverStripe\ApiDocs\Parser\Filter\SilverStripeFilter;
use Symfony\Component\Console\Output\OutputInterface;
use SilverStripe\ApiDocs\Data\RepoFactory;
use Psr\Log\NullLogger;
use Gitonomy\Git\Exception\ProcessException;
use SilverStripe\SupportedModules\MetaData;
use Symfony\Component\Console\Output\ConsoleOutput;
use Gitonomy\Git\Admin;
use SilverStripe\Core\Path;
use Symbiote\QueuedJobs\Services\AbstractQueuedJob;

class BuildDocsTask extends BuildTask
{
    private static $segment = 'BuildDocsTask';

    /**
     * Regex patterns for github repositories that should be ignored.
     * These are repositories that don't have relevant API.
     * The patterns are matched against the packagist name for the module.
     */
    private array $ignoreModulePatterns = [
        '/^silverstripe\/developer-docs$/',
        '/^silverstripe\/installer$/',
        '/^silverstripe-themes\/simple$/',
        '/^silverstripe\/startup-theme$/',
        '/^silverstripe\/vendor-plugin$/',
        '/^silverstripe\/recipe-.*/',
        '/-theme$/',
    ];

    private ?BuildDocsQueuedJob $job;

    private OutputInterface $output;

    public function __construct(?BuildDocsQueuedJob $job = null)
    {
        parent::__construct();
        $this->job = $job;
        $this->output = new ConsoleOutput(ConsoleOutput::VERBOSITY_VERBOSE);
    }

    public function run($request)
    {
        // Remove time limit to ensure the task will run to completion
        set_time_limit(0);

        // Git clone supported repositories
        $this->cloneRepositories();

        // Create static documentation with Doctum
        $doctum = $this->getDoctum();
        $doctum->getProject()->update(null, true);
    }

    private function cloneRepositories(): void
    {
        $config = Config::getConfig();

        // Ensure all dirs exist
        foreach ($config['paths'] as $type => $path) {
            if ($type === 'versionmap') {
                $path = dirname($path);
            }
            $fullPath = Config::configPath($path);
            if (!file_exists($fullPath)) {
                $this->log("Creating path <info>$fullPath</info>");
                mkdir($fullPath, 0755, true);
            }
        }
        $versionMap = $this->buildVersionMap($config);
        // Ensure all repos are checked out
        foreach ($versionMap as $name => &$data) {
            $root = Config::configPath($config['paths']['packages'] . '/' . $name);

            // Either update or checkout
            if (file_exists($root . '/.git')) {
                $this->updateVersionMapWithRealBranches($name, $data, $config, $root, true);
            } else {
                $this->log("Cloning <info>$name</info>");
                if (!file_exists($root)) {
                    mkdir($root, 0755, true);
                }
                Admin::cloneTo($root, $data['repository'], false, RepoFactory::options());
                $this->updateVersionMapWithRealBranches($name, $data, $config, $root);
            }
        }
        $this->writeMapToDisk($versionMap, $config);
    }

    /**
     * Checks the repository for a branch name matching the major release for each version number based on mapping in $data.
     *
     * If the branch doesn't exist, updates the $data mapping with the correct branch name.
     *
     * If $updateRepoData is true, also updates local branches for each version.
     */
    private function updateVersionMapWithRealBranches(
        string $name,
        array &$data,
        array $config,
        string $root,
        bool $updateRepoData = false
    ) {
        $repo = RepoFactory::repoFor($root, $this->output);
        if ($updateRepoData) {
            $this->log("Updating <info>$name</info>");
            $repo->run('fetch', ['--all', '--prune']);
        }
        foreach (array_keys($config['versions']) as $version) {
            $branch = null;
            $majorBranch = $data['versionmap'][$version];
            if ($majorBranch === null) {
                continue;
            }
            // Check if the major branch exists.
            // Make sure to set a null logger so if the branch is missing there's no error logged for it.
            $result = null;
            $oldLogger = $repo->getLogger();
            $repo->setLogger(new NullLogger());
            try {
                $result = $repo->run('rev-parse', ['--quiet', '--verify', 'origin/' . $majorBranch]);
            } catch (ProcessException $e) {
                // no-op, this happens if the result is null and the repo is in debug mode.
            } finally {
                $repo->setLogger($oldLogger);
            }
            // If major branch doesn't exist, get the last available minor branch
            if ($result === null) {
                // Get all branches and remove the remote name from the front
                $allBranches = array_map(
                    fn($branchName) => trim(str_replace('origin/', '', $branchName)),
                    explode(PHP_EOL, $repo->run('branch', ['-r']))
                );
                // Exclude branches not in the correct major release
                $allBranches = array_filter($allBranches, fn($branchName) => preg_match('/^' . preg_quote($majorBranch, '/') . '\.\d+$/', $branchName));
                // Use the numerically largest option
                sort($allBranches, SORT_NATURAL);
                $data['versionmap'][$version] = $branch = end($allBranches) ?: null;
            } else {
                // No change - but still note the branch so we can update the repo data
                $branch = $majorBranch;
            }
            if ($branch === null) {
                trigger_error("No valid branch available for $name in version $version", E_USER_WARNING);
            } elseif ($updateRepoData) {
                $repo->run('checkout', ['--force', $branch]);
                $repo->run('reset', ['--hard', 'origin/' . $branch]);
            }
        }
    }

    /**
     * Writes a version mapping array to disk as JSON, so other areas of the code can use it.
     */
    private function writeMapToDisk(array $map, array $config)
    {
        $this->log('Writing version map to disk');
        $filePath = Config::configPath($config['paths']['versionmap']);
        $success = file_put_contents(
            $filePath,
            json_encode($map, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
        if ($success === false) {
            throw new RuntimeException('Failed to write version map to disk. Attempted to write to ' . $filePath);
        }
    }

    /**
     * Builds a map of which Silverstripe CMS major release matches which major release of a module.
     */
    private function buildVersionMap(array $config)
    {
        $this->log('Making new version map');
        $map = [];
        $versions = array_keys($config['versions']);
        $repos = MetaData::getAllRepositoryMetaData()[MetaData::CATEGORY_SUPPORTED];
        foreach ($repos as $repo) {
            if ($this->shouldIgnoreModule($repo)) {
                continue;
            }
            $githubName = $repo['github'];
            $moduleName = $repo['packagist'];
            $versionMap = $repo['majorVersionMapping'];
            // Remove mapping from any versions we don't care about, and make sure we only have one branch for each cms major
            foreach ($versionMap as $cmsMajor => $branches) {
                if (!in_array($cmsMajor, $versions)) {
                    unset($versionMap[$cmsMajor]);
                    continue;
                }
                $versionMap[$cmsMajor] = end($branches);
            }
            $map[$moduleName] = [
                'repository' => "https://github.com/$githubName.git",
                'versionmap' => $versionMap,
            ];
        }

        // CMS 3 isn't in the main branch of silverstripe/supported-modules - we have to use a legacy branch for that data.
        if (in_array(3, $versions)) {
            $modulesUrl = "https://raw.githubusercontent.com/silverstripe/supported-modules/3/modules.json";
            $modulesJson = json_decode(file_get_contents($modulesUrl) ?: 'null', true);
            if ($modulesJson === null) {
                throw new RuntimeException('Modules data could not be retrieved for version 3');
            }
            foreach ($modulesJson as $module) {
                if ($this->shouldIgnoreModuleLegacy($module, true)) {
                    continue;
                }

                $moduleName = $module['composer'];
                if (!array_key_exists($moduleName, $map)) {
                    $githubName = $module['github'];
                    $map[$moduleName] = [
                        'repository' => "https://github.com/$githubName.git",
                        'versionmap' => [],
                    ];
                }

                $branches = $module['branches'];
                sort($branches);
                $map[$moduleName]['versionmap'][3] = end($branches);
            }
        }
        // Add a null entry for anything that's not supported for a given version
        foreach ($map as $index => $data) {
            foreach ($versions as $version) {
                if (!isset($data['versionmap'][$version])) {
                    $map[$index]['versionmap'][$version] = null;
                }
            }
        }
        return $map;
    }

    private function shouldIgnoreModuleLegacy(array $module)
    {
        if ($module['type'] !== 'supported-module' || empty($module['branches'])) {
            return true;
        }
        foreach ($this->ignoreModulePatterns as $regex) {
            if (preg_match($regex, $module['composer'])) {
                return true;
            }
        }
        return false;
    }

    private function shouldIgnoreModule(array $module)
    {
        if ($module['type'] === 'other' || empty($module['majorVersionMapping'])) {
            return true;
        }
        foreach ($this->ignoreModulePatterns as $regex) {
            if (preg_match($regex, $module['packagist'])) {
                return true;
            }
        }
        return false;
    }

    private function getDoctum(): Doctum
    {
        // Get config
        $config = Config::getConfig();
        // Build versions
        /** @var RecipeVersionCollection $versions */
        $versions = new RecipeVersionCollection();
        foreach ($config['versions'] as $version => $description) {
            $versions->add((string)$version, $description);
        }
        // Build finder which draws from the recipe collection
        $iterator = new RecipeFinder($versions);
        $iterator
            ->files()
            ->name('*.php')
            ->exclude('thirdparty')
            ->exclude('examples')
            ->exclude('tests');
        // Config
        $doctum = new Doctum($iterator, [
            'theme' => $config['theme'],
            'title' => $config['title'],
            'base_url' => $config['base_url'],
            'versions' => $versions,
            'build_dir' => Config::configPath($config['paths']['www']) . '/%version%',
            'cache_dir' => Config::configPath($config['paths']['cache']) . '/%version%',
            'source_dir' => $versions->getPackagePath(''),// Root of all the packages
            'remote_repository' => new SilverStripeRemoteRepository('', $versions->getPackagePath('') . '/'),
            'template_dirs' => [ dirname(__DIR__, 3) .'/doctum-conf/themes' ],
        ]);
        // Make sure we document `@config` options
        $doctum['filter'] = function() {
            return new SilverStripeFilter();
        };
        $doctum['php_traverser'] = function ($sc) {
            $traverser = new NodeTraverser();
            $traverser->addVisitor(new NameResolver());
            $traverser->addVisitor(new SilverStripeNodeVisitor($sc['parser_context']));
            return $traverser;
        };

        $doctum['renderer'] = function ($sc) {
            return new SilverStripeRenderer($sc['twig'], $sc['themes'], $sc['tree'], $sc['indexer']);
        };
        // Override json store
        $store = $doctum['store'];
        unset($doctum['store']);
        $doctum['store'] = function () {
            return new ApiJsonStore();
        };
        // Override project
        unset($doctum['project']);
        $doctum['project'] = function ($sc) {
            $project = new Project($sc['store'], $sc['_versions'], array(
                'build_dir' => $sc['build_dir'],
                'cache_dir' => $sc['cache_dir'],
                'remote_repository' => $sc['remote_repository'],
                'include_parent_data' => $sc['include_parent_data'],
                'default_opened_level' => $sc['default_opened_level'],
                'theme' => $sc['theme'],
                'title' => $sc['title'],
                'source_url' => $sc['source_url'],
                'source_dir' => $sc['source_dir'],
                'insert_todos' => $sc['insert_todos'],
                'base_url' => $sc['base_url'],
                'footer_link' => [
                    'href' => 'https://github.com/silverstripe/api.silverstripe.org',
                    'rel' => 'noreferrer noopener',
                    'target' => '_blank',
                    'before_text' => 'Contributions to this documentation repository are welcomed',
                    'link_text' => 'on Github!',
                ],
            ));
            $project->setRenderer($sc['renderer']);
            $project->setParser($sc['parser']);
            return $project;
        };
        return $doctum;
    }

    private function log(string $message)
    {
        $this->output->writeln($message);
        if ($this->job) {
            $this->job->addMessage(strip_tags($message));
        }
    }
}
