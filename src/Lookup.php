<?php

namespace SilverStripe\ApiDocs;

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
        return null;
    }

    /**
     * Redirect the user to where they need to go.
     * E.g. http://api.silverstripe.org/search/lookup/?q=SilverStripe\ORM\HasManyList&version=4&module=framework
     * redirects to /4/SilverStripe/ORM/HasManyList.html
     *
     * @param bool $return When true, the redirect URL will be returned instead of a header issued
     * @return string|null
     */
    public function handle($return = false)
    {
        $url = $this->getURL();
        if ($return) {
            return $url;
        }
        header('Location: ' . $url);
        return null;
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
     * Base dir
     *
     * @return string
     */
    public function getBaseDir()
    {
        return __DIR__ . '/..';
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

    /**
     * Given a config determine the URL to navigate to
     *
     * @param array $searchConfig
     * @return string
     */
    protected function getURLForClass(array $searchConfig): string
    {
        $searchPath = '/' . $this->getVersion() . '/' . str_replace('\\', '/', $searchConfig['class']) . '.html';

        // If file doesn't exist, redirect to search
        if (!file_exists($this->getBaseDir() . '/htdocs' . $searchPath)) {
            return '/' . $this->getVersion() . '/search.html?search=' . urlencode($searchConfig['class']);
        }

        // Add hash-link on end
        if ($searchConfig['property'] && $searchConfig['type']) {
            $searchPath .= '#' . $searchConfig['type'] . '_' . $searchConfig['property'];
        }

        return $searchPath;
    }

    /**
     * Get url for this search
     *
     * @return string
     */
    protected function getURL(): string
    {
        // Search
        $searchOrig = $this->getArg('q');
        if (!$searchOrig) {
            return '/'; // Just go to home
        }

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

        return $this->getURLForClass($searchConfig);
    }
}
