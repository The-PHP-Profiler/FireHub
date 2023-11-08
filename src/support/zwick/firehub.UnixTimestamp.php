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

namespace FireHub\Core\Support\Zwick;

use FireHub\Core\Support\Zwick\Helpers\Parse;
use FireHub\Core\Support\Enums\Data\Type;
use FireHub\Core\Support\Enums\DateTime\ {
    Epoch, Format\Predefined, Month, Ordinal, TimeName, WeekDay, Unit\Unit
};
use FireHub\Core\Support\LowLevel\ {
    Data, DateAndTime, StrSB
};
use Error, ValueError;

/**
 * ### Epoch Unix timestamp
 *
 * Epoch Unix timestamp is the number of seconds since the Unix Epoch (January 1, 1970 00:00:00 GMT).
 * @since 1.0.0
 *
 * @method static self now (TimeZone $timezone = null) ### Create unix timestamp with current date and time
 * @method static self today (TimeName|string $at = TimeName::MIDNIGHT, TimeZone $timezone = null) ### Create unix timestamp with today's date
 * @method static self yesterday (TimeName|string $at = TimeName::MIDNIGHT, TimeZone $timezone = null) ### Create unix timestamp with yesterday's date
 * @method static self tomorrow (TimeName|string $at = TimeName::MIDNIGHT, TimeZone $timezone = null) ### Create unix timestamp with tomorrow's date
 * @method static self relative (int $number, Unit $unit, TimeName|string $at = TimeName::NOW, TimeZone $timezone = null) ### Create unix timestamp with relative date and time
 * @method static self firstDay (?Month $month = null, ?int $year = null, TimeName|string $at = TimeName::MIDNIGHT, TimeZone $timezone = null) ### Create unix timestamp with first day of specified month
 * @method static self lastDay (?Month $month = null, ?int $year = null, TimeName|string $at = TimeName::MIDNIGHT, TimeZone $timezone = null) ### Create unix timestamp with last day of specified month
 * @method static self weekDay (?Ordinal $ordinal, WeekDay $weekday, ?Month $month = null, ?int $year = null, TimeName|string $at = TimeName::MIDNIGHT, TimeZone $timezone = null) ### Create unix timestamp with specified weekday name and month
 *
 * @api
 */
class UnixTimestamp extends Zwick {

    /**
     * ### Timestamp reference point
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Epoch::UNIX As default value.
     *
     * @var string
     */
    protected string $epoch = Epoch::UNIX->value;

    /**
     * ### Timestamp seconds
     * @since 1.0.0
     *
     * @var int
     */
    protected int $seconds;

    /**
     * ### Timestamp fractions of the second
     * @since 1.0.0
     *
     * @var int
     */
    protected int $fractions = 0;

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::calculateSeconds() To calculate the number of seconds from a set
     * epoch.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::calculateFractions() To calculate number fractions in second.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::getDefaultTimeZone() To get default timezone.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::setDefaultTimeZone() To set default timezone.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::create() To create a new timezone.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::get() To get timezone.
     *
     * @param non-empty-string $datetime <p>
     * A date/time string.
     * </p>
     * @param null|\FireHub\Core\Support\Zwick\TimeZone $timezone [optional] <p>
     * TimeZone support class.
     * </p>
     *
     * @throws Error If we could not convert string to timestamp or could not get or set default timezone.
     * @error\exeption E_WARNING if the time zone is not valid.
     *
     * @return void
     */
    protected function __construct (string $datetime, TimeZone $timezone = null) {

        $default_timezone = TimeZone::getDefaultTimeZone();
        $timezone = $timezone ?? TimeZone::create($default_timezone);
        TimeZone::setDefaultTimeZone($timezone->get());

        $this->seconds = $this->calculateSeconds($datetime);

        $this->fractions = $this->calculateFractions($datetime);

        if ($timezone->get() <> $default_timezone) TimeZone::setDefaultTimeZone($default_timezone);

    }

