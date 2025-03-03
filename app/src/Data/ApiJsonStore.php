<?php

namespace SilverStripe\ApiDocs\Data;

use Doctum\Project;
use Doctum\Store\JsonStore;
use RuntimeException;

/**
 * Adjust json store to not fail on branches with fewer classes than others
 */
class ApiJsonStore extends JsonStore
{
    public function removeClass(Project $project, $name)
    {
        try {
            parent::removeClass($project, $name);
        } catch (RuntimeException $e) {
            // no-op - let the site finish building.
        }
    }

    public function removeFunction(Project $project, string $name): void
    {
        try {
            parent::removeFunction($project, $name);
        } catch (RuntimeException $e) {
            // no-op - let the site finish building.
        }
    }
}
