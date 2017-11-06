<?php

namespace SilverStripe\ApiDocs;

use SilverStripe\ApiDocs\Commands\BuildCommand;
use SilverStripe\ApiDocs\Commands\CheckoutCommand;
use Symfony\Component\Console\Application;

class DocsApplication extends Application
{
    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();
        $commands[] = new CheckoutCommand('checkout');
        $commands[] = new BuildCommand('build');
        return $commands;
    }
}
