<?php

namespace SilverStripe\ApiDocs\Console;

use Gitonomy\Git\Admin;
use Gitonomy\Git\Exception\ProcessException;
use Psr\Log\NullLogger;
use RuntimeException;
use SilverStripe\ApiDocs\Data\Config;
use SilverStripe\ApiDocs\Data\RepoFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckoutCommand extends Command
{
    /**
     * Regex patterns for github repositories that should be ignored.
     * These are repositories that don't have relevant API.
     */
    private array $ignoreModulePatterns = [
        '/^silverstripe\/developer-docs$/',
        '/^silverstripe\/installer$/',
        '/^silverstripe-themes\/simple$/',
        '/^silverstripe\/vendor-plugin$/',
        '/^silverstripe\/recipe-.*/',
        '/-theme$/',
    ];

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $config = Config::getConfig();

        // Ensure all dirs exist
        foreach ($config['paths'] as $type => $path) {
            if ($type === 'versionmap') {
                $path = dirname($path);
            }
            $fullPath = Config::configPath($path);
            if (!file_exists($fullPath)) {
                $output->writeln("Creating path <info>$fullPath</info>");
                mkdir($fullPath, 0755, true);
            }
        }

        $versionMap = $this->buildVersionMap($config, $output);

        // Ensure all repos are checked out
        foreach ($versionMap as $name => &$data) {
            $root = Config::configPath($config['paths']['packages'] . '/' . $name);

            // Either update or checkout
            if (file_exists($root . '/.git')) {
                $this->updateVersionMapWithRealBranches($name, $data, $config, $root, $output, true);
            } else {
                $output->writeln("Cloning <info>$name</info>");
                if (!file_exists($root)) {
                    mkdir($root, 0755, true);
                }
                Admin::cloneTo($root, $data['repository'], false, RepoFactory::options());
                $this->updateVersionMapWithRealBranches($name, $data, $config, $root, $output);
            }
        }

        $this->writeMapToDisk($versionMap, $config, $output);

        return self::SUCCESS;
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
        OutputInterface $output,
        bool $updateRepoData = false
    ) {
        $repo = RepoFactory::repoFor($root, $output);
        if ($updateRepoData) {
            $output->writeln("Updating <info>$name</info>");
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
    private function writeMapToDisk(array $map, array $config, OutputInterface $output)
    {
        $output->writeln('Writing version map to disk');
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
    private function buildVersionMap(array $config, OutputInterface $output)
    {
        $output->writeln('Making new version map');
        $map = [];
        $versions = array_keys($config['versions']);

        foreach ($versions as $version) {
            $modulesUrl = "https://raw.githubusercontent.com/silverstripe/supported-modules/$version/modules.json";
            $modulesJson = json_decode(file_get_contents($modulesUrl) ?: 'null', true);

            if ($modulesJson === null) {
                throw new RuntimeException('Modules data could not be retrieved for version ' . $version);
            }

            foreach ($modulesJson as $module) {
                $moduleName = $module['composer'];

                if ($this->shouldIgnoreModule($module)) {
                    continue;
                }

                if (!array_key_exists($moduleName, $map)) {
                    $githubName = $module['github'];
                    $map[$moduleName] = [
                        'repository' => "https://github.com/$githubName.git",
                        'versionmap' => [],
                    ];
                }

                $branches = $module['branches'];
                sort($branches);
                $map[$moduleName]['versionmap'][$version] = end($branches);
            }
        }

        foreach ($map as &$data) {
            foreach ($versions as $version) {
                if (!isset($data['versionmap'][$version])) {
                    $data['versionmap'][$version] = null;
                }
            }
        }

        return $map;
    }

    private function shouldIgnoreModule(array $module)
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
}
