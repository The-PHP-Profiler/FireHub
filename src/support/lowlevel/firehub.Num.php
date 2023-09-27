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

use FireHub\Core\Support\Enums\Number\Round;

use function abs;
use function ceil;
use function floor;
use function max;
use function min;
use function number_format;
use function pow;
use function round;

/**
 * ### Number low level class
 *
 * Class contains methods that are used on all number types.
 * @since 1.0.0
 */
abstract class Num {

    /**
     * ### Absolute value
     * @since 1.0.0
     *
     * @param int|float $number <p>
     * The numeric value to process.
     * </p>
     *
     * @return ($number is int ? int : float) The absolute value of number.
     */
    final public static function absolute (int|float $number):int|float {

        return abs($number);

    }

    /**
     * ### Round fractions up
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\NumFloat::round() To round a float.
     *
     * @param int|float $number <p>
     * The value to round up.
     * </p>
     *
     * @return int Rounded number up to the next highest integer.
     */
    final public static function ceil (int|float $number):int {

        return self::round(ceil($number));

    }

    /**
     * ### Round fractions down
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\NumFloat::round() To round a float.
     *
     * @param int|float $number <p>
     * The value to round down.
     * </p>
     *
     * @return int Rounded number up to the next lowest integer.
     */
    final public static function floor (int|float $number):int {

        return self::round(floor($number));

    }

    /**
     * ### Rounds a float
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\Number\Round::HALF_UP Round number enum.
     * @uses \FireHub\Core\Support\Enums\Number\Round::HALF_DOWN Round number enum.
     * @uses \FireHub\Core\Support\Enums\Number\Round::HALF_EVEN Round number enum.
     * @uses \FireHub\Core\Support\Enums\Number\Round::HALF_ODD Round number enum.
     *
     * @param int|float $number <p>
     * The value to round.
     * </p>
     * @param int $precision [optional] <p>
     * Number of decimal digits to round to.
     * </p>
     * @param \FireHub\Core\Support\Enums\Number\Round $round [optional] <p>
     * Specify the mode in which rounding occurs.
     * </p>
     *
     * @return ($precision is positive-int ? float : int) Rounded number float.
     */
    final public static function round (int|float $number, int $precision = 0, Round $round = Round::HALF_UP):int|float {

        $round = round($number, $precision, match ($round) {
            Round::HALF_UP => 1,
            Round::HALF_DOWN => 2,
            Round::HALF_EVEN => 3,
            Round::HALF_ODD => 4,
        });

        return $precision > 0 ? $round : (int)$round;

    }

    /**
     * ### Find highest value
     * @since 1.0.0
     *
     * @template TInt of int|float
     *
     * @param TInt $value <p>
     * Any comparable value.
     * </p>
     * @param TInt ...$values <p>
     * Any comparable values.
     * </p>
     *
     * @return TInt Value considered "highest" according to standard comparisons.
     */
    final public static function max (int|float $value, int|float ...$values):int|float {

        return max([$value, ...$values]);

    }

    /**
     * ### Find lowest value
     * @since 1.0.0
     *
     * @template TInt of int|float
     *
     * @param TInt $value <p>
     * Any comparable value.
     * </p>
     * @param TInt ...$values <p>
     * Any comparable values.
     * </p>
     *
     * @return TInt Value considered "lowest" according to standard comparisons.
     */
    final public static function min (int|float $value, int|float ...$values):int|float {

        return min([$value, ...$values]);

    }

    /**
     * ### Exponential expression
     * @since 1.0.0
     *
     * @param int|float $base <p>
     * The base to use.
     * </p>
     * @param int|float $exponent <p>
     * The exponent.
     * </p>
     *
     * @return int|float Base raised to the power of exponent. If both arguments are non-negative integers and the result can be represented as an integer, the result will be returned with int type, otherwise it will be returned as a float.
     */
    final public static function power (int|float $base, int|float $exponent):int|float {

        return pow($base, $exponent);

    }

    /**
     * ### Format a number with grouped thousands
     * @since 1.0.0
     *
     * @param int|float $number <p>
     * The number being formatted.
     * </p>
     * @param non-negative-int $decimals <p>
     * Sets the number of decimal digits. If 0, the decimal_separator is omitted from the return value.
     * </p>
     * @param string $decimal_separator <p>
     * Sets the separator for the decimal point.
     * </p>
     * @param string $thousands_separator <p>
     * Sets the thousands separator.
     * </p>
     *
     * @return string A formatted version of number.
     */
    final public static function format (int|float $number, int $decimals, string $decimal_separator = '.', string $thousands_separator = ','):string {

        return number_format(
            $number,
            $decimals,
            $decimal_separator,
            $thousands_separator
        );

    }

}