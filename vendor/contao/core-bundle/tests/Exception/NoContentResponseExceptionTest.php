<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\Tests\Exception;

use Contao\CoreBundle\Exception\NoContentResponseException;
use PHPUnit\Framework\TestCase;

/**
 * Tests the NoContentResponseException class.
 *
 * @author Christian Schiffler <https://github.com/discordier>
 */
class NoContentResponseExceptionTest extends TestCase
{
    /**
     * Tests the getResponse() method.
     */
    public function testSetsTheResponseStatusCode()
    {
        $exception = new NoContentResponseException();

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $exception->getResponse());
        $this->assertSame(204, $exception->getResponse()->getStatusCode());
        $this->assertSame('', $exception->getResponse()->getContent());
    }
}
