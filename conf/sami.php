<?php

use Sami\Sami;
use Sami\Version\Version;
use SilverStripe\ApiDocs\Data\Config;
use SilverStripe\ApiDocs\Inspections\RecipeFinder;
use SilverStripe\ApiDocs\Inspections\RecipeVersionCollection;
use SilverStripe\ApiDocs\Twig\NavigationExtension;

// Get config
$config = Config::getConfig();

// Build versions
/** @var RecipeVersionCollection $versions */
$versions = new RecipeVersionCollection();
foreach ($config['versions'] as $version => $description) {
    $versions->add((string)$version, $description);
}

// Build finder which draws from the recipe collection
$iterator = new RecipeFinder($versions);
$iterator
    ->files()
    ->name('*.php')
    ->exclude('thirdparty')
    ->exclude('examples')
    ->exclude('tests');

// Config
$sami = new Sami($iterator, [
    'theme' => $config['theme'],
    'title' => $config['title'],
    'versions' => $versions,
    'build_dir' => Config::configPath($config['paths']['www']) . '/%version%',
    'cache_dir' => Config::configPath($config['paths']['cache']) . '/%version%',
    'default_opened_level' => 2,
    'template_dirs' => [ __DIR__ .'/themes' ],
]);

// Override twig
/** @var Twig_Environment $twig */
$twig = $sami['twig'];
unset($sami['twig']);
$sami['twig'] = function () use ($twig) {
    $twig->addExtension(new NavigationExtension());
    return $twig;
};

return $sami;
