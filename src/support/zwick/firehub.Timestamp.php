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
    Epoch, Format\Predefined, Month, Ordinal, TimeName, WeekDay, Unit\Unit, Zone
};
use FireHub\Core\Support\LowLevel\ {
    Arr, Data, DataIs, DateAndTime, StrSB
};
use Error, ValueError;

/**
 * ### Epoch timestamp
 *
 * Epoch timestamp is the number of seconds passes form fixed date and time used as a reference
 * from which a computer measures system time.
 * @since 1.0.0
 *
 * @method static self now (Epoch|string $epoch, TimeZone $timezone = null) ### Create timestamp with current date and time
 * @method static self today (Epoch|string $epoch, TimeName|string $at = TimeName::MIDNIGHT, TimeZone $timezone = null) ### Create timestamp with today's date
 * @method static self yesterday (Epoch|string $epoch, TimeName|string $at = TimeName::MIDNIGHT, TimeZone $timezone = null) ### Create timestamp with yesterday's date
 * @method static self tomorrow (Epoch|string $epoch, TimeName|string $at = TimeName::MIDNIGHT, TimeZone $timezone = null) ### Create timestamp with tomorrow's date
 * @method static self relative (Epoch|string $epoch, int $number, Unit $unit, TimeName|string $at = TimeName::NOW, TimeZone $timezone = null) ### Create timestamp with relative date and time
 * @method static self firstDay (Epoch|string $epoch, ?Month $month = null, ?int $year = null, TimeName|string $at = TimeName::MIDNIGHT, TimeZone $timezone = null) ### Create timestamp with first day of specified month
 * @method static self lastDay (Epoch|string $epoch, ?Month $month = null, ?int $year = null, TimeName|string $at = TimeName::MIDNIGHT, TimeZone $timezone = null) ### Create timestamp with last day of specified month
 * @method static self weekDay (Epoch|string $epoch, ?Ordinal $ordinal, WeekDay $weekday, ?Month $month = null, ?int $year = null, TimeName|string $at = TimeName::MIDNIGHT, TimeZone $timezone = null) ### Create timestamp with specified weekday name and month
 *
 * @api
 */
final class Timestamp extends UnixTimestamp {

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::_construct() As parent constructor.
     * @uses \FireHub\Core\Support\Zwick\TimeZone As parameter.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::getDefaultTimeZone() To get default timezone.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::setDefaultTimeZone() To set default timezone.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::create() To create a new timezone.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::get() To get timezone.
     * @uses \FireHub\Core\Support\Enums\DateTime\Epoch As parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\Epoch::UNIX As epoch enum.
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::format() To format a Unix timestamp.
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::stringToTimestamp() To parse about any English textual
     * datetime description into a Unix timestamp.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::string() To find whether the type of variable is a string.
     *
     * @param non-empty-string $datetime <p>
     * A date/time string.
     * </p>
     * @param \FireHub\Core\Support\Enums\DateTime\Epoch|non-empty-string $epoch <p>
     * Timestamp reference point.
     * </p>
     * @param null|\FireHub\Core\Support\Zwick\TimeZone $timezone [optional] <p>
     * TimeZone support class.
     * </p>
     *
     * @throws Error If we could not convert string to timestamp or could not get or set default timezone.
     * @error\exeption E_WARNING if the time zone is not valid.
     */
    protected function __construct (string $datetime, Epoch|string $epoch, TimeZone $timezone = null) {

        $default_timezone = TimeZone::getDefaultTimeZone();
        $timezone = $timezone ?? TimeZone::create($default_timezone);
        TimeZone::setDefaultTimeZone($timezone->get());

        $epoch = DataIs::string($epoch) ? $epoch : $epoch->value;

        $this->epoch = DateAndTime::format(
            'Y-m-d H:i:s',
            DateAndTime::stringToTimestamp($epoch)
        );

        parent::__construct($datetime);

        if ($timezone->get() <> $default_timezone) TimeZone::setDefaultTimeZone($default_timezone);

    }

    /**
     * ### Create timestamp from string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\TimeZone As parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\Epoch As parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\Epoch::UNIX As epoch enum.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Timestamp;
     *
     * $timestamp = Timestamp::from('now', 'today');
     *
     * // 38791
     * ```
     *
     * @param non-empty-string $datetime <p>
     * A date/time string.
     * </p>
     * @param null|\FireHub\Core\Support\Zwick\TimeZone $timezone [optional] <p>
     * TimeZone support class.
     * </p>
     * @param \FireHub\Core\Support\Enums\DateTime\Epoch|non-empty-string $epoch [optional] <p>
     * Timestamp reference point.
     * </p>
     *
     * @throws Error If we could not convert string to timestamp or could not get or set default timezone.
     * @error\exeption E_WARNING if the time zone is not valid.
     *
     * @return self New timestamp from string.
     */
    public static function from (string $datetime, TimeZone $timezone = null, Epoch|string $epoch = Epoch::UNIX):self {

        return new self($datetime, $epoch, $timezone);

    }

    /**
     * ### Create timestamp according to a specified format
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\Predefined As parameter.
     * @uses \FireHub\Core\Support\Zwick\TimeZone As parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\Epoch As parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\Epoch::UNIX As epoch enum.
     * @uses \FireHub\Core\Support\Zwick\Zwick::dateFromFormat() To create date\time string according to a specified format.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Timestamp;
     *
     * $timestamp = Timestamp::fromFormat('H:i:s', '20:10:00', 'today');
     *
     * // 65400
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
     * @param \FireHub\Core\Support\Enums\DateTime\Epoch|non-empty-string $epoch [optional] <p>
     * Timestamp reference point.
     * </p>
     *
     * @throws Error If we could not convert string to timestamp or could not get or set default timezone.
     * @throws ValueError If $datetime contains NULL-bytes.
     * @error\exeption E_WARNING if the time zone is not valid.
     *
     * @return self Timestamp from specified format.
     */
    public static function fromFormat (Predefined|string $format, string $datetime, TimeZone $timezone = null, Epoch|string $epoch = Epoch::UNIX):self {

        return new self(
            self::dateFromFormat($format, $datetime), $epoch, $timezone
        );

    }

