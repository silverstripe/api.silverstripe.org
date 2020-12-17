<?php

namespace SilverStripe\ApiDocs\Renderer;

use Doctum\Project;
use Doctum\Renderer\Renderer;
use SilverStripe\ApiDocs\Reflection\SilverStripeClassReflection;

class SilverStripeRenderer extends Renderer
{
    protected function renderClassTemplates(array $classes, Project $project, $callback = null)
    {
        foreach ($classes as $class) {
            $variables = $this->getVariablesFromClassReflection($class, $project, $callback);

            $configs = [];

            if ($class instanceof SilverStripeClassReflection) {
                $configs = $class->getConfigs($project->getConfig('include_parent_data'));
                $sortProperties = $project->getConfig('sort_class_properties');
                if ($sortProperties) {
                    if (is_callable($sortProperties)) {
                        uksort($configs, $sortProperties);
                    } else {
                        ksort($properties);
                    }
                }
            }

            $variables['configs'] = $configs;

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
}
