<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package
 *
 * This file contains all number functions.
 * @since 1.0.0
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2023 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @package FireHub\Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\Helpers\Number;

use FireHub\Core\Support\Number;

/**
 * ### Convert Number high-level class, int, or float to integer
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\Number As parameter.
 * @uses \FireHub\Core\Support\Number::from() To create a new number from raw number.
 * @uses \FireHub\Core\Support\Number::asInt() To get number as integer.
 *
 * @example
 * ```php
 * use FireHub\Core\Support\Number;
 * use function FireHub\Core\Support\Helpers\Number\number_to_int;
 *
 * $number = Number::from(12.5);
 *
 * number_to_int($number);
 *
 * // 13
 * ```
 * @example With typecast.
 * ```php
 * use FireHub\Core\Support\Number;
 * use function FireHub\Core\Support\Helpers\Number\number_to_int;
 *
 * $number = Number::from(12.5);
 *
 * number_to_int($number, true);
 *
 * // 12
 * ```
 *
 * @param \FireHub\Core\Support\Number|int|float $number <p>
 * Number to convert.
 * </p>
 * @param bool $typecast [optional] <p>
 * If true, converting float to integer will remove all float digits, otherwise, if false, float will be converted
 * to the nearest integer.
 * </p>
 *
 * @return int Integer from number.
 *
 * @api
 */
function number_to_int (Number|int|float $number, bool $typecast = false):int {

    return $number instanceOf Number
        ? $number->asInt($typecast)
        : ($typecast
            ? (int)$number
            : Number::from($number)->asInt()
        );

}

/**
 * ### Convert Number high-level class, int, or float to float
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\Number As parameter.
 * @uses \FireHub\Core\Support\Number::asFloat() To get number as float.
 *
 * @example
 * ```php
 * use FireHub\Core\Support\Number;
 * use function FireHub\Core\Support\Helpers\Number\number_to_float;
 *
 * $number = Number::from(12.5);
 *
 * number_to_float($number);
 *
 * // 12.5
 * ```
 *
 * @param \FireHub\Core\Support\Number|int|float $number <p>
 * Number to convert.
 * </p>
 *
 * @return float Float from number.
 *
 * @api
 */
function number_to_float (Number|int|float $number):float {

    return $number instanceOf Number ? $number->asFloat() : (float)$number;

}

/**
 * ### Convert Number high-level class, int, or float to Number high-level class
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\Number As parameter.
 * @uses \FireHub\Core\Support\Number::from() To create a new number from raw number.
 *
 * @example
 * ```php
 * use function FireHub\Core\Support\Helpers\Number\mixed_to_number;
 *
 * mixed_to_number(12.5);
 *
 * // 12.5
 * ```
 *
 * @param \FireHub\Core\Support\Number|int|float $number <p>
 * Number to convert.
 * </p>
 *
 * @return \FireHub\Core\Support\Number Number high-level class from number.
 *
 * @api
 */
function mixed_to_number (Number|int|float $number):Number {

    return $number instanceof Number ? $number : Number::from($number);

}