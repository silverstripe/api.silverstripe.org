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
    }

    public function getConfigs($deep = false): array
    {
        if (empty($this->configs) && $this->hasTrait('SilverStripe\Core\Config\Configurable')) {
            foreach ($this->getProperties($deep) as $property) {
                if ($this->propertyIsConfig($property)) {
                    $this->addConfig($property);
                }
            }
        }
        return $this->configs;
    }

    public function propertyIsConfig(PropertyReflection $property): bool
    {
        return !$property->isInternal() && $property->isStatic() && $property->isPrivate();
    }

    private function hasTrait(string $traitName): bool
    {
        $traits = $this->getTraits(true);
        foreach ($traits as $trait) {
            if ($trait->getName() === $traitName) {
                return true;
            }
        }
        return false;
    }
}
