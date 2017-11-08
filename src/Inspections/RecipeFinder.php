<?php

namespace SilverStripe\ApiDocs\Inspections;

use ReflectionProperty;
use Symfony\Component\Finder\Finder;

class RecipeFinder extends Finder
{
    /**
     * @var RecipeVersionCollection
     */
    protected $collection;

    public function __construct(RecipeVersionCollection $collection)
    {
        parent::__construct();
        $this->collection = $collection;
    }

    public function getIterator()
    {
        // Clear all dirs
        $this->resetDirs();

        // Add all recipes active in the current version
        $packages = $this->collection->getActivePackages();
        foreach ($packages as $package) {
            $path = $this->collection->getPackagePath($package);
            $this->in($path);
        }

        // Build iterator using active repos
        return parent::getIterator();
    }

    /**
     * Reset the dirs property
     */
    protected function resetDirs()
    {
        // Reset $this->dirs to the list of package paths
        // I read http://fabien.potencier.org/pragmatism-over-theory-protected-vs-private.html and I feel depressed
        $dirsProp = new ReflectionProperty(Finder::class, 'dirs');
        $dirsProp->setAccessible(true);
        $dirsProp->setValue($this, []);
    }
}
