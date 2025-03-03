<?php

namespace SilverStripe\ApiDocs\Build;

use Symbiote\QueuedJobs\Services\AbstractQueuedJob;
use SilverStripe\Core\Injector\Injectable;

/**
 * Queued job to build the API documentation
 * This job is configured in `job.yml` to recreate itself
 */
class BuildDocsQueuedJob extends AbstractQueuedJob
{
    use Injectable;

    public function getTitle()
    {
        return 'Build API Docs';
    }

    public function process()
    {
        $task = new BuildDocsTask();
        $task->run(null);
        $this->isComplete = true;
    }
}
