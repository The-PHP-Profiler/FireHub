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

use function acos;
use function acosh;
use function asin;
use function asinh;
use function atan;
use function atan2;
use function cos;
use function cosh;
use function deg2rad;
use function exp;
use function fdiv;
use function fmod;
use function hypot;
use function is_finite;
use function is_infinite;
use function is_nan;
use function rad2deg;
use function sin;
use function sinh;
use function sqrt;
use function tan;
use function tanh;

/**
 * ### Float number low level class
 *
 * Floating point numbers (also known as "floats", "doubles", or "real numbers") can be specified using any of the
 * following syntax's: 1.234, 1.2e3, 7E-10, 1_234.567.
 * @since 1.0.0
 */
final class NumFloat extends Num {

    /**
     * ### Finds whether a value is a legal finite number
     * @since 1.0.0
     *
     * @param float $number <p>
     * The value to check.
     * </p>
     *
     * @return bool True if number is a legal finite number within the allowed range for a PHP float on this platform,
     * false otherwise.
     */
    public static function isFinite (float $number):bool {

        return is_finite($number);

    }

    /**
     * ### Finds whether a value is infinite
     * @since 1.0.0
     *
     * @param float $number <p>
     * The value to check.
     * </p>
     *
     * @return bool True if number is infinite, false otherwise.
     */
    public static function infinite (float $number):bool {

        return is_infinite($number);

    }

    /**
     * ### Finds whether a value is not a number
     * @since 1.0.0
     *
     * @param float $number <p>
     * Value to check.
     * </p>
     *
     * @return bool True if number is 'not a number', false otherwise.
     */
    public static function nan (float $number):bool {

        return is_nan($number);

    }

    /**
     * ### Divides two numbers, according to IEEE 754
     *
     * Returns the floating point result of dividing the num1 by the num2.
     * If the num2 is zero, then one of INF, -INF, or NAN will be returned.
     * @since 1.0.0
     *
     * @param float $dividend <p>
     * Number to be divided.
     * </p>
     * @param float $divisor <p>
     * Number which divides the $dividend.
     * </p>
     *
     * @return float The floating point result of the division of $dividend by $divisor.
     */
    public static function divide (float $dividend, float $divisor):float {

        return fdiv($dividend, $divisor);

    }

    /**
     * ### Get the floating point remainder (modulo) of the division of the arguments
     * @since 1.0.0
     *
     * @param float $dividend <p>
     * The dividend.
     * </p>
     * @param float $divisor <p>
     * The divisor.
     * </p>
     *
     * @return float The floating point remainder (modulo) of the division of the arguments.
     */
    public static function remainder (float $dividend, float $divisor):float {

        return fmod($dividend, $divisor);

    }

    /**
     * ### Cosine
     * @since 1.0.0
     *
     * @param float $number <p>
     * An angle in radians.
     * </p>
     *
     * @return float The cosine of angle.
     */
    public static function cosine (float $number):float {

        return cos($number);

    }

    /**
     * ### Arc cosine
     * @since 1.0.0
     *
     * @param float $number <p>
     * The argument to process.
     * </p>
     *
     * @return float The arc cosine of number in radians.
     */
    public static function cosineArc (float $number):float {

        return acos($number);

    }

    /**
     * ### Hyperbolic cosine
     * @since 1.0.0
     *
     * @param float $number <p>
     * The argument to process.
     * </p>
     *
     * @return float Hyperbolic cosine of number, defined as (exp($number) + exp(-$number))/2.
     */
    public static function cosineHyperbolic (float $number):float {

        return cosh($number);

    }

    /**
     * ### Inverse hyperbolic cosine
     * @since 1.0.0
     *
     * @param float $number <p>
     * The argument to process.
     * </p>
     *
     * @return float Inverse hyperbolic cosine of number.
     */
    public static function cosineInverseHyperbolic (float $number):float {

        return acosh($number);

    }

    /**
     * ### Sine
     * @since 1.0.0
     *
     * @param float $number <p>
     * The argument to process.
     * </p>
     *
     * @return float Sine of number.
     */
    public static function sine (float $number):float {

        return sin($number);

    }

