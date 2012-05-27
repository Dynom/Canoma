<?php

namespace Canoma\HashAdapter;

use \Canoma\HashAdapterInterface;
use \Canoma\HashAdapterAbstract;

/**
 * @author Mark van der Velden <mark@dynom.nl>
 */
class Md5 extends HashAdapterAbstract implements HashAdapterInterface
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


    /**
     * Overriding default compare behavior else things go boom. Depending on BCMath functionality.
     * @see http://www.php.net/BCMath
     *
     * @param int $left
     * @param int $right
     * @return int
     */
    public function compare($left, $right)
    {
        return bccomp(
            sprintf("%f", hexdec($left)),
            sprintf("%f", hexdec($right)),
            0
        );
    }
}
