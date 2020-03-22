<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\Tests\DependencyInjection\Compiler;

use Contao\CoreBundle\DependencyInjection\Compiler\AddImagineClassPass;
use Contao\CoreBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Tests the AddImagineClassPass class.
 *
 * @author Leo Feyer <http://github.com/leofeyer>
 */
class AddImagineClassPassTest extends TestCase
{
    /**
     * Tests adding the Imagine class.
     */
    public function testAddsTheImagineClass()
    {
        $container = new ContainerBuilder();
        $container->setDefinition('contao.image.imagine', new Definition());

        $pass = new AddImagineClassPass();
        $pass->process($container);

        $this->assertContains(
            $container->getDefinition('contao.image.imagine')->getClass(),
            [
                'Imagine\Gd\Imagine',
                'Imagine\Gmagick\Imagine',
                'Imagine\Imagick\Imagine',
            ]
        );
    }
}
