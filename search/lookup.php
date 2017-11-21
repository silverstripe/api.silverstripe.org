<?php
// Lookup script to convert symbol names and other parameters
// to their URL representation in the API docs. Redirects to this URL.
// See README for more info.

require_once __DIR__ .'/../vendor/autoload.php';

$lookup = new SilverStripe\ApiDocs\Lookup($_GET);

$lookup->setVersionMap(array(
    'master' => '5',
    '/^(\d+)[.].*$/' => '$1',
));

$lookup->handle();
