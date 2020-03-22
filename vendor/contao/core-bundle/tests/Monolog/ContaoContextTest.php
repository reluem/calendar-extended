<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\Tests\Monolog;

use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\CoreBundle\Tests\TestCase;

/**
 * Tests the ContaoContextTest class.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ContaoContextTest extends TestCase
{
    /**
     * Tests the setter and getter methods.
     */
    public function testSupportsReadingAndWritingValues()
    {
        $context = new ContaoContext('foo');

        $this->assertSame('foo', $context->getFunc());
        $this->assertNull($context->getAction());
        $this->assertNull($context->getUsername());
        $this->assertNull($context->getIp());
        $this->assertNull($context->getBrowser());
        $this->assertNull($context->getSource());

        $context->setAction('action');
        $context->setUsername('username');
        $context->setIp('1.2.3.4');
        $context->setBrowser('Mozilla');
        $context->setSource('Foo::bar()');

        $this->assertSame(
            json_encode([
                'func' => 'foo',
                'action' => 'action',
                'username' => 'username',
                'ip' => '1.2.3.4',
                'browser' => 'Mozilla',
            ]),
            (string) $context
        );
    }

    /**
     * Tests passing an empty function name.
     */
    public function testFailsIfTheFunctionNameIsEmpty()
    {
        $this->expectException('InvalidArgumentException');

        new ContaoContext('');
    }
}
