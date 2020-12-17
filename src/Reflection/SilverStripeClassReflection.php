<?php

namespace SilverStripe\ApiDocs\Reflection;

use Doctum\Reflection\ClassReflection;
use Doctum\Reflection\PropertyReflection;

class SilverStripeClassReflection extends ClassReflection
{

    /** @var array<string,PropertyReflection> */
    protected $configs = [];

    public function addConfig(PropertyReflection $property): void
    {
        $this->configs[$property->getName()] = $property;
        $property->setClass($this);
    }

    public function getConfigs(): array
    {
        return $this->configs;
    }
}