    /**
     * ### Create unix timestamp from string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\TimeZone As parameter.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\UnixTimestamp;
     *
     * $timestamp = UnixTimestamp::from('now');
     *
     * // 1693392455
     * ```
     *
     * @param non-empty-string $datetime <p>
     * A date/time string.
     * </p>
     * @param null|\FireHub\Core\Support\Zwick\TimeZone $timezone [optional] <p>
     * TimeZone support class.
     * </p>
     *
     * @throws Error If we could not convert string to timestamp or could not get or set default timezone.
     * @error\exeption E_WARNING if the time zone is not valid.
     *
     * @return self New unix timestamp from string.
     */
    public static function from (string $datetime, TimeZone $timezone = null):self {

        return new self($datetime, $timezone);

    }

    /**
     * ### Create unix timestamp according to a specified format
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\Predefined As parameter.
     * @uses \FireHub\Core\Support\Zwick\TimeZone As parameter.
     * @uses \FireHub\Core\Support\Zwick\Zwick::dateFromFormat() To create date\time string according to a specified format.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\UnixTimestamp;
     *
     * $timestamp = UnixTimestamp::fromFormat('H:i:s', '20:10:00');
     *
     * // 1693419000
     * ```
     *
     * @param \FireHub\Core\Support\Enums\DateTime\Format\Predefined|non-empty-string $format <p>
     * Format accepted by date with some extras.
     * </p>
     * @param non-empty-string $datetime <p>
     * A date/time string.
     * </p>
     * @param null|\FireHub\Core\Support\Zwick\TimeZone $timezone [optional] <p>
     * TimeZone support class.
     * </p>
     *
     * @throws Error If we could not convert string to timestamp or could not get or set default timezone.
     * @throws ValueError If $datetime contains NULL-bytes.
     * @error\exeption E_WARNING if the time zone is not valid.
     *
     * @return self Unix timestamp from specified format.
     */
    public static function fromFormat (Predefined|string $format, string $datetime, TimeZone $timezone = null):self {

        return new self(
            self::dateFromFormat($format, $datetime), $timezone
        );

    }

    /**
     * ### Create unix timestamp from timestamp seconds
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Epoch::START As epoch value.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\UnixTimestamp;
     *
     * $timestamp = UnixTimestamp::fromTimestamp(1693392455);
     *
     * // 1693392455
     * ```
     *
     * @param int $time <p>
     * Timestamp seconds.
     * </p>
     *
     * @throws Error If we could not convert string to timestamp or could not get or set default timezone.
     * @error\exeption E_WARNING if the time zone is not valid.
     *
     * @return self New unix timestamp from timestamp seconds.
     */
    public static function fromTimestamp (int $time):self {

        $timestamp = new self(Epoch::START->value);

        $timestamp->seconds = $time;

        return $timestamp;

    }

    /**
     * ### Create unix timestamp from timestamp microseconds
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Epoch::START As epoch value.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT As data type.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::explode() To split a string by a string.
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To convert data to type.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\UnixTimestamp;
     *
     * $timestamp = UnixTimestamp::fromMicroTimestamp('1693392455.054155');
     *
     * // 1693392455
     * ```
     *
     * @param non-empty-string $microtime <p>
     * Timestamp microtime.
     * </p>
     *
     * @throws Error If we could not convert string to timestamp or could not get or set default timezone.
     * @error\exeption E_WARNING if the time zone is not valid.
     *
     * @return self New unix timestamp from timestamp microseconds.
     */
    public static function fromMicroTimestamp (string $microtime):self {

        $timestamp = new self(Epoch::START->value);

        $microtime = StrSB::explode($microtime, '.');

        $timestamp->seconds = Data::setType($microtime[0] ?? 0, Type::T_INT);
        $timestamp->fractions = Data::setType($microtime[1] ?? 0, Type::T_INT);

        return $timestamp;

    }

