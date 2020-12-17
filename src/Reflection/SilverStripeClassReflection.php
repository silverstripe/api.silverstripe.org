<?php

namespace SilverStripe\ApiDocs\Reflection;

use Doctum\Reflection\ClassReflection;
use Doctum\Reflection\PropertyReflection;
use SilverStripe\ApiDocs\Data\Config;

class SilverStripeClassReflection extends ClassReflection
{
    const CATEGORY_CONFIG = 4;

    private static $categoryName = [
        1 => 'class',
        2 => 'interface',
        3 => 'trait',
        4 => 'config',
    ];

    protected $configs = [];

    public function addConfig(PropertyReflection $property)
    {
        $this->configs[$property->getName()] = $property;
        $property->setClass($this);
    }

    public function getConfigs()
    {
        return $this->configs;
    }

    public function getSourcePath($line = null)
    {
        $config = Config::getConfig();
        $realPackagePath = realpath(Config::configPath($config['paths']['packages']));
        $realRelativeFilePath = realpath($this->relativeFilePath);
        $filePath = substr($realRelativeFilePath, strlen($realPackagePath) + 1);
        $parts = explode('/', $filePath);
        $package = "{$parts[0]}/{$parts[1]}";
        $this->project->setRemoteRepository($config['packages'][$package]['repository']);
        $relativePathFromPackage = substr($filePath, strlen($package));

        return $this->project->getViewSourceUrl($relativePathFromPackage, $line);
    }
}
