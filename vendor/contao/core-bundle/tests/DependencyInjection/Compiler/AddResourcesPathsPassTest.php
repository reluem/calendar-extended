<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\Tests\DependencyInjection\Compiler;

use Contao\CoreBundle\DependencyInjection\Compiler\AddResourcesPathsPass;
use Contao\CoreBundle\HttpKernel\Bundle\ContaoModuleBundle;
use Contao\CoreBundle\Tests\TestCase;
use Contao\TestBundle\ContaoTestBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Tests the AddResourcesPathsPass class.
 *
 * @author Leo Feyer <http://github.com/leofeyer>
 */
class AddResourcesPathsPassTest extends TestCase
{
    /**
     * Tests adding the resources paths.
     */
    public function testAddsTheResourcesPaths()
    {
        $pass = new AddResourcesPathsPass();

        $container = new ContainerBuilder();
        $container->setParameter('kernel.project_dir', $this->getRootDir());

        $container->setParameter('kernel.bundles', [
            'FrameworkBundle' => FrameworkBundle::class,
            'ContaoTestBundle' => ContaoTestBundle::class,
            'foobar' => ContaoModuleBundle::class,
        ]);

        $pass->process($container);

        $this->assertTrue($container->hasParameter('contao.resources_paths'));

        $path = $this->getRootDir().'/vendor/contao/test-bundle';

        if ('\\' === \DIRECTORY_SEPARATOR) {
            $path = strtr($path, '/', '\\');
        }

        $this->assertSame(
            [
                $path.'/Resources/contao',
                $this->getRootDir().'/system/modules/foobar',
                $this->getRootDir().'/app/Resources/contao',
            ],
            $container->getParameter('contao.resources_paths')
        );
    }
}
