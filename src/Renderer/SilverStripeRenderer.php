<?php

namespace SilverStripe\ApiDocs\Renderer;

use Doctum\Project;
use Doctum\Renderer\Renderer;
use SilverStripe\ApiDocs\Reflection\SilverStripeClassReflection;

class SilverStripeRenderer extends Renderer
{
    /**
     * @inheritDoc
     * This is implemented here to promote visibility to public
     */
    public function save(Project $project, $uri, $template, $variables)
    {
        return parent::save($project, $uri, $template, $variables);
    }

    /**
     * Initialise the renderer so we can render versionless templates.
     * This happens when rendering version templates normally, but we want to render the global stuff first.
     */
    public function init(Project $project): void
    {
        // This is all copied from the render() method
        $this->theme = $this->getTheme($project);
        $dirs        = $this->theme->getTemplateDirs();
        // add parent directory to be able to extends the same template as the current one but in the parent theme
        foreach ($dirs as $dir) {
            $dirs[] = dirname($dir);
        }
        $this->twig->getLoader()->setPaths(array_unique($dirs));
        $this->twig->addGlobal('has_namespaces', $project->hasNamespaces());
        $this->twig->addGlobal('project', $project);
    }

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
