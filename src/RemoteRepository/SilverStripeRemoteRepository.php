<?php

namespace SilverStripe\ApiDocs\RemoteRepository;

use Doctum\RemoteRepository\AbstractRemoteRepository;

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
        $url = 'https://github.com/' . $this->name . '/blob/' . $this->buildProjectPath($projectVersion, $relativePath);

        if (null !== $line) {
            $url .= '#L' . (int) $line;
        }

        return $url;
    }
}
