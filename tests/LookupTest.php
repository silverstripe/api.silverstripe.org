<?php

namespace SilverStripe\ApiDocs\Test;

use PHPUnit\Framework\TestCase;
use SilverStripe\ApiDocs\Lookup;

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
        $this->assertSame('foomonkeybaz', $lookup->getVersion());
    }

    public function testGetVersionExactRule()
    {
        $lookup = new Lookup(['version' => 'master']);
        $lookup->setVersionMap([
            'master' => '5.x',
        ]);
        $this->assertSame('5.x', $lookup->getVersion());
    }

    public function testGetVersionDefault()
    {
        $lookup = new Lookup(['version' => 'unknown']);
        $lookup->setVersionMap([
            'master' => '5.x',
            '4' => '4.x',
            '3' => '3.x',
        ]);

        $this->assertSame('unknown', $lookup->getVersion());
    }
}
