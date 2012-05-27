<?php

namespace Canoma\HashAdapter;

use \Canoma\HashAdapterInterface;

/**
 * @author Mark van der Velden <mark@dynom.nl>
 */
class Crc32 implements HashAdapterInterface
{
    /**
     * Convert the argument (a string) to a hexadecimal value, using the crc32 algorithm.
     *
     * @param string $string
     *
     * @return string
     */
    public function hash($string)
    {
        return hash(
            'crc32',
            $string
        );
    }
}
