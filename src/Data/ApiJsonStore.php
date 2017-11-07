<?php

namespace SilverStripe\ApiDocs\Data;

use Sami\Project;
use Sami\Store\JsonStore;

/**
 * Adjust json store to not fail on branches with fewer classes than others
 */
class ApiJsonStore extends JsonStore
{
    public function removeClass(Project $project, $name)
    {
        $path = $this->getFilename($project, $name);
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
