<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\Tests\HttpKernel\Bundle;

use Contao\CoreBundle\HttpKernel\Bundle\ContaoModuleBundle;
use Contao\CoreBundle\Tests\TestCase;

/**
 * Tests the ContaoModuleBundle class.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ContaoModuleBundleTest extends TestCase
{
    /**
     * @var ContaoModuleBundle
     */
    protected $bundle;

    /**
     * Creates a new Contao module bundle.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->bundle = new ContaoModuleBundle('foobar', $this->getRootDir().'/app');
    }

    /**
     * Tests returning the module path.
     */
    public function testReturnsTheModulePath()
    {
        $this->assertSame(
            $this->getRootDir().'/system/modules/foobar',
            $this->bundle->getPath()
        );
    }

    /**
     * Tests that an exception is thrown if the module folder does not exist.
     */
    public function testFailsIfTheModuleFolderDoesNotExist()
    {
        $this->expectException('LogicException');

        $this->bundle = new ContaoModuleBundle('invalid', $this->getRootDir().'/app');
    }
}
