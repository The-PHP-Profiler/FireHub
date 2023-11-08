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

namespace FireHub\Core\Support;

use FireHub\Core\Base\ {
    Base, Master
};
use FireHub\Core\Support\Contracts\Stringable;
use FireHub\Core\Support\Enums\ {
    Data\Type, Number\Round
};
use FireHub\Core\Support\LowLevel\ {
    Data, NumFloat
};
use DivisionByZeroError;

use function FireHub\Core\Support\Helpers\Number\number_to_float;

/**
 *  ### Number high-level class
 *
 * Class allows you to manipulate numbers in various ways.
 * @since 1.0.0
 *
 * @api
 */
final class Number implements Master, Stringable {

    /**
     * ### FireHub base class trait
     * @since 1.0.0
     */
    use Base;

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @param float $number <p>
     * Number to use.
     * </p>
     *
     * @return void
     */
    protected function __construct (
        protected float $number
    ) {}

    /**
     * ### Create a new number from raw number
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(10);
     *
     * // 10
     * ```
     *
     * @param int|float $number <p>
     * Number to use.
     * </p>
     *
     * @return self New number.
     */
    public static function from (int|float $number):self {

        return new self($number);

    }

    /**
     * ### Check if number is positive
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(12)->isPositive();
     *
     * // true
     * ```
     *
     * @return bool True if the number is positive, false otherwise.
     */
    public function isPositive ():bool {

        return $this->number > 0;

    }

    /**
     * ### Check if number is negative
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(12)->isNegative();
     *
     * // false
     * ```
     *
     * @return bool True if the number is negative, false otherwise.
     */
    public function isNegative ():bool {

        return $this->number < 0;

    }

    /**
     * ### Finds whether a value is a legal finite number
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\NumFloat::isFinite() To find whether a value is a legal finite number.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(12)->isFinite(1e308 * 2);
     *
     * // false
     * ```
     *
     * @return bool True if the number is a legal finite number, false otherwise.
     */
    public function isFinite ():bool {

        return NumFloat::isFinite($this->number);

    }

    /**
     * ### Finds whether a value is infinite
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\NumFloat::isInfinite() To find whether a value is infinite.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(12)->isInfinite(1e308 * 2);
     *
     * // true
     * ```
     *
     * @return bool True if the number is infinite, false otherwise.
     */
    public function isInfinite ():bool {

        return NumFloat::isInfinite($this->number);

    }

    /**
     * ### Finds whether a value is not a number
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\NumFloat::isInfinite() To find whether a value is not a number.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(12)->isNan(1sqrt(-1));
     *
     * // true
     * ```
     *
     * @return bool True if the number is not a number, false otherwise.
     */
    public function isNan ():bool {

        return NumFloat::isNan($this->number);

    }

    /**
     * ### Make sure the number is positive
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Number::isNegative() To check if the number is negative.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(-12)->toPositive();
     *
     * // 12
     * ```
     *
     * @return $this This number.
     */
    public function toPositive ():self {

        $this->number = $this->isNegative() ? -$this->number : $this->number;

        return $this;

    }

    /**
     * ### Make sure the number is negative
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Number::isPositive() To check if the number is positive.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(12)->toNegative();
     *
     * // -12
     * ```
     *
     * @return $this This number.
     */
    public function toNegative ():self {

        $this->number = $this->isPositive() ? -$this->number : $this->number;

        return $this;

    }

    /**
     * ### Absolute value
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\NumFloat::absolute() To get absolute value.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(-12)->absolute();
     *
     * // 12
     * ```
     *
     * @return $this This number.
     */
    public function absolute ():self {

        $this->number = NumFloat::absolute($this->number);

        return $this;

    }

    /**
     * ### Round fractions up
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\NumFloat::ceil() To round fractions up.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(12.5)->ceil();
     *
     * // 13
     * ```
     *
     * @return $this This number.
     */
    public function ceil ():self {

        $this->number = NumFloat::ceil($this->number);

        return $this;

    }

    /**
     * ### Round fractions down
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\NumFloat::floor() To round fractions down.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(12.5)->floor();
     *
     * // 12
     * ```
     *
     * @return $this This number.
     */
    public function floor ():self {

        $this->number = NumFloat::floor($this->number);

        return $this;

    }

    /**
     * ### Rounds number
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\NumFloat::round() To round a float.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(12.5)->round();
     *
     * // 13
     * ```
     * @example Using different round mode.
     * ```php
     * use FireHub\Core\Support\Number;
     * use FireHub\Core\Support\Enums\Number\Round;
     *
     * $number = Number::from(12.5)->round(round: Round::HALF_DOWN);
     *
     * // 12
     * ```
     * @example Using different precision.
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(12.56)->round(1);
     *
     * // 12.6
     * ```
     *
     * @return $this This number.
     */
    public function round (int $precision = 0, Round $round = Round::HALF_UP):self {

        $this->number = NumFloat::round($this->number, $precision, $round);

        return $this;

    }

