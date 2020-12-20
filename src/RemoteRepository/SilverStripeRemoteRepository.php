<?php

namespace SilverStripe\ApiDocs\RemoteRepository;

use Doctum\RemoteRepository\AbstractRemoteRepository;
use SilverStripe\ApiDocs\Data\Config;

class SilverStripeRemoteRepository extends AbstractRemoteRepository
{

    public function getFileUrl($projectVersion, $relativePath, $line)
    {
        // $this->localPath is the root path to packages
        // Example: /mnt/Dev/@code-lts/@doctum-fork/api.silverstripe.org/data/packages/
        // $this->name is empty, and not used
        // $relativePath for the current file
        // Example "silverstripe/admin/code/AdminRootController.php"
        $pathParts = explode('/', $relativePath, 3);
        // Example: [silverstripe, admin, code/AdminRootController.php]
        $this->name = $pathParts[0] . '/' . $pathParts[1];

        $packageConfig = Config::getConfig()['packages'][$this->name];
        $rootPath = $packageConfig['repository'];
        if (substr($rootPath, -4) === '.git') {
            $rootPath = substr($rootPath, 0, -4);
        }
        if (isset($packageConfig['versionmap'])) {
            $versionMaps = Config::getConfig()['versionmaps'];
            $projectVersion = $versionMaps[$packageConfig['versionmap']][(string) $projectVersion];
        }
        $url = $rootPath . '/blob/' . $this->buildProjectPath($projectVersion, $pathParts[2]);

        if (null !== $line) {
            $url .= '#L' . (int) $line;
        }

        return $url;
    }
}
