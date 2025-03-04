<?php

namespace SilverStripe\ApiDocs\Search;

use SilverStripe\Control\Controller;
use SilverStripe\ApiDocs\Search\Lookup;
use SilverStripe\Control\HTTPRequest;

class LookupController extends Controller
{
    public function index(HTTPRequest $request)
    {
        $lookup = new Lookup($request->getVars());
        $lookup->setVersionMap(['/^(\d+)[.].*$/' => '$1']);
        $location = $lookup->handle(true);
        $response = $this->getResponse();
        $response->setStatusCode(302);
        $response->addHeader('Location', $location);
        return $response;
    }
}
