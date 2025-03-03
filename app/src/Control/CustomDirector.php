<?php

namespace SilverStripe\ApiDocs\Control;

use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;

/**
 * Custom Director implementation that uses content from a file if one exists for the given error response status code.
 * This is done because the public/.htaccess ErrorDocument doesn't work and we aren't using silverstripe/errorpage
 * which includes routing for errors.
 */
class CustomDirector extends Director
{
    public function handleRequest(HTTPRequest $request)
    {
        $response = parent::handleRequest($request);
        if ($response->isError()) {
            $code = $response->getStatusCode();
            $path = BASE_PATH . "/public/errors/$code.html";
            if (file_exists($path)) {
                $response->setBody(file_get_contents($path));
            }
        }
        return $response;
    }
}
