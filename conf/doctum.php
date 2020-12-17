<?php

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use Doctum\Doctum;
use SilverStripe\ApiDocs\Data\ApiJsonStore;
use SilverStripe\ApiDocs\Data\Config;
use SilverStripe\ApiDocs\Inspections\RecipeFinder;
use SilverStripe\ApiDocs\Inspections\RecipeVersionCollection;
use SilverStripe\ApiDocs\SilverStripeProject;
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
$doctum = new Doctum($iterator, [
    'theme' => $config['theme'],
    'title' => $config['title'],
    'base_url' => $config['base_url'],
    'versions' => $versions,
    'build_dir' => Config::configPath($config['paths']['www']) . '/%version%',
    'cache_dir' => Config::configPath($config['paths']['cache']) . '/%version%',
    'template_dirs' => [ __DIR__ .'/themes' ],
]);

// Make sure we document `@config` options
$doctum['filter'] = function() {
    return new \SilverStripe\ApiDocs\Parser\Filter\SilverStripeFilter();
};

$doctum['php_traverser'] = function ($sc) {
    $traverser = new NodeTraverser();
    $traverser->addVisitor(new NameResolver());
    $traverser->addVisitor(new \SilverStripe\ApiDocs\Parser\SilverStripeNodeVisitor($sc['parser_context']));

    return $traverser;
};

$doctum['renderer'] = function ($sc) {
    return new \SilverStripe\ApiDocs\Renderer\SilverStripeRenderer($sc['twig'], $sc['themes'], $sc['tree'], $sc['indexer']);
};

// Override twig
/** @var Twig_Environment $twig */
$twig = $doctum['twig'];
unset($doctum['twig']);
$doctum['twig'] = function () use ($twig) {
    $twig->addExtension(new NavigationExtension());
    return $twig;
};

// Override json store
$store = $doctum['store'];
unset($doctum['store']);
$doctum['store'] = function () {
    return new ApiJsonStore();
};

// Override project
unset($doctum['project']);
$doctum['project'] = function ($sc) {
    $project = new SilverStripeProject($sc['store'], $sc['_versions'], array(
        'build_dir' => $sc['build_dir'],
        'cache_dir' => $sc['cache_dir'],
        'remote_repository' => $sc['remote_repository'],
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

return $doctum;
