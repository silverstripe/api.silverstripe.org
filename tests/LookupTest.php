<?php

namespace SilverStripe\ApiDocs\Test;

use PHPUnit\Framework\TestCase;
use SilverStripe\ApiDocs\Search\Lookup;

class LookupTest extends TestCase
{
    public function testGetArgs()
    {
        $lookup = new Lookup(['foo' => 'bar']);
        $this->assertSame(['foo' => 'bar'], $lookup->getArgs());
        $this->assertSame('bar', $lookup->getArg('foo'));
    }

    public function testGetUrl()
    {
        $mock = $this->createPartialMock(Lookup::class, ['getURL']);
        $mock->expects($this->once())->method('getURL')->willReturn('foobar');

        $this->assertSame('foobar', $mock->handle(true));
    }

    public function testGetVersionChecksRegularExpression()
    {
        $lookup = new Lookup(['version' => 'foobarbaz']);
        $lookup->setVersionMap([
            '/bar/' => 'monkey',
        ]);
        $this->assertSame(Lookup::DEFAULT_BRANCH, $lookup->getVersion());
    }

    public function testGetVersionExactRule()
    {
        $lookup = new Lookup(['version' => '5']);
        $lookup->setVersionMap([
            '5' => '5',
        ]);
        $this->assertSame('5', $lookup->getVersion());
    }

    public function testGetVersionDefault()
    {
        $lookup = new Lookup(['version' => 'unknown']);
        $lookup->setVersionMap([
            '5' => '5.x',
            '4' => '4.x',
            '3' => '3.x',
        ]);

        $this->assertSame(Lookup::DEFAULT_BRANCH, $lookup->getVersion());
    }

    public static function provideParseGetParams(): array
    {
        return [
            'class-with-version' => [
                'q' => 'SilverStripe\ORM\DataObject',
                'version' => '4',
                'expected' => '/4/SilverStripe/ORM/DataObject.html',
            ],
            'class-without-version' => [
                'q' => 'SilverStripe\ORM\DataObject',
                'version' => null,
                'expected' => '/' . Lookup::DEFAULT_BRANCH . '/SilverStripe/ORM/DataObject.html',
            ],
            'class-and-method-with-version' => [
                'q' => 'SilverStripe\ORM\DataObject::write()',
                'version' => '4',
                'expected' => '/4/SilverStripe/ORM/DataObject.html#method_write',
            ],
            'class-and-method-without-version' => [
                'q' => 'SilverStripe\ORM\DataObject::write()',
                'version' => null,
                'expected' => '/' . Lookup::DEFAULT_BRANCH . '/SilverStripe/ORM/DataObject.html#method_write',
            ],
            'class-and-property-with-version' => [
                'q' => 'SilverStripe\ORM\DataObject->db',
                'version' => '4',
                'expected' => '/4/SilverStripe/ORM/DataObject.html#property_db',
            ],
            'class-and-property-without-version' => [
                'q' => 'SilverStripe\ORM\DataObject->db',
                'version' => null,
                'expected' => '/' . Lookup::DEFAULT_BRANCH . '/SilverStripe/ORM/DataObject.html#property_db',
            ],
            'no-class-with-version' => [
                'q' => null,
                'version' => '4',
                'expected' => '/',
            ],
            'no-class-without-version' => [
                'q' => null,
                'version' => null,
                'expected' => '/',
            ],
            'directory-traversal-protection' => [
                'q' => '\..\..\.htaccess::write()',
                'version' => null,
                'expected' => '/',
            ],
        ];
    }

    /**
     * @dataProvider provideParseGetParams
     */
    public function testParseGetParams(?string $q, ?string $version, string $expected): void
    {
        $args = [];
        if ($q) {
            $args['q'] = $q;
        }
        if ($version) {
            $args['version'] = $version;
        }
        $lookup = new class($args) extends Lookup {
            public function getURL(): string
            {
                return parent::getURL();
            }
            protected function staticFileExists(string $searchPath): bool
            {
                return true;
            }
        };
        $actual = $lookup->getURL();
        $this->assertSame($expected, $actual);
    }
}
