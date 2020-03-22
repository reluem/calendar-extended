<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\Referer;

use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;

/**
 * Generates an 8 character referer token.
 *
 * @author Yanick Witschi <https://github.com/toflar>
 */
class TokenGenerator extends UriSafeTokenGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generateToken()
    {
        return substr(parent::generateToken(), 0, 8);
    }
}