    /**
     * ### Add number to base
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Helpers\Number\number_to_float() To convert Number high-level class, int, or float to
     * float.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(10);
     *
     * $number->add(2);
     *
     * // 12
     * ```
     *
     * @param \FireHub\Core\Support\Number|int|float $number <p>
     * Number to use.
     * </p>
     *
     * @return $this This number.
     */
    public function add (self|int|float $number):self {

        $this->number += number_to_float($number);

        return $this;

    }

    /**
     * ### Subtract number from base
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Helpers\Number\number_to_float() To convert Number high-level class, int, or float to
     * float.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(10);
     *
     * $number->sub(2);
     *
     * // 8
     * ```
     * @param \FireHub\Core\Support\Number|int|float $number <p>
     * Number to use.
     * </p>
     *
     *
     * @return $this This number.
     */
    public function sub (self|int|float $number):self {

        $this->number -= number_to_float($number);

        return $this;

    }

    /**
     * ### Multiply number to base
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Helpers\Number\number_to_float() To convert Number high-level class, int, or float to
     * float.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(10);
     *
     * $number->multiply(2);
     *
     * // 20
     * ```
     *
     * @param \FireHub\Core\Support\Number|int|float $number <p>
     * Number to use.
     * </p>
     *
     * @return $this This number.
     */
    public function multiply (self|int|float $number):self {

        $this->number *= number_to_float($number);

        return $this;

    }

    /**
     * ### Divide number from base
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Helpers\Number\number_to_float() To convert Number high-level class, int, or float to
     * float.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(10);
     *
     * $number->divide(2);
     *
     * // 5
     * ```
     *
     * @param \FireHub\Core\Support\Number|non-zero-int|float $number <p>
     * Number to use.
     * </p>
     *
     * @throws DivisionByZeroError If trying to divide by zero.
     *
     * @return $this This number.
     */
    public function divide (self|int|float $number):self {

        $this->number /= number_to_float($number);

        return $this;

    }

    /**
     * ### Remainder of base when divided by number
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Helpers\Number\number_to_float() To convert Number high-level class, int, or float to
     * float.
     * @uses \FireHub\Core\Support\LowLevel\NumFloat::remainder() To get the floating point remainder (modulo) of
     * division of the arguments.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(10);
     *
     * $number->modulo(4);
     *
     * // 2
     * ```
     *
     * @param \FireHub\Core\Support\Number|int|float $number <p>
     * Number to use.
     * </p>
     *
     * @return $this This number.
     */
    public function modulo (self|int|float $number):self {

        $this->number = NumFloat::remainder(
            $this->number,
            number_to_float($number)
        );

        return $this;

    }

    /**
     * ### Base raised to the power of exponent
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Helpers\Number\number_to_float() To convert Number high-level class, int, or float to
     * float.
     * @uses \FireHub\Core\Support\LowLevel\NumFloat::power() As exponential expression.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(10);
     *
     * $number->power(2);
     *
     * // 100
     * ```
     *
     * @param \FireHub\Core\Support\Number|int|float $number <p>
     * Number to use.
     * </p>
     *
     * @return $this This number.
     */
    public function power (self|int|float $number):self {

        $this->number = NumFloat::power(
            $this->number,
            number_to_float($number)
        );

        return $this;

    }

    /**
     * ### Get number as float
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(12.5)->toFloat();
     *
     * // 12.5
     * ```
     *
     * @return float This number.
     */
    public function toFloat ():float {

        return $this->number;

    }

    /**
     * ### Get number as integer
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To set a data type.
     * @uses \FireHub\Core\Support\LowLevel\NumFloat::round() To round a float.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT As data type.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(12.5)->toInt();
     *
     * // 13
     * ```
     * @example With typecast.
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(12.5)->toInt(true);
     *
     * // 12
     * ```
     *
     * @param bool $typecast [optional] <p>
     * If true, converting float to integer will remove all float digits, otherwise, if false, float will be converted
     * to the nearest integer.
     * </p>
     *
     * @return int This number.
     *
     * @note The Number will be rounded.
     */
    public function toInt (bool $typecast = false):int {

        return $typecast
            ? (int)$this->number
            :Data::setType(NumFloat::round($this->number), Type::T_INT);

    }

    /**
     * ### Parse number
     *
     * Parse a number with grouped thousands and optionally decimal digits using the rounding half up rule.
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * $number = Number::from(1000.54)->parse();
     *
     * // 1,000.54
     *
     * @param non-negative-int $decimals [optional] <p>
     * Sets the number of decimal digits. If 0, the decimal_separator is omitted from the return value.
     * </p>
     * @param string $decimal_separator <p>
     * Sets the separator for the decimal point.
     * </p>
     * @param string $thousands_separator <p>
     * Sets the separator for thousands.
     * </p>
     *
     * @return string A formatted version of number.
     */
    public function parse (int $decimals = 0, string $decimal_separator = '.', string $thousands_separator = ','):string {

        return NumFloat::format(
            $this->number,
            $decimals,
            $decimal_separator,
            $thousands_separator
        );

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To set a data type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_STRING As data type.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Number;
     *
     * echo Number::from(10);
     *
     * // 10
     * ```
     */
    public function __toString ():string {

        return Data::setType($this->number, Type::T_STRING);

    }

}