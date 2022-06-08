<?php

namespace SilverStripe\ApiDocs\Test;

use PHPUnit\Framework\TestCase;
use SilverStripe\ApiDocs\RemoteRepository\SilverStripeRemoteRepository;

class SilverStripeRemoteRepositoryTest extends TestCase
{
    public function testGetRepoUrl()
    {
        $remoteRepo = new SilverStripeRemoteRepository(
            '',
            '/mnt/Dev/@code-lts/@doctum-fork/api.silverstripe.org/data/packages/'
        );

        $url = $remoteRepo->getFileUrl(
            '4',
            'silverstripe/admin/code/AdminRootController.php',
            0
        );

        $this->assertSame(
            'https://github.com/silverstripe/silverstripe-admin/blob/1/code/AdminRootController.php#L0',
            $url
        );

        $url = $remoteRepo->getFileUrl(
            '4',
            'silverstripe/graphql/src/Extensions/DevBuildExtension.php',
            0
        );

        $this->assertSame(
            'https://github.com/silverstripe/'
            . 'silverstripe-graphql/blob/4/src/Extensions/DevBuildExtension.php#L0',
            $url
        );

        $url = $remoteRepo->getFileUrl(
            'master',
            'silverstripe/graphql/src/Extensions/DevBuildExtension.php',
            0
        );

        $this->assertSame(
            'https://github.com/silverstripe/'
            . 'silverstripe-graphql/blob/master/src/Extensions/DevBuildExtension.php#L0',
            $url
        );

        $url = $remoteRepo->getFileUrl(
            '3',
            'silverstripe/graphql/src/Extensions/IntrospectionProvider.php',
            0
        );

        $this->assertSame(
            'https://github.com/silverstripe/'
            . 'silverstripe-graphql/blob/3/src/Extensions/IntrospectionProvider.php#L0',
            $url
        );

        // A mysterious version did pop out, oh snap
        $url = $remoteRepo->getFileUrl(
            'foobar',
            'silverstripe/graphql/src/Extensions/IntrospectionProvider.php',
            0
        );

        $this->assertSame(
            'https://github.com/silverstripe/'
            . 'silverstripe-graphql/blob/foobar/src/Extensions/IntrospectionProvider.php#L0',
            $url
        );
    }
}