    /**
     * ### Arc sine
     * @since 1.0.0
     *
     * @param float $number <p>
     * The argument to process.
     * </p>
     *
     * @return float The arc sine of number in radians.
     */
    public static function sineArc (float $number):float {

        return asin($number);

    }

    /**
     * ### Hyperbolic sine
     * @since 1.0.0
     *
     * @param float $number <p>
     * The argument to process.
     * </p>
     *
     * @return float Hyperbolic sine of number.
     */
    public static function sineHyperbolic (float $number):float {

        return sinh($number);

    }

    /**
     * ### Inverse hyperbolic tangent
     * @since 1.0.0
     *
     * @param float $number <p>
     * The argument to process.
     * </p>
     *
     * @return float The inverse hyperbolic sine of number.
     */
    public static function sineHyperbolicInverse (float $number):float {

        return asinh($number);

    }

    /**
     * ### Tangent
     * @since 1.0.0
     *
     * @param float $number <p>
     * The argument to process in radians.
     * </p>
     *
     * @return float Tangent of number.
     */
    public static function tangent (float $number):float {

        return tan($number);

    }

    /**
     * ### Arc tangent
     * @since 1.0.0
     *
     * @param float $number <p>
     * The argument to process.
     * </p>
     *
     * @return float Arc tangent of num in radians.
     */
    public static function tangentArc (float $number):float {

        return atan($number);

    }

    /**
     * ### Arc tangent of two variables
     *
     * This method calculates the arc tangent of the two variables x and y.
     * It is similar to calculating the arc tangent of y / x, except that the signs of both arguments are used to determine the quadrant of the result.
     * @since 1.0.0
     *
     * @param float $x <p>
     * Divisor parameter.
     * </p>
     * @param float $y <p>
     * Dividend parameter.
     * </p>
     *
     * @return float Arc tangent of y/x in radians.
     */
    public static function tangentArc2 (float $x, float $y):float {

        return atan2($y, $x);

    }

    /**
     * ### Hyperbolic Tangent
     * @since 1.0.0
     *
     * @param float $number <p>
     * The argument to process.
     * </p>
     *
     * @return float Hyperbolic tangent of number.
     */
    public static function tangentHyperbolic (float $number):float {

        return tanh($number);

    }

    /**
     * ### Converts the number in degrees to the radian equivalent
     * @since 1.0.0
     *
     * @param float $number <p>
     * Angular value in degrees.
     * </p>
     *
     * @return float Radian equivalent of number.
     */
    public static function degreesToRadian (float $number):float {

        return deg2rad($number);

    }

    /**
     * ### Converts the radian number to the equivalent number in degrees
     * @since 1.0.0
     *
     * @param float $number <p>
     * Radian value.
     * </p>
     *
     * @return float Equivalent of number in degrees.
     */
    public static function radianToDegrees (float $number):float {

        return rad2deg($number);

    }

    /**
     * ### Calculates the exponent of e
     * @since 1.0.0
     *
     * @param float $number <p>
     * The argument to process.
     * </p>
     *
     * @return float 'e' raised to the power of number.
     *
     * @note 'e' is the base of the natural system of logarithms, or approximately 2.718282.
     */
    public static function exponent (float $number):float {

        return exp($number);

    }

    /**
     * ### Calculate the length of the hypotenuse of a right-angle triangle
     * @since 1.0.0
     *
     * @param float $x <p>
     * Length of first side.
     * </p>
     * @param float $y <p>
     * Length of second side.
     * </p>
     *
     * @return float Calculated length of the hypotenuse.
     */
    public static function hypotenuseLength (float $x, float $y):float {

        return hypot($x, $y);

    }

    /**
     * ### Square root
     * @since 1.0.0
     *
     * @param float $number  <p>
     * The argument to process.
     * </p>
     *
     * @return float The square root of num or the special value NAN for negative numbers.
     */
    public static function squareRoot (float $number):float {

        return sqrt($number);

    }

}