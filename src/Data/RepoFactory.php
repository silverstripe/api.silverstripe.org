<?php


namespace SilverStripe\ApiDocs\Data;


use Gitonomy\Git\Repository;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

class RepoFactory
{
    /**
     * Get git repo
     *
     * @param string $path
     * @param OutputInterface $output
     * @return Repository
     */
    public static function repoFor($path, OutputInterface $output = null)
    {
        // Git options
        // Make repo
        $repo = new Repository($path, self::options());
        if ($output) {
            $repo->setLogger(new ConsoleLogger($output));
        }
        return $repo;
    }

    /**
     * git options to use
     *
     * @return array
     */
    public static function options(): array
    {
        return [
            'environment_variables' => [
                'HOME' => getenv('HOME'),
                'SSH_AUTH_SOCK' => getenv('SSH_AUTH_SOCK')
            ]
        ];
    }
}
