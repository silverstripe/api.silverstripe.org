<?php

namespace SilverStripe\ApiDocs\Data;

use Exception;

class Config
{
    /**
     * Base path
     *
     * @return string
     */
    public static function getBase()
    {
        return __DIR__ .'/../..';
    }

    /**
     * Load config
     * @return array
     * @throws Exception
     */
    public static function getConfig()
    {
        $path = static::getBase() . '/conf/doctum.json';
        if (!file_exists($path)) {
            throw new Exception("File $path not found");
        }
        $result = json_decode(file_get_contents($path), true);
        if (json_last_error()) {
            throw new Exception(json_last_error_msg());
        }
        return $result;
    }

    /**
     * Get the map of packages and versions to branches.
     * @throws Exception
     */
    public static function getVersionMap()
    {
        $config = static::getConfig();
        $path = Config::configPath($config['paths']['versionmap']);
        if (!file_exists($path)) {
            throw new Exception("File $path not found");
        }
        return json_decode(file_get_contents($path), true);
    }

    /**
     * Map named path to absolute path
     *
     * @param string $path
     * @return string
     */
    public static function configPath($path)
    {
        return static::getBase() . '/' . $path;
    }
}
