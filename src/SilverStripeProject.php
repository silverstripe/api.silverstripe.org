<?php
namespace SilverStripe\ApiDocs;

use Doctum\Project;
use Doctum\RemoteRepository\GitHubRemoteRepository;
use SilverStripe\ApiDocs\Data\Config;

class SilverStripeProject extends Project
{
    public function setRemoteRepository($remoteRepositoryName)
    {
        if (strpos($remoteRepositoryName, 'https://github.com/') !== false) {
            $remoteRepositoryName = substr($remoteRepositoryName, strlen('https://github.com/'));
        }

        if (substr($remoteRepositoryName, -4) === '.git') {
            $remoteRepositoryName = substr($remoteRepositoryName, 0, -4);
        }

        $this->config['remote_repository'] = new GitHubRemoteRepository(
            $remoteRepositoryName,
            Config::getConfig()['paths']['packages'] . "/{$remoteRepositoryName}"
        );
    }
}
