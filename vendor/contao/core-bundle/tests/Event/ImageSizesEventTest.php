<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\Tests\Event;

use Contao\BackendUser;
use Contao\CoreBundle\Event\ImageSizesEvent;
use PHPUnit\Framework\TestCase;

/**
 * Tests the ImageSizesEvent class.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ImageSizesEventTest extends TestCase
{
    /**
     * Tests the image sizes setter and getter.
     */
    public function testSupportsReadingAndWritingImageSizes()
    {
        $event = new ImageSizesEvent([1]);

        $this->assertSame([1], $event->getImageSizes());

        $event->setImageSizes([1, 2]);

        $this->assertSame([1, 2], $event->getImageSizes());
    }

    /**
     * Tests the getUser() method.
     */
    public function testSupportsReadingTheUserObject()
    {
        $user = $this->createMock(BackendUser::class);
        $event = new ImageSizesEvent([1], $user);

        $this->assertSame($user, $event->getUser());
    }
}
