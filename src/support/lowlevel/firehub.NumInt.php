<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2023 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @package FireHub\Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\LowLevel;

use Throwable;

use function intdiv;

/**
 * ### Integer number low level class
 *
 * An int is a number of the set Z = {..., -2, -1, 0, 1, 2, ...}.
 * Int can be specified in decimal (base 10), hexadecimal (base 16), octal (base 8) or binary (base 2) notation.
 * The negation operator can be used to denote a negative int.
 *
 * @since 1.0.0
 */
final class NumInt extends Num {

    /**
     * ### Integer division
     * @since 1.0.0
     *
     * @param int $dividend <p>
     * Number to be divided.
     * </p>
     * @param int $divisor <p>
     * Number which divides the $dividend.
     * </p>
     *
     * @return int|false The integer quotient of the division of $dividend by $divisor, false if division failed.
     */
    public static function divide (int $dividend, int $divisor):int|false {

        try {

            return intdiv($dividend, $divisor);

        } catch (Throwable) {

            return false;

        }

    }

}