<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Allows to convert a preview URL.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class PreviewUrlConvertEvent extends Event
{
    /**
     * @var string
     */
    private $url;

    /**
     * Returns the URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the URL.
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
}
