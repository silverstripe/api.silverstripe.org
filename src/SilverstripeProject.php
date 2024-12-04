<?php

namespace SilverStripe\ApiDocs;

use Doctum\Project;
use FilesystemIterator;
use SilverStripe\ApiDocs\Data\Config;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Filesystem\Path;

class SilverstripeProject extends Project
{
    public function update($callback = null, $force = false): void
    {
        $this->renderMainPages();
        $this->copyStaticFiles();
        parent::update($callback, $force);
        $this->outputStatus();
    }

    public function render($callback = null, $force = false): void
    {
        $this->renderMainPages();
        $this->copyStaticFiles();
        parent::render($callback, $force);
        $this->outputStatus();
    }

    /**
     * Renders pages that are not version dependent such as the main index.html
     */
    private function renderMainPages(): void
    {
        $this->version = '';
        $this->read();
        $config = Config::getConfig();
        $variables = [
            // Get array of strings of major version numbers
            'versions' => array_map(fn($version) => (string)$version, array_keys($config['versions'])),
            'default_version' => $config['default_version'],
        ];
        $this->renderer->init($this);
        $this->renderer->save($this, 'index.html', 'main_index.twig', $variables);
        $this->renderer->save($this, 'search.html', 'main_search.twig', $variables);
        $this->renderer->save($this, '404.html', '404.twig', $variables);
    }

    /**
     * Copies static files such as images or css into the build directory
     */
    private function copyStaticFiles(): void
    {
        $this->filesystem->mirror(Path::join(Config::getBase(), 'static'), $this->getBuildDir(), options: ['override' => true]);
    }

    /**
     * Outputs the number of items in htdocs. This can be a useful debugging tool if the site doesn't build correctly.
     */
    private function outputStatus(): void
    {
        $fileIterator = new FilesystemIterator($this->getBuildDir(), FilesystemIterator::SKIP_DOTS);
        $numItems = iterator_count($fileIterator);
        $output = new ConsoleOutput();
        $output->writeln("Found $numItems files/directories in htdocs");
    }

    protected function replaceVars(string $pattern): string
    {
        if (!$this->version) {
            // Remove the version pattern if we're rendering stuff directly into htdocs
            $pattern = str_replace('%version%/', '', $pattern);
        }
        return parent::replaceVars($pattern);
    }
}
