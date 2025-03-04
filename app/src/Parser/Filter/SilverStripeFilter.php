<?php

namespace SilverStripe\ApiDocs\Parser\Filter;

use Doctum\Parser\Filter\DefaultFilter;
use Doctum\Reflection\ClassReflection;
use Doctum\Reflection\MethodReflection;
use Doctum\Reflection\PropertyReflection;

class SilverStripeFilter extends DefaultFilter
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
        // Explicitly allow private static properties
        return !$property->getTags('internal') && ($property->isStatic() || parent::acceptProperty($property));
    }
}
