<?php

use Sami\Project;
use Sami\Sami;
use SilverStripe\ApiDocs\Data\ApiJsonStore;
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
    'base_url' => $config['base_url'],
    'versions' => $versions,
    'build_dir' => Config::configPath($config['paths']['www']) . '/%version%',
    'cache_dir' => Config::configPath($config['paths']['cache']) . '/%version%',
    'default_opened_level' => 2,
    'template_dirs' => [ __DIR__ .'/themes' ],
]);

// Make sure we document `@config` options
$sami['filter'] = function() {
    return new \SilverStripe\ApiDocs\Parser\Filter\SilverStripeFilter();
};

// Override twig
/** @var Twig_Environment $twig */
$twig = $sami['twig'];
unset($sami['twig']);
$sami['twig'] = function () use ($twig) {
    $twig->addExtension(new NavigationExtension());
    return $twig;
};

// Override json store
$store = $sami['store'];
unset($sami['store']);
$sami['store'] = function () {
    return new ApiJsonStore();
};

// Override project
unset($sami['project']);
$sami['project'] = function ($sc) {
    $project = new Project($sc['store'], $sc['_versions'], array(
        'build_dir' => $sc['build_dir'],
        'cache_dir' => $sc['cache_dir'],
        'remote_repository' => $sc['remote_repository'],
        'simulate_namespaces' => $sc['simulate_namespaces'],
        'include_parent_data' => $sc['include_parent_data'],
        'default_opened_level' => $sc['default_opened_level'],
        'theme' => $sc['theme'],
        'title' => $sc['title'],
        'source_url' => $sc['source_url'],
        'source_dir' => $sc['source_dir'],
        'insert_todos' => $sc['insert_todos'],
        'base_url' => $sc['base_url'],
    ));
    $project->setRenderer($sc['renderer']);
    $project->setParser($sc['parser']);

    return $project;
};

return $sami;
