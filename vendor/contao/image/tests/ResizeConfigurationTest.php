<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\Image\Tests;

use Contao\Image\ResizeConfiguration;
use PHPUnit\Framework\TestCase;

class ResizeConfigurationTest extends TestCase
{
    public function testInstantiation()
    {
        $resizeConfig = new ResizeConfiguration();

        $this->assertInstanceOf('Contao\Image\ResizeConfiguration', $resizeConfig);
        $this->assertInstanceOf('Contao\Image\ResizeConfigurationInterface', $resizeConfig);
    }

    public function testIsEmpty()
    {
        $config = new ResizeConfiguration();

        $this->assertTrue($config->isEmpty());

        $config->setMode(ResizeConfiguration::MODE_CROP);

        $this->assertTrue($config->isEmpty());

        $config->setMode(ResizeConfiguration::MODE_PROPORTIONAL);

        $this->assertTrue($config->isEmpty());

        $config->setMode(ResizeConfiguration::MODE_BOX);

        $this->assertTrue($config->isEmpty());

        $config->setWidth(100);

        $this->assertFalse($config->isEmpty());

        $config->setWidth(0)->setHeight(100);

        $this->assertFalse($config->isEmpty());

        $config->setHeight(0)->setZoomLevel(100);

        $this->assertFalse($config->isEmpty());

        $config->setWidth(100)->setHeight(100)->setZoomLevel(100);

        $this->assertFalse($config->isEmpty());

        $config->setWidth(0)->setHeight(0)->setZoomLevel(0);

        $this->assertTrue($config->isEmpty());
    }

    public function testSetWidth()
    {
        $config = new ResizeConfiguration();

        $this->assertSame(0, $config->getWidth());
        $this->assertSame($config, $config->setWidth(100.0));
        $this->assertSame(100, $config->getWidth());
        $this->assertInternalType('int', $config->getWidth());

        $this->expectException('InvalidArgumentException');

        $config->setWidth(-1);
    }

    public function testSetHeight()
    {
        $config = new ResizeConfiguration();

        $this->assertSame(0, $config->getHeight());
        $this->assertSame($config, $config->setHeight(100.0));
        $this->assertSame(100, $config->getHeight());
        $this->assertInternalType('int', $config->getHeight());

        $this->expectException('InvalidArgumentException');

        $config->setHeight(-1);
    }

    public function testSetMode()
    {
        $config = new ResizeConfiguration();

        $this->assertSame(ResizeConfiguration::MODE_CROP, $config->getMode());
        $this->assertSame($config, $config->setMode(ResizeConfiguration::MODE_BOX));
        $this->assertSame(ResizeConfiguration::MODE_BOX, $config->getMode());

        $this->expectException('InvalidArgumentException');

        $config->setMode('invalid');
    }

    public function testSetZoomLevel()
    {
        $config = new ResizeConfiguration();

        $this->assertSame(0, $config->getZoomLevel());
        $this->assertSame($config, $config->setZoomLevel(100.0));
        $this->assertSame(100, $config->getZoomLevel());
        $this->assertInternalType('int', $config->getZoomLevel());

        $this->expectException('InvalidArgumentException');

        $config->setZoomLevel(-1);
    }

    public function testSetZoomLevelTooHigh()
    {
        $config = new ResizeConfiguration();

        $this->expectException('InvalidArgumentException');

        $config->setZoomLevel(101);
    }
}
