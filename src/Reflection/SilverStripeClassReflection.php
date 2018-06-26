<?php

namespace SilverStripe\ApiDocs\Reflection;

use Sami\Reflection\ClassReflection;
use Sami\Reflection\PropertyReflection;

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
}
