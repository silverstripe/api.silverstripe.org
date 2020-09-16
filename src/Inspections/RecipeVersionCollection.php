<?php

namespace SilverStripe\ApiDocs\Inspections;

use LogicException;
use Doctum\Version\Version;
use Doctum\Version\VersionCollection;
use SilverStripe\ApiDocs\Data\Config;
use SilverStripe\ApiDocs\Data\RepoFactory;

/**
 * Provides a per-recipe view of all versions in a multi-repo checkout
 */
class RecipeVersionCollection extends VersionCollection
{
    /**
     * Current version
     *
     * @var Version
     */
    protected $version = null;

    /**
     * @var array
     */
    protected $config;

    public function __construct($versions = [])
    {
        parent::__construct($versions);
        $this->config = Config::getConfig();
    }

    /**
     * Get all active packages for the current version
     *
     * @return array
     */
    public function getActivePackages()
    {
        $packages = $this->getPackages();
        $version = $this->getVersion();
        if (!$version) {
            return $packages;
        }

        // Filter out disabled packages
        return array_filter($packages, function ($package) {
            return $this->getPackageBranch($package) !== null;
        });
    }

    /**
     * List of all package paths
     *
     * @return array List of package names
     */
    public function getPackages()
    {
        return array_keys($this->config['packages']);
    }

    /**
     * Get branch for the given package
     *
     * @param string $package
     * @return string Package, or null if not enabled for this version
     */
    public function getPackageBranch($package)
    {
        // Get version
        $version = $this->getVersion();
        if (!$version) {
            return null;
        }

        // Get version map for this package
        $map = $this->config['packages'][$package]['versionmap'] ?? null;
        if ($map && !is_array($map)) {
            // Use named map instead of specific map
            $map = $this->config['versionmaps'][$map] ?? null;
            if (!is_array($map)) {
                throw new LogicException("Package $package uses non-existant versionmap");
            }
        }

        // Get name to map
        $name = $version->getName();

        // Map branch name
        if ($map && array_key_exists($name, $map)) {
            return $map[$name];
        }

        return $name;
    }

    /**
     * @return Version
     */
    public function getVersion(): Version
    {
        // Get current version if iterating
        if ($this->version) {
            return $this->version;
        }
        // Default to first version if not set
        if ($this->versions) {
            return $this->versions[0];
        }
        return null;
    }

    /**
     * @param Version $version
     * @return $this
     */
    public function setVersion(Version $version)
    {
        $this->version = $version;
        return $this;
    }

    protected function switchVersion(Version $version)
    {
        $this->version = $version;

        // Switch branches for all repos
        foreach ($this->getPackages() as $package) {
            $this->switchPackageVersion($package, $version);
        }
    }

    /**
     * Get path to the given package root
     *
     * @param string $package
     * @return string
     */
    public function getPackagePath($package)
    {
        return Config::configPath($this->config['paths']['packages'] . '/' . $package);
    }

    /**
     * Switch package to the given version
     *
     * @param string $package
     * @param Version $version
     */
    protected function switchPackageVersion($package, Version $version)
    {
        $this->setVersion($version);

        // Check branch alias for this package
        $target = $this->getPackageBranch($package);
        if (!isset($target)) {
            return;
        }

        // Switch branch to $target
        $repository = RepoFactory::repoFor($this->getPackagePath($package));
        $repository->run('checkout', ['-qf', $target]);
    }
}
