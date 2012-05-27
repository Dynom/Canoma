<?php

namespace Canoma;

/**
 * @author Mark van der Velden <mark@dynom.nl>
 */
interface HashAdapterInterface
{

    /**
     * Convert the argument (a string) to a hexadecimal value, using a adapter-specific algorithm. The return value
     * should be a string.
     *
     * @abstract
     * @param string $string
     * @return string
     */
    public function hash($string);
}