    /**
     * ### Create timestamp from timestamp seconds
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Epoch As parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\Epoch::UNIX As epoch enum.
     * @uses \FireHub\Core\Support\Enums\DateTime\Epoch::START As epoch value.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Timestamp;
     *
     * $timestamp = Timestamp::fromTimestamp(38791, 'today');
     *
     * // 38791
     * ```
     *
     * @param int $time <p>
     * Timestamp seconds.
     * </p>
     * @param \FireHub\Core\Support\Enums\DateTime\Epoch|non-empty-string $epoch [optional] <p>
     * Timestamp reference point.
     * </p>
     *
     * @throws Error If we could not convert string to timestamp or could not get or set default timezone.
     * @error\exeption E_WARNING if the time zone is not valid.
     *
     * @return self New timestamp from timestamp seconds.
     */
    public static function fromTimestamp (int $time, Epoch|string $epoch = Epoch::UNIX):self {

        $timestamp = new self(Epoch::START->value, $epoch);

        $timestamp->seconds = $time;

        return $timestamp;

    }

    /**
     * ### Create timestamp from timestamp microseconds
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Epoch::START As epoch value.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT As data type.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::explode() To split a string by a string.
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To convert data to type.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Timestamp;
     *
     * $timestamp = Timestamp::fromMicroTimestamp('1693392455.054155', '1970-01-01');
     *
     * // 1693392455
     * ```
     *
     * @param non-empty-string $microtime <p>
     * Timestamp microtime.
     * </p>
     * @param \FireHub\Core\Support\Enums\DateTime\Epoch|non-empty-string $epoch [optional] <p>
     * Timestamp reference point.
     * </p>
     *
     * @throws Error If we could not convert string to timestamp or could not get or set default timezone.
     * @error\exeption E_WARNING if the time zone is not valid.
     *
     * @return self New timestamp from timestamp microseconds.
     */
    public static function fromMicroTimestamp (string $microtime, Epoch|string $epoch = Epoch::UNIX):self {

        $timestamp = new self(Epoch::START->value, $epoch);

        $microtime = StrSB::explode($microtime, '.');

        $timestamp->seconds = Data::setType($microtime[0] ?? 0, Type::T_INT);
        $timestamp->fractions = Data::setType($microtime[1] ?? 0, Type::T_INT);

        return $timestamp;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp As return.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::from() To create unix timestamp from string.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::create() To create a new timezone.
     * @uses \FireHub\Core\Support\Enums\DateTime\Zone::UTC As timezone enum.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Timestamp;
     *
     * $timestamp = Timestamp::from('now', 'today');
     *
     * // 38937
     *
     * $unix_timestamp = Timestamp::toUnix();
     *
     * // 1693392537
     * ```
     */
    public function toUnix ():UnixTimestamp {

        $timestamp = UnixTimestamp::from(
            $this->epoch." $this->seconds seconds".TimeZone::create(Zone::UTC)
        );

        $timestamp->fractions = $this->fractions;

        return $timestamp;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::stringToTimestamp() To parse about any English textual datetime
     * description into a Unix timestamp.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::create() To create a new timezone.
     * @uses \FireHub\Core\Support\Enums\DateTime\Zone::UTC As timezone enum.
     *
     * @throws Error If we could not convert string to timestamp.
     */
    protected function calculateSeconds (string $datetime):int {

        return DateAndTime::stringToTimestamp($datetime)
            - DateAndTime::stringToTimestamp($this->epoch.' '.TimeZone::create(Zone::UTC));

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::firstKey() To get the first key from an array.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::string() To find whether the type of variable is a string.
     * @uses \FireHub\Core\Support\Enums\DateTime\Epoch To check if argument contains an instance of Epoch.
     * @uses \FireHub\Core\Support\Zwick\TimeZone To check if argument contains an instance of TimeZone.
     * @uses \FireHub\Core\Support\Zwick\Timestamp::from() To create timestamp from string.
     * @uses \FireHub\Core\Support\Zwick\Helpers\Parse To parse about any English textual datetime description into a
     * date/time.
     *
     * @throws Error If argument epoch must exist, or is not string or instance of Epoch enum, or $epoch is not correct
     * datetime format, we could not convert string to timestamp or could not get or set the default timezone.
     * @error\exeption E_WARNING if the time zone is not valid.
     *
     * @return self New timestamp.
     */
    public static function __callStatic (string $method, array $arguments):self {

        foreach ($arguments as $argument_key => $argument_value) if ($argument_value instanceof TimeZone) {
            $timezone = $argument_value;
            unset($arguments[$argument_key]);
        }

        $epoch_key = Arr::firstKey($arguments) ?? throw new Error('Argument epoch must exist.');

        $epoch_value = ($epoch_value = $arguments[$epoch_key]) instanceof Epoch || DataIs::string($epoch_value)
            ? $epoch_value
            : throw new Error('Epoch argument must be string or instance of Epoch enum.');
        unset($arguments[$epoch_key]);

        /** @phpstan-ignore-next-line */
        return self::from(Parse::$method(...$arguments), $timezone ?? null, $epoch_value);

    }

}