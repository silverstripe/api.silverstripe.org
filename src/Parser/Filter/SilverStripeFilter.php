<?php

namespace SilverStripe\ApiDocs\Parser\Filter;

use Doctum\Parser\Filter\PublicFilter;
use Doctum\Reflection\ClassReflection;
use Doctum\Reflection\MethodReflection;
use Doctum\Reflection\PropertyReflection;

class SilverStripeFilter extends PublicFilter
{
    public function acceptClass(ClassReflection $class)
    {
        return !$class->getTags('internal') && parent::acceptClass($class);
    }

    public function acceptMethod(MethodReflection $method)
    {
        return !$method->getTags('internal') && parent::acceptMethod($method);
    }

    public function acceptProperty(PropertyReflection $property)
    {
        // if there's a config tag, then we want to document it
        return !$property->getTags('internal') &&
            ($property->getTags('config') || parent::acceptProperty($property));
    }
}
