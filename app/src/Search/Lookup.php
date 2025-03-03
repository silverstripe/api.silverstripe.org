<?php

namespace SilverStripe\ApiDocs\Search;

/**
 * Lookup to convert symbol names and other parameters to their URL representation in the API docs
 */
class Lookup
{
    /**
     * The default branch name for each core module
     */
    public const DEFAULT_BRANCH = '5';

    protected array $args = [];

    protected array $versionMap = [];

    public function __construct(array $args)
    {
        $this->args = $args;
    }

    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * Return an argument from the array
     */
    public function getArg(string $key): ?string
    {
        return $this->args[$key] ?? null;
    }

    /**
     * Redirect the user to where they need to go.
     * E.g. http://api.silverstripe.org/search/lookup/?q=SilverStripe\ORM\HasManyList&version=4&module=framework
     * redirects to /4/SilverStripe/ORM/HasManyList.html
     *
     * @param bool $return When true, the redirect URL will be returned instead of a header issued
     */
    public function handle(bool $return = false): ?string
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
     * Useful in the event that modules have strange branching strategies
     */
    public function setVersionMap(array $map): static
    {
        $this->versionMap = $map;
        return $this;
    }

    /**
     * Get the version from the URL, check for manually set version mapping
     */
    public function getVersion(): string
    {
        $version = $this->getArg('version') ?? self::DEFAULT_BRANCH;
        if (!is_string($version) || !is_numeric($version)) {
            $version = self::DEFAULT_BRANCH;
        }
        foreach ($this->versionMap as $rule => $substitution) {
            // Check regular expression rule
            if (strpos($rule, '/') === 0 && preg_match($rule, $version)) {
                return preg_replace($rule, $substitution, $version);
            }
            // Check exact rule
            if (strpos($rule, '/') === false && $rule === $version) {
                return $substitution;
            }
        }
        return $version;
    }

    /**
     * Given a config determine the URL to navigate to
     */
    protected function getURLForClass(array $searchConfig): string
    {
        // If there's anything in the class name that could be used for things like directory traversal,
        // then just return '/';
        // Note that the quad backslash is to escape the backslash in the regex is intentional
        if (preg_match('#[^a-zA-Z0-9\\\\_]#', $searchConfig['class'])) {
            return '/';
        }

        // If file doesn't exist, redirect to search
        $searchPath = '/' . $this->getVersion() . '/' . str_replace('\\', '/', $searchConfig['class']) . '.html';
        if (!$this->staticFileExists($searchPath)) {
            return '/' . $this->getVersion() . '/search.html?search=' . urlencode($searchConfig['class']);
        }

        // Add hash-link on end
        if ($searchConfig['property'] && $searchConfig['type']) {
            $searchPath .= '#' . $searchConfig['type'] . '_' . $searchConfig['property'];
        }

        return $searchPath;
    }

    /**
     * Whether a static file exists for this search
     */
    protected function staticFileExists(string $searchPath): bool
    {
        $baseDir = dirname(__DIR__, 3);
        $path = $baseDir . '/public' . $searchPath;
        return file_exists($path);
    }

    /**
     * Get url for this search
     */
    protected function getURL(): string
    {
        $searchOrig = $this->getArg('q');
        if (!$searchOrig) {
            return '/';
        }

        $search = str_replace(['()', '$'], '', $searchOrig);
        $searchParts = preg_split('/(::|\->)/', $search);
        $searchConfig = [];
        if (count($searchParts) === 2) {
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
