<?php

namespace SilverStripe\ApiDocs\Parser;

use PhpParser\Node\Stmt\Class_ as ClassNode;
use Doctum\Parser\NodeVisitor;
use PhpParser\Node\Stmt\ClassLike as ClassLikeNode;
use PhpParser\Node\Stmt\Property as PropertyNode;
use SilverStripe\ApiDocs\Reflection\SilverStripeClassReflection;

class SilverStripeNodeVisitor extends NodeVisitor
{
    protected function addClassOrInterface(ClassLikeNode $node)
    {
        $class = new SilverStripeClassReflection((string) $node->namespacedName, $node->getLine());
        return $this->addClassOrInterfaceForReflection($class, $node);
    }

    protected function addProperty(PropertyNode $node)
    {
        foreach ($node->props as $prop) {
            [$property, $errors] = $this->getPropertyReflectionFromParserProperty($node, $prop);

            if ($this->context->getFilter()->acceptProperty($property)) {
                $this->context->getClass()->addProperty($property);

                if ($errors) {
                    $this->context->addErrors((string) $property, $prop->getLine(), $errors);
                }
            }
        }
    }
}
