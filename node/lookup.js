const path = require('path');
const fs = require('fs');

/**
 * The default branch name for each core module
 *
 * @var string
 */
const DEFAULT_BRANCH = 'master';

/**
 * Lookup script to convert symbol names and other parameters to their URL representation in the API docs.
 *
 * See README for more info.
 */
class Lookup {

    /**
     * @var string[]
     */
    args = [];

    /**
     * @var Map
     */
    versionMap = new Map();

    /**
     * @var string
     */
    serverName = null;

    /**
     * @param array args
     */
    constructor(args = [])
    {
        this.args = args;
    }

    /**
     * @return string[]
     */
    getArgs()
    {
        return this.args;
    }


    /**
     * Return an argument from the array
     *
     * @param  string key
     * @return string|null
     */
    getArg(key)
    {
        return this.args[key] || null;
    }


    /**
     * Redirect the user to where they need to go.
     * E.g. http://api.silverstripe.org/search/lookup/?q=SilverStripe\ORM\HasManyList&version=4&module=framework
     * redirects to /4/SilverStripe/ORM/HasManyList.html
     *
     * @return string|null
     */
    handle()
    {
        return this.getURL();
    }

    /**
     * Allow setting the "version mapping" that can be used to convert "4" to "master", etc
     *
     * @param  Map map
     * @return this
     */
    setVersionMap(map)
    {
        this.versionMap = map;
        return this;
    }


    /**
     * Get the version from the URL, check for manually set version mapping
     *
     * @return string
     */
    getVersion()
    {
        let version = this.getArg('version') || DEFAULT_BRANCH;

        this.versionMap.forEach((rule, substitution) => {
            // Check regular expression rule
            if (rule instanceof RegExp) {
                version = version.replace(rule, substitution);
            } else if (rule === version) {
                version = substitution;
            }
        })
        return version;
    }

    /**
     * Base dir
     *
     * @return string
     */
    getBaseDir()
    {
        return path.resolve(`${__dirname}/..`);
    }

    /**
     * Set the server name to use for API reference links
     *
     * @param string name
     * @return this
     */
    setServerName(name)
    {
        this.serverName = name;
        return this;
    }

    /**
     * Given a config determine the URL to navigate to
     *
     * @param array searchConfig
     * @return string
     */
    getURLForClass(searchConfig = [])
    {
        const basename = searchConfig['class'].replace('\\', '/');
        let searchPath = `/${this.getVersion()}/${basename}.html`;

        // If file doesn't exist, redirect to search
        if (!fs.existsSync(path.join(this.getBaseDir(), 'htdocs', searchPath))) {
            return path.join('/', this.getVersion(), `/search.html?search=${encodeURI(searchConfig['class'])}`);
        }

        // Add hash-link on end
        if (searchConfig.property && searchConfig.type) {
            searchPath += `#${searchConfig.type}_${searchConfig.property}`;
        }

        return searchPath;
    }

    /**
     * Get url for this search
     *
     * @return string
     */
    getURL()
    {
        // Search
        const searchOrig = this.getArg('q');
        if (!searchOrig) {
            return '/'; // Just go to home
        }

        const search = searchOrig.replace('()', '').replace('$', '');
        const searchParts = search.split(/(::|\->)/);
        const searchConfig = {};
        if (searchParts.length == 2) {
            searchConfig['class'] = searchParts[0];
            searchConfig.property = searchParts[1];
            searchConfig.type = searchOrig.match('()') ? 'method' : 'property';
        } else {
            searchConfig['class'] = search;
            searchConfig.property = '';
            searchConfig.type = 'class';
        }

        return this.getURLForClass(searchConfig);
    }
}

module.exports = Lookup;
