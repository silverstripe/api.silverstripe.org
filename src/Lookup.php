<?php

namespace SilverStripe\ApiDocs;

use Symfony\Component\Yaml\Yaml;

/**
 * Lookup script to convert symbol names and other parameters to their URL representation in the API docs.
 *
 * See README for more info.
 */
class Lookup
{
    /**
     * The default branch name for each core module
     *
     * @var string
     */
    const DEFAULT_BRANCH = 'master';

    /**
     * @var string[]
     */
    protected $args = array();

    /**
     * @var string[]
     */
    protected $versionMap = array();

    /**
     * @var string[]
     */
    protected $upgraderMap = array();

    /**
     * @var string
     */
    protected $serverName;

    /**
     * @param array $args E.g. $_GET vars
     */
    public function __construct($args = array())
    {
        $this->args = $args;
    }

    /**
     * @return string[]
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Return an argument from the array
     *
     * @param  string $key
     * @return string
     */
    public function getArg($key)
    {
        if (array_key_exists($key, $this->args)) {
            return $this->args[$key];
        }
    }

    /**
     * Redirect the user to where they need to go
     *
     * @param bool $return When true, the redirect URL will be returned instead of a header issued
     * @return string|null
     */
    public function handle($return = false)
    {
        $paths = array();

        // Only include modules path if we're not request core.
        if ($this->getArg('module') && !in_array($this->getArg('module'), array('cms', 'framework', 'sapphire'))) {
            $paths[] = 'modules/' . $this->getArg('module');
        }

        $paths[] = $this->getVersion();

        // Search
        if ($searchOrig = $this->getArg('q')) {
            $search = str_replace(array('()', '$'), '', $searchOrig);
            $searchParts = preg_split('/(::|\->)/', $search);
            $searchConfig = array();
            if (count($searchParts) == 2) {
                $searchConfig['class'] = $searchParts[0];
                $searchConfig['property'] = $searchParts[1];
                $searchConfig['type'] = (strpos($searchOrig, '()') !== false) ? 'method' : 'property';
            } else {
                $searchConfig['class'] = $search;
                $searchConfig['property'] = '';
                $searchConfig['type'] = 'class';
            }
            $searchConfig['class'] = $this->getWithNamespace($searchConfig['class']);
            $searchPath = 'class-' . $searchConfig['class'] . '.html';
            if ($searchConfig['property']) {
                if ($searchConfig['type'] == 'method') {
                    $searchPath .= '#_' . $searchConfig['property'];
                } else {
                    $searchPath .= '#$' . $searchConfig['property'];
                }
            }
            $paths[] = $searchPath;
        }

        $url = 'http://' . $this->getServerName() . '/' . implode('/', array_filter($paths));

        if ($return) {
            return $url;
        }
        header('Location: ' . $url);
    }

    /**
     * Allow setting the "version mapping" that can be used to convert "4" to "master", etc
     *
     * @param  array $map
     * @return $this
     */
    public function setVersionMap($map)
    {
        $this->versionMap = $map;
        return $this;
    }

    /**
     * Get the version from the URL, check for manually set version mapping
     *
     * @return string
     */
    public function getVersion()
    {
        $version = $this->getArg('version') ?: self::DEFAULT_BRANCH;
        if (array_key_exists($version, $this->versionMap)) {
            return $this->versionMap[$version];
        }
        return $version;
    }

    /**
     * Using any available silverstripe/upgrader mapping files, see if we can convert an unqualified class name
     * into a fully qualified one
     *
     * @param  string $class
     * @param  bool   $sanitise Whether to have the return value sanitised
     * @return string
     */
    public function getWithNamespace($class, $sanitise = true)
    {
        $map = $this->getUpgraderMap();
        if (!empty($map) && array_key_exists($class, $map)) {
            $class = $map[$class];
        }

        if ($sanitise) {
            return $this->sanitiseNamespaces($class);
        }

        return $class;
    }

    /**
     * Look for silverstripe/upgrader .upgrade.yml mapping files in this version's core modules. If found, combine
     * them and return whether or not anything useful was found.
     *
     * @return bool
     */
    public function getUpgraderMap()
    {
        if ($this->upgraderMap) {
            return $this->upgraderMap;
        }

        $mapFilePaths = __DIR__ . '/../assets/src/' . $this->getVersion() . '/*/.upgrade.yml';
        foreach (glob($mapFilePaths) as $mapFile) {
            try {
                $mapping = (array) Yaml::parse(file_get_contents($mapFile));
            } catch (\Symfony\Component\Yaml\ParseException $ex) {
                // Fail gracefully here
            }
            if (!empty($mapping['mappings'])) {
                $this->upgraderMap = array_merge($this->upgraderMap, $mapping['mappings']);
            }
        }
        return $this->upgraderMap;
    }

    /**
     * Allow ability to set the upgrader mapping manually. Useful for tests.
     *
     * @param array $map
     * @return $this
     */
    public function setUpgraderMap($map)
    {
        $this->upgraderMap = $map;
        return $this;
    }

    /**
     * Returns the given class name with any namespace backslashes replaced with periods, and the leading slash
     * trimmed off.
     *
     * @param  string $class E.g. \SilverStripe\ORM\DataExtension
     * @return string        E.g. SilverStripe.ORM.DataExtension
     */
    public function sanitiseNamespaces($class)
    {
        return str_replace('\\', '.', ltrim($class, '\\'));
    }

    /**
     * Get the server name to use for API reference links. Defaults to retrieving a server variable.
     *
     * @return string
     */
    public function getServerName()
    {
        if ($this->serverName) {
            return $this->serverName;
        }
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * Set the server name to use for API reference links
     *
     * @param string $name
     * @return $this
     */
    public function setServerName($name)
    {
        $this->serverName = $name;
        return $this;
    }
}
