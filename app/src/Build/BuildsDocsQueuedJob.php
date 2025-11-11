<?php

namespace SilverStripe\ApiDocs\Build;

use SilverStripe\Core\Injector\Injectable;
use SilverStripe\PolyExecution\PolyOutput;
use Symbiote\QueuedJobs\Services\AbstractQueuedJob;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

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
        $task = BuildDocsTask::create();
        $input = new ArrayInput([]);
        $output = PolyOutput::create(PolyOutput::FORMAT_ANSI, ConsoleOutput::VERBOSITY_VERBOSE);
        $task->run($input, $output);
        $this->isComplete = true;
    }
}
