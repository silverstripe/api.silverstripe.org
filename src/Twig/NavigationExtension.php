<?php

namespace SilverStripe\ApiDocs\Twig;

use Twig_Extension;
use Twig_Function;

class NavigationExtension extends Twig_Extension
{
    public function getFunctions()
    {
        return [
            new Twig_Function('file_get_contents', [$this, 'file_get_contents']),
        ];
    }

    protected $navigation = [];

    /**
     * Import url
     *
     * @param string $url
     * @return string
     */
    public function file_get_contents($url)
    {
        // http://www.silverstripe.org/assets/global-nav-api.html
        if (!isset($this->navigation[$url])) {
            $this->navigation[$url] = file_get_contents($url);
        }
        return $this->navigation[$url];
    }
}
