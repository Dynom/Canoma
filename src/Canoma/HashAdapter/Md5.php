<?php

namespace Canoma\HashAdapter;

use \Canoma\HashAdapterInterface;

/**
 * @author Mark van der Velden <mark@dynom.nl>
 */
class Md5 implements HashAdapterInterface
{
    /**
     * Convert the argument (a string) to a hexadecimal value, using the md5 algorithm.
     *
     * @param string $string
     *
     * @return string
     */
    public function hash($string)
    {
        return hash(
            'md5',
            $string
        );
    }
}
