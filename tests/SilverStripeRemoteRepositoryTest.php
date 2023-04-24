<?php

namespace SilverStripe\ApiDocs\Test;

use PHPUnit\Framework\TestCase;
use SilverStripe\ApiDocs\Data\Config;
use SilverStripe\ApiDocs\RemoteRepository\SilverStripeRemoteRepository;

class SilverStripeRemoteRepositoryTest extends TestCase
{
    private static ?string $fileContent = null;

    public static function setUpBeforeClass(): void
    {
        $config = Config::getConfig();
        $filePath = Config::configPath($config['paths']['versionmap']);
        $fileDir = dirname($filePath);
        if (!is_dir($fileDir)) {
            mkdir($fileDir, 0755, true);
        }
        if (file_exists($filePath)) {
            self::$fileContent = file_get_contents($filePath);
            unlink($filePath);
        }
        file_put_contents($filePath, json_encode([
            "silverstripe/admin" => [
                "repository" => "https://github.com/silverstripe/silverstripe-admin.git",
                "versionmap" => [
                    "5" => "2",
                    "4" => "1",
                    "3" => null,
                ],
            ],
            "silverstripe/asset-admin" => [
                "repository" => "https://github.com/silverstripe/silverstripe-asset-admin.git",
                "versionmap" => [
                    "5" => "2",
                    "4" => "1",
                    "3" => null,
                ],
            ],
            "silverstripe/assets" => [
                "repository" => "https://github.com/silverstripe/silverstripe-assets.git",
                "versionmap" => [
                    "5" => "2",
                    "4" => "1",
                    "3" => null,
                ],
            ],
            "silverstripe/cms" => [
                "repository" => "https://github.com/silverstripe/silverstripe-cms.git",
                "versionmap" => [
                    "5" => "5",
                    "4" => "4",
                    "3" => "3",
                ],
            ],
            "silverstripe/config" => [
                "repository" => "https://github.com/silverstripe/silverstripe-config.git",
                "versionmap" => [
                    "5" => "2",
                    "4" => "1",
                    "3" => "1",
                ],
            ],
            "silverstripe/framework" => [
                "repository" => "https://github.com/silverstripe/silverstripe-framework.git",
                "versionmap" => [
                    "5" => "5",
                    "4" => "4",
                    "3" => "3",
                ],
            ],
            "silverstripe/graphql" => [
                "repository" => "https://github.com/silverstripe/silverstripe-graphql.git",
                "versionmap" => [
                    "5" => "5",
                    "4" => "4",
                    "3" => null,
                ],
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    public static function tearDownAfterClass(): void
    {
        $config = Config::getConfig();
        $filePath = Config::configPath($config['paths']['versionmap']);
        unlink($filePath);
        if (self::$fileContent !== null) {
            file_put_contents($filePath, self::$fileContent);
        }
    }

    public function provideGetRepoUrl()
    {
        return [
            [
                '4',
                'silverstripe/admin/code/AdminRootController.php',
                0,
                'https://github.com/silverstripe/silverstripe-admin/blob/1/code/AdminRootController.php#L0',
            ],
            [
                '4',
                'silverstripe/graphql/src/Extensions/DevBuildExtension.php',
                18,
                'https://github.com/silverstripe/'
                . 'silverstripe-graphql/blob/4/src/Extensions/DevBuildExtension.php#L18',
            ],
            [
                '5',
                'silverstripe/graphql/src/Extensions/DevBuildExtension.php',
                0,
                'https://github.com/silverstripe/'
                . 'silverstripe-graphql/blob/5/src/Extensions/DevBuildExtension.php#L0',
            ],
            [
                '4',
                'silverstripe/graphql/src/Extensions/IntrospectionProvider.php',
                53,
                'https://github.com/silverstripe/'
                . 'silverstripe-graphql/blob/4/src/Extensions/IntrospectionProvider.php#L53',
            ],
            [
                '5',
                'silverstripe/cms/code/model/SiteTree.php',
                0,
                'https://github.com/silverstripe/'
                . 'silverstripe-cms/blob/5/code/model/SiteTree.php#L0',
            ],
            [
                '3',
                'silverstripe/cms/code/model/SiteTree.php',
                0,
                'https://github.com/silverstripe/'
                . 'silverstripe-cms/blob/3/code/model/SiteTree.php#L0',
            ],
            // A mysterious version did pop out, oh snap
            [
                'foobar',
                'silverstripe/graphql/src/Extensions/IntrospectionProvider.php',
                0,
                'https://github.com/silverstripe/'
                . 'silverstripe-graphql/blob/foobar/src/Extensions/IntrospectionProvider.php#L0',
                true,
            ],
        ];
    }

    /**
     * @dataProvider provideGetRepoUrl
     */
    public function testGetRepoUrl(string $version, string $path, int $line, string $expected, bool $emitsWarning = false)
    {
        $remoteRepo = new SilverStripeRemoteRepository(
            '',
            '/mnt/Dev/@code-lts/@doctum-fork/api.silverstripe.org/data/packages/'
        );

        if ($emitsWarning) {
            $this->expectWarning();
        }

        $this->assertSame($expected, $remoteRepo->getFileUrl($version, $path, $line));
    }
}
