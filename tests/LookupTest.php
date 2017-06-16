<?php

namespace SilverStripe\ApiDocs\Test;

use SilverStripe\ApiDocs\Lookup;

class LookupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Basic injection and getter tests
     */
    public function testHandleArgs()
    {
        $input = array('foo' => 'bar', 'bar' => 'baz');
        $lookup = new Lookup($input);

        $this->assertSame('bar', $lookup->getArg('foo'));
        $this->assertNull($lookup->getArg('animal'));
        $this->assertSame($input, $lookup->getArgs());
    }

    /**
     * End to end tests
     *
     * @param array $input
     * @param string $expected
     * @dataProvider handleProvider
     */
    public function testHandle($input, $expected)
    {
        $lookup = new Lookup($input);
        $lookup->setServerName('mockserver');
        $lookup->setVersionMap(array('4' => 'master', '3' => '3.6'));

        if (empty($input['version']) || in_array($input['version'], array('master', '4'))) {
            $lookup->setUpgraderMap(array(
                'DataExtension' => 'SilverStripe\ORM\DataExtension',
                'SiteTree' => 'SilverStripe\CMS\Model\SiteTree',
                'Director' => 'SilverStripe\Control\Director'
            ));
        }

        $this->assertSame($expected, $lookup->handle(true));
    }

    /**
     * @return array[]
     */
    public function handleProvider()
    {
        return array(
            array(
                array('q' => 'DataExtension', 'version' => '4'),
                'http://mockserver/master/class-SilverStripe.ORM.DataExtension.html'
            ),
            array(
                array('q' => 'DataExtension'),
                'http://mockserver/master/class-SilverStripe.ORM.DataExtension.html'
            ),
            array(
                array('q' => 'SomeClass'),
                'http://mockserver/master/class-SomeClass.html'
            ),
            array(
                array('q' => 'SomeClass', 'version' => '3'),
                'http://mockserver/3.6/class-SomeClass.html'
            ),
            array(
                array('q' => 'SomeClass', 'version' => '3.4'),
                'http://mockserver/3.4/class-SomeClass.html'
            ),
            array(
                array('q' => '\Already\Fully\Qualified', 'version' => '3.4'),
                'http://mockserver/3.4/class-Already.Fully.Qualified.html'
            ),
            array(
                array('q' => 'Director', 'module' => 'framework'),
                'http://mockserver/master/class-SilverStripe.Control.Director.html'
            ),
            array(
                array('q' => 'SiteTree::write()', 'module' => 'cms'),
                'http://mockserver/master/class-SilverStripe.CMS.Model.SiteTree.html#_write'
            ),
            array(
                array('q' => 'SiteTree::$allowed_children', 'module' => 'cms'),
                'http://mockserver/master/class-SilverStripe.CMS.Model.SiteTree.html#$allowed_children'
            ),
        );
    }

    /**
     * Ensure that when major versions are provided, they are converted to the appropriate branch name to provide
     * relevant API docs
     *
     * @param string $version
     * @param string $expected
     * @dataProvider versionProvider
     */
    public function testGetVersions($version, $expected)
    {
        $lookup = new Lookup(array('version' => $version));
        $lookup->setVersionMap(array('4' => 'master', '3' => '3.6'));
        $this->assertSame($expected, $lookup->getVersion());
    }

    /**
     * @return array[]
     */
    public function versionProvider()
    {
        return array(
            array('master', 'master'),
            array('4', 'master'),
            array('3', '3.6'),
            array('3.5', '3.5'),
            array('3.4', '3.4'),
        );
    }

    /**
     * Ensure that class names, whether namespaced or not, are handled correctly
     *
     * @param string $class
     * @param string $expected
     * @dataProvider classNameProvider
     */
    public function testSanitiseClassNames($class, $expected)
    {
        $this->assertSame($expected, (new Lookup)->sanitiseNamespaces($class));
    }

    /**
     * @return array[]
     */
    public function classNameProvider()
    {
        return array(
            array('Director', 'Director'),
            array('SilverStripe\ORM\DataExtension', 'SilverStripe.ORM.DataExtension'),
            array('\SilverStripe\ORM\DataExtension', 'SilverStripe.ORM.DataExtension'),
            array('\Convert', 'Convert')
        );
    }

    /**
     * Ensure that the namespace for a class can be acquired from a map of upgrader class names
     */
    public function testGetNamespacesForClass()
    {
        $lookup = new Lookup;
        $lookup->setUpgraderMap(array(
            'DataExtension' => 'SilverStripe\ORM\DataExtension',
            'SiteTree' => 'SilverStripe\CMS\Model\SiteTree',
            'Director' => 'SilverStripe\Control\Director'
        ));

        $this->assertSame('SilverStripe\ORM\DataExtension', $lookup->getWithNamespace('DataExtension', false));
        $this->assertSame('SilverStripe\CMS\Model\SiteTree', $lookup->getWithNamespace('SiteTree', false));
        $this->assertSame('SilverStripe\Control\Director', $lookup->getWithNamespace('Director', false));
        $this->assertSame('SilverStripe.Control.Director', $lookup->getWithNamespace('Director', true));
    }
}
