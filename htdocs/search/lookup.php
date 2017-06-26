<?php
// Lookup script to convert symbol names and other parameters
// to their URL representation in the API docs. Redirects to this URL.
// See README for more info.

require_once __DIR__ . '/../../vendor/autoload.php';

// 2017-06-27: Cannot run composer dump-autoload on SSP
require_once __DIR__ . '/../../src/Lookup.php';

$lookup = new SilverStripe\ApiDocs\Lookup($_GET);

$lookup->setVersionMap(array(
    '4' => 'master',
    '3' => '3.6'
));

$lookup->handle();