    /**
     * ### Get timestamp reference point
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Timestamp;
     *
     * $timestamp = UnixTimestamp::from('now');
     *
     * $seconds = $timestamp->epoch();
     *
     * // 1970-01-01 00:00:00
     * ```
     *
     * @return string Timestamp reference point.
     *
     */
    public function epoch ():string {

        return $this->epoch;

    }

    /**
     * ### Get timestamp seconds
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Timestamp;
     *
     * $timestamp = UnixTimestamp::from('now');
     *
     * $timestamp = $timestamp->seconds();
     *
     * // 1693419000
     * ```
     *
     * @return int Timestamp seconds.
     */
    public function seconds ():int {

        return $this->seconds;

    }

    /**
     * ### Get timestamp fractions
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Timestamp;
     *
     * $timestamp = UnixTimestamp::from('now');
     *
     * $fraction = $timestamp->fractions();
     *
     * // 456112
     * ```
     *
     * @return int Timestamp fractions.
     */
    public function fractions ():int {

        return $this->fractions;

    }

    /**
     * ### Get timestamp microtime
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Zwick::formatFraction() To format fractions as string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Timestamp;
     *
     * $timestamp = UnixTimestamp::from('now');
     *
     * $micro_timestamp = $timestamp->microTimestamp();
     *
     * // 1693419000.567122
     * ```
     *
     * @return non-empty-string Timestamp microtime.
     *
     */
    public function microTimestamp ():string {

        return "$this->seconds.{$this->formatFraction($this->fractions)}";

    }

    /**
     * ### Convert unix timestamp from current timestamp
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\UnixTimestamp;
     *
     * $timestamp = UnixTimestamp::from('now');
     *
     * // 38937
     *
     * $unix_timestamp = UnixTimestamp::toUnix();
     *
     * // 1693392537
     * ```
     *
     * @return \FireHub\Core\Support\Zwick\UnixTimestamp Unix timestamp.
     */
    public function toUnix ():UnixTimestamp {

        return $this;

    }

    /**
     * ### Calculate number of seconds from set epoch
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::stringToTimestamp() To parse about any English textual datetime
     * description into a Unix timestamp.
     *
     * @param non-empty-string $datetime <p>
     * A date/time string.
     * </p>
     *
     * @throws Error If we could not convert string to timestamp.
     * @error\exeption E_WARNING if the time zone is not valid.
     *
     * @return int Number of seconds from set epoch.
     */
    protected function calculateSeconds (string $datetime):int {

        return DateAndTime::stringToTimestamp($datetime);

    }

    /**
     * ### Calculate number fractions in second
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::parse() To get an associative array with detailed info about given
     * date/time.
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::microtime() To get current Unix microseconds.
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To set a data type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT As data type.
     *
     * @param non-empty-string $datetime <p>
     * A date/time string.
     * </p>
     *
     * @return int<0, 999999> Fraction of second.
     */
    final protected function calculateFractions (string $datetime):int {

        /** @phpstan-ignore-next-line */
        return ($parse = DateAndTime::parse($datetime)) && $parse['fraction'] !== false
            ? Data::setType($parse['fraction'] * 1000000, Type::T_INT)
            : DateAndTime::microtime();

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\TimeZone To check if argument contains an instance of TimeZone.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::from() To create unix timestamp from string.
     * @uses \FireHub\Core\Support\Zwick\Helpers\Parse To parse about any English textual datetime description into a
     * date/time.
     *
     * @throws Error If we could not convert string to timestamp or could not get or set default timezone.
     * @error\exeption E_WARNING if the time zone is not valid.
     */
    public static function __callStatic (string $method, array $arguments):self {

        foreach ($arguments as $argument_key => $argument_value) if ($argument_value instanceof TimeZone) {
            $timezone = $argument_value;
            unset($arguments[$argument_key]);
        }

        return self::from(Parse::$method(...$arguments), $timezone ?? null);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::microTimestamp() To get timestamp microtime.
     */
    public function __toString ():string {

        return $this->microTimestamp();

    }

}