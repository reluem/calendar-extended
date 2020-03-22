<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\Config\Dumper;

/**
 * Interface for cache dumpers.
 *
 * @author Andreas Schempp <https://github.com/aschempp>
 */
interface DumperInterface
{
    /**
     * Dumps files into a given cache file.
     *
     * @param array|string $files
     * @param string       $cacheFile
     * @param array        $options
     */
    public function dump($files, $cacheFile, array $options = []);
}
