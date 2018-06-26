<?php

namespace SilverStripe\ApiDocs\Parser;

use Prophecy\Doubler\Generator\Node\ClassNode;
use Sami\Parser\NodeVisitor;
use PhpParser\Node\Stmt\ClassLike as ClassLikeNode;
use PhpParser\Node\Stmt\Property as PropertyNode;
use Sami\Reflection\PropertyReflection;
use SilverStripe\ApiDocs\Reflection\SilverStripeClassReflection;

class SilverStripeNodeVisitor extends NodeVisitor
{
    protected function addClassOrInterface(ClassLikeNode $node)
    {
        $class = new SilverStripeClassReflection((string) $node->namespacedName, $node->getLine());
        if ($node instanceof ClassNode) {
            $class->setModifiers($node->flags);
        }
        $class->setNamespace($this->context->getNamespace());
        $class->setAliases($this->context->getAliases());
        $class->setHash($this->context->getHash());
        $class->setFile($this->context->getFile());

        $comment = $this->context->getDocBlockParser()->parse($node->getDocComment(), $this->context, $class);
        $class->setDocComment($node->getDocComment());
        $class->setShortDesc($comment->getShortDesc());
        $class->setLongDesc($comment->getLongDesc());
        if ($errors = $comment->getErrors()) {
            $class->setErrors($errors);
        } else {
            $class->setTags($comment->getOtherTags());
        }

        if ($this->context->getFilter()->acceptClass($class)) {
            if ($errors) {
                $this->context->addErrors((string) $class, $node->getLine(), $errors);
            }
            $this->context->enterClass($class);
        }

        return $class;
    }

    protected function addProperty(PropertyNode $node)
    {
        foreach ($node->props as $prop) {
            $property = new PropertyReflection($prop->name, $prop->getLine());
            $property->setModifiers($node->flags);

            $property->setDefault($prop->default);

            $comment = $this->context->getDocBlockParser()->parse($node->getDocComment(), $this->context, $property);
            $property->setDocComment($node->getDocComment());
            $property->setShortDesc($comment->getShortDesc());
            $property->setLongDesc($comment->getLongDesc());
            if ($errors = $comment->getErrors()) {
                $property->setErrors($errors);
            } else {
                if ($tag = $comment->getTag('var')) {
                    $property->setHint($this->resolveHint($tag[0][0]));
                    $property->setHintDesc($tag[0][1]);
                }

                $property->setTags($comment->getOtherTags());
            }

            if ($this->context->getFilter()->acceptProperty($property)) {
                if ($property->getTags('config')) {
                    $this->context->getClass()->addConfig($property);
                } else {
                    $this->context->getClass()->addProperty($property);
                }

                if ($errors) {
                    $this->context->addErrors((string) $property, $prop->getLine(), $errors);
                }
            }
        }
    }
}
