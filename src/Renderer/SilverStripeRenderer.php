<?php

namespace SilverStripe\ApiDocs\Renderer;

use Doctum\Message;
use Doctum\Project;
use Doctum\Renderer\Renderer;
use SilverStripe\ApiDocs\Reflection\SilverStripeClassReflection;

class SilverStripeRenderer extends Renderer
{
    protected function renderClassTemplates(array $classes, Project $project, $callback = null)
    {
        foreach ($classes as $class) {
            if (null !== $callback) {
                call_user_func(
                    $callback,
                    Message::RENDER_PROGRESS,
                    ['Class', $class->getName(), $this->step, $this->steps]
                );
            }

            $properties = $class->getProperties($project->getConfig('include_parent_data'));

            $sortProperties = $project->getConfig('sort_class_properties');
            if ($sortProperties) {
                if (is_callable($sortProperties)) {
                    uksort($properties, $sortProperties);
                } else {
                    ksort($properties);
                }
            }

            if ($class instanceof SilverStripeClassReflection) {
                $configs = $class->getConfigs($project->getConfig('include_parent_data'));
                if ($sortProperties) {
                    if (is_callable($sortProperties)) {
                        uksort($configs, $sortProperties);
                    } else {
                        ksort($properties);
                    }
                }
            } else {
                $configs = array();
            }

            $methods = $class->getMethods($project->getConfig('include_parent_data'));

            $sortMethods = $project->getConfig('sort_class_methods');
            if ($sortMethods) {
                if (is_callable($sortMethods)) {
                    uksort($methods, $sortMethods);
                } else {
                    ksort($methods);
                }
            }

            $constants = $class->getConstants($project->getConfig('include_parent_data'));

            $sortConstants = $project->getConfig('sort_class_constants');
            if ($sortConstants) {
                if (is_callable($sortConstants)) {
                    uksort($constants, $sortConstants);
                } else {
                    ksort($constants);
                }
            }

            $traits = $class->getTraits($project->getConfig('include_parent_data'));

            $sortTraits = $project->getConfig('sort_class_traits');
            if ($sortTraits) {
                if (is_callable($sortTraits)) {
                    uksort($traits, $sortTraits);
                } else {
                    ksort($traits);
                }
            }

            $sortInterfaces = $project->getConfig('sort_class_interfaces');
            if ($sortInterfaces) {
                $class->sortInterfaces($sortInterfaces);
            }

            $variables = [
                'class' => $class,
                'properties' => $properties,
                'configs' => $configs,
                'methods' => $methods,
                'constants' => $constants,
                'traits' => $traits,
                'tree' => $this->getTree($project),
            ];

            foreach ($this->theme->getTemplates('class') as $template => $target) {
                $this->save(
                    $project,
                    sprintf($target, str_replace('\\', '/', $class->getName())),
                    $template,
                    $variables
                );
            }
        }
    }

    private function getTree(Project $project)
    {
        $key = $project->getBuildDir();
        if (!isset($this->cachedTree[$key])) {
            $this->cachedTree[$key] = $this->tree->getTree($project);
        }

        return $this->cachedTree[$key];
    }
}
