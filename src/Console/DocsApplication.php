<?php

namespace SilverStripe\ApiDocs\Console;

use Doctum\Console\Application;

class DocsApplication extends Application
{
    public function __construct()
    {
        parent::__construct();
        $this->add(new CheckoutCommand('checkout'));
    }
}
