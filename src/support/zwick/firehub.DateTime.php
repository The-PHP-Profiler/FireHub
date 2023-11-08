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
use FireHub\Core\Support\Zwick\Traits\ {
    Check, Get, Set
};
use FireHub\Core\Support\Enums\ {
    Side, Data\Type
};
use FireHub\Core\Support\Enums\DateTime\ {
    Month, Ordinal, TimeName, WeekDay, Unit\Unit, Zone,
    Format\Format, Format\Predefined
};
use FireHub\Core\Support\LowLevel\{
    Data, DataIs, DateAndTime, NumFloat, NumInt, StrSB
};
use Error, ValueError;

/**
 * ### Date and time manipulation support library
 *
 * This class allows you to represent date/time information with a rich set of methods that are
 * supplied to modify and format this information as well.
 * @since 1.0.0
 *
 * @method static self now (TimeZone $timezone = null) ### Create datetime with current date and time
 * @method static self today (TimeName|string $at = TimeName::MIDNIGHT, TimeZone $timezone = null) ### Create datetime with today's date
 * @method static self yesterday (TimeName|string $at = TimeName::MIDNIGHT, TimeZone $timezone = null) ### Create datetime with yesterday's date
 * @method static self tomorrow (TimeName|string $at = TimeName::MIDNIGHT, TimeZone $timezone = null) ### Create datetime with tomorrow's date
 * @method static self relative (int $number, Unit $unit, TimeName|string $at = TimeName::NOW, TimeZone $timezone = null) ### Create datetime with relative date and time
 * @method static self firstDay (?Month $month = null, ?int $year = null, TimeName|string $at = TimeName::MIDNIGHT, TimeZone $timezone = null) ### Create datetime with first day of specified month
 * @method static self lastDay (?Month $month = null, ?int $year = null, TimeName|string $at = TimeName::MIDNIGHT, TimeZone $timezone = null) ### Create datetime with last day of specified month
 * @method static self weekDay (?Ordinal $ordinal, WeekDay $weekday, ?Month $month = null, ?int $year = null, TimeName|string $at = TimeName::MIDNIGHT, TimeZone $timezone = null) ### Create datetime with specified weekday name and month
 *
 * @api
 */
final class DateTime extends Zwick {

    /**
     * ### Check information about current date\time
     * @since 1.0.0
     */
    use Check;

    /**
     * ### Get information about current date\time
     * @since 1.0.0
     */
    use Get;

    /**
     * ### Set information for current date\time
     * @since 1.0.0
     */
    use Set;

    /**
     * ### Datetime string
     * @since 1.0.0
     *
     * @var non-empty-string
     */
    private string $datetime;

    /**
     * ### TimeZone support class
     * @since 1.0.0
     *
     * @var \FireHub\Core\Support\Zwick\TimeZone
     */
    private TimeZone $timezone;

    /**
     * ### Default timezone
     * @since 1.0.0
     *
     * @var \FireHub\Core\Support\Enums\DateTime\Zone
     */
    private Zone $default_timezone;

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::from() To create unix timestamp from string.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::formatFraction() To format fractions as string.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::fractions() To get timestamp fractions.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::seconds() To get timestamp seconds.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::getDefaultTimeZone() To get default timezone.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::setDefaultTimeZone() To set default timezone.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::create() To create a new timezone.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::get() To get timezone.
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::format() To format a Unix timestamp.
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
    private function __construct (string $datetime, TimeZone $timezone = null) {

        $this->default_timezone = TimeZone::getDefaultTimeZone();
        $this->timezone = $timezone ?? TimeZone::create($this->default_timezone);
        TimeZone::setDefaultTimeZone($this->timezone->get());

        $timestamp = UnixTimestamp::from($datetime, $timezone);

        $this->datetime = ($datetime = DateAndTime::format(
            'Y-m-d H:i:s.'.$timestamp->formatFraction($timestamp->fractions()),
            $timestamp->seconds()
        )) !== '' ? $datetime : throw new Error('Datetime is empty.');

        if ($this->timezone->get() <> $this->default_timezone)
            TimeZone::setDefaultTimeZone($this->default_timezone);

    }

    /**
     * ### Create datetime from string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\TimeZone As parameter.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::from('now');
     *
     * // 2023-08-30 13:57:10.103403
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
     * @return self New datetime from string.
     */
    public static function from (string $datetime, TimeZone $timezone = null):self {

        return new self($datetime, $timezone);

    }

    /**
     * ### Create datetime according to a specified format
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\Predefined As parameter.
     * @uses \FireHub\Core\Support\Zwick\TimeZone As parameter.
     * @uses \FireHub\Core\Support\Zwick\Zwick::dateFromFormat() To create date\time string according to a specified
     * format.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::fromFormat('H:i:s', '20:10:00');
     *
     * // 2023-08-30 20:10:00.000000
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
     * @return self New datetime specified format.
     */
    public static function fromFormat (Predefined|string $format, string $datetime, TimeZone $timezone = null):self {

        return new self(self::dateFromFormat($format, $datetime), $timezone);

    }

    /**
     * ### Create datetime from unix timestamp
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::toUnix() To convert unix timestamp from current timestamp.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::getDefaultTimeZone() To get default timezone.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::setDefaultTimeZone() To set default timezone.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::create() To create a new timezone.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::get() To get timezone.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::formatFraction() To format fractions as string.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::fractions() To get timestamp fractions.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::seconds() To get timestamp seconds.
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::format() To format a Unix timestamp.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\UnixTimestamp;
     *
     * $datetime = DateTime::fromTimestamp(1693392455);
     *
     * // 1693392455
     * ```
     *
     * @param \FireHub\Core\Support\Zwick\UnixTimestamp $timestamp <p>
     * Epoch Unix timestamp.
     * </p>
     * @param null|\FireHub\Core\Support\Zwick\TimeZone $timezone [optional] <p>
     * TimeZone support class.
     * </p>
     *
     * @throws Error If we could not convert string to timestamp or could not get or set default timezone.
     * @error\exeption E_WARNING if the time zone is not valid.
     *
     * @return self New datetime from unix timestamp.
     */
    public static function fromTimestamp (UnixTimestamp $timestamp, TimeZone $timezone = null):self {

        $timestamp = $timestamp->toUnix();

        $default_timezone = TimeZone::getDefaultTimeZone();
        $timezone = $timezone ?? TimeZone::create($default_timezone);
        TimeZone::setDefaultTimeZone($timezone->get());

        $datetime = ($datetime = DateAndTime::format(
            'Y-m-d H:i:s.'.$timestamp->formatFraction($timestamp->fractions()),
            $timestamp->seconds()
        )) !== '' ? $datetime : throw new Error('Datetime is empty.');

        if ($timezone->get() <> $default_timezone) TimeZone::setDefaultTimeZone($default_timezone);

        return new self($datetime, $timezone);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\Format As parameter.
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::format() To format a Unix timestamp.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::replace() To replace all occurrences of the search string with the replacement string.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::string() To find whether the type of variable is a string.
     * @uses \FireHub\Core\Support\Zwick\DateTime:formatFraction() To format fractions as string.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::fractions() To get timestamp microseconds.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::seconds() To get timestamp seconds.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::parse('H:i:s');
     *
     * // 2023-08-30
     * ```
     *
     * @throws Error If we could not convert string to timestamp or could not get or set default timezone.
     * @error\exeption E_WARNING if the time zone is not valid.
     *
     * @return non-empty-string Formatted datetime.
     */
    public function parse (Format|string $format):string {

        $timestamp = UnixTimestamp::from($this->datetime);

        return ($datetime = DateAndTime::format(
            StrSB::replace(
                ['u', 'v'],
                [$this->formatFraction(
                    $timestamp->fractions()),
                    $this->formatFraction($timestamp->fractions(), true)
                ],
                ($format = DataIs::string($format)
                    ? $format
                    : (DataIs::string($format->value)
                        ? $format->value
                        : throw new Error('Format enum value must be string.'))
                ) ? $format : throw new Error('Format enum value must be string.')
            ),
            $timestamp->seconds()
        )) !== '' ? $datetime : throw new Error('Datetime is empty.');

    }

    /**
     * ### Gets uNIX timestamp
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp As return.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::from() To create unix timestamp from string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->timestamp();
     *
     * // 1693400369
     * ```
     *
     * @throws Error If we could not convert string to timestamp or could not get or set default timezone.
     * @error\exeption E_WARNING if the time zone is not valid.
     *
     * @return \FireHub\Core\Support\Zwick\UnixTimestamp Datetime Unix timestamp.
     */
    public function timestamp ():UnixTimestamp {

        return UnixTimestamp::from($this->datetime, $this->timezone);

    }

    /**
     * ### Gets timezone for datetime
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\TimeZone As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->timezone();
     *
     * // America/New_York
     * ```
     *
     * @return \FireHub\Core\Support\Zwick\TimeZone Timezone for datetime.
     */
    public function timezone ():TimeZone {

        return $this->timezone;

    }

    /**
     * ### Sets new timezone
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::from() To create unix timestamp from string.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp:formatFraction() To format fractions as string.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::fractions() To get timestamp microseconds.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::seconds() To get timestamp seconds.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::getDefaultTimeZone() To get default timezone.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::setDefaultTimeZone() To set default timezone.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::get() To get timezone.
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::format() To format a Unix timestamp.
     *
     * @param \FireHub\Core\Support\Zwick\TimeZone $timezone <p>
     * TimeZone support class.
     * </p>
     *
     * @throws Error If we could not convert string to timestamp or could not get or set default timezone.
     * @error\exeption E_WARNING if the time zone is not valid.
     *
     * @return $this Datetime with new timezone.
     */
    public function changeTimezone (TimeZone $timezone):self {

        $timestamp = UnixTimestamp::from($this->datetime);
        $seconds = $timestamp->seconds();

        $default_timezone = TimeZone::getDefaultTimeZone();
        $this->timezone = $timezone;
        TimeZone::setDefaultTimeZone($this->timezone->get());

        $this->datetime = ($datetime = DateAndTime::format(
            'Y-m-d H:i:s.'.$timestamp->formatFraction($timestamp->fractions()),
            $seconds
        )) !== '' ? $datetime : throw new Error('Datetime is empty.');

        if ($this->timezone->get() <> $this->default_timezone)
            TimeZone::setDefaultTimeZone($default_timezone);

        return $this;

    }

    /**
     * ### Get offset in seconds between selected timezone and Coordinated Universal Time(UTC)
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\DateTime::from() To create datetime from string.
     * @uses \FireHub\Core\Support\Zwick\DateTime::timestamp() To get Unix timestamp.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::seconds() To get timestamp seconds.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::create() To create a new timezone.
     * @uses \FireHub\Core\Support\Enums\DateTime\TimeZone::UTC As TimeZone value.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->timezoneOffset();
     *
     * // 7200
     * ```
     *
     * @throws Error If we could not convert string to timestamp or could not get or set default timezone.
     * @error\exeption E_WARNING if the time zone is not valid.
     *
     * @return int Offset in seconds between selected timezone and Coordinated Universal Time(UTC).
     */
    public function timezoneOffset ():int {

        return self::from(
            $this->datetime,
            TimeZone::create(Zone::UTC)
            )->timestamp()->seconds() - $this->timestamp()->seconds();

    }

    /**
     * ### Get offset in seconds between selected timezone and Coordinated Universal Time(UTC)
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\DateTime::from() To create datetime from string.
     * @uses \FireHub\Core\Support\Zwick\DateTime::timestamp() To get Unix timestamp.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::seconds() To get timestamp seconds.
     * @uses \FireHub\Core\Support\Zwick\TimeZone::create() To create a new timezone.
     * @uses \FireHub\Core\Support\Enums\DateTime\TimeZone::EUROPE_LONDON As TimeZone value.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->timezoneOffsetGMT();
     *
     * // 3600
     * ```
     *
     * @throws Error If we could not convert string to timestamp or could not get or set default timezone.
     * @error\exeption E_WARNING if the time zone is not valid.
     *
     * @return int Offset in seconds between selected timezone and Coordinated Universal Time(UTC).
     */
    public function timezoneOffsetGMT ():int {

        return self::from(
            $this->datetime,
            TimeZone::create(Zone::EUROPE_LONDON)
            )->timestamp()->seconds() - $this->timestamp()->seconds();

    }

    /**
     * ### Get offset in seconds between two DateTime timezones
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\DateTime::timezoneOffsetGMT() To get offset in seconds between selected
     * timezone and Coordinated Universal Time(UTC).
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     * use FireHub\Core\Support\Enums\DateTime\TimeZone;
     *
     * $datetime1 = DateTime::now(TimeZone::AMERICA_NEW_YORK);
     * $datetime2 = DateTime::now(TimeZone::ASIA_TOKYO);
     *
     * $datetime1->timezoneOffsetFrom($datetime2);
     *
     * // 46800
     * ```
     *
     * @return int Offset in seconds between two DateTime timezones.
     */
    public function timezoneOffsetFrom (self $datetime):int {

        return $this->timezoneOffsetGMT() - $datetime->timezoneOffsetGMT();

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::format() To format a Unix timestamp.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::pad To pad a string to a certain length with another string.
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To convert data to type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_STRING As data type.
     * @uses \FireHub\Core\Support\Enums\Side::LEFT As side enum.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::from() To create unix timestamp from string.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp:formatFraction() To format fractions as string.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::fractions() To get timestamp microseconds.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::seconds() To get timestamp seconds.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::year() To get year.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::month() To get month.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::day() To get day of the month.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::hour() To get current hour.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::minute() To get current minute.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::second() To get current second.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::microSecond() To get current micro second.
     *
     * @throws Error If a system could not get timestamp from $datetime.
     */
    private function set (int $year = null, ?int $month = null, ?int $day = null, ?int $hour = null, ?int $minute = null, ?int $second = null, ?int $microsecond = null):static {

        $timestamp = UnixTimestamp::from(
            ($year ? StrSB::pad(Data::setType($year, Type::T_STRING), 4, '0', Side::LEFT) : $this->year(false))
            .'-'.($month ? StrSB::pad(Data::setType($month, Type::T_STRING), 2, '0', Side::LEFT) : $this->month(false))
            .'-'.($day ? StrSB::pad(Data::setType($day, Type::T_STRING), 2, '0', Side::LEFT) : $this->day(false))
            .' '.($hour ? StrSB::pad(Data::setType($hour, Type::T_STRING), 2, '0', Side::LEFT) : $this->hour(false))
            .':'.($minute ? StrSB::pad(Data::setType($minute, Type::T_STRING), 2, '0', Side::LEFT) : $this->minute(false))
            .':'.($second ? StrSB::pad(Data::setType($second, Type::T_STRING), 2, '0', Side::LEFT) : $this->second(false))
            .'.'.($microsecond ? $this->formatFraction($microsecond) : $this->microSecond(false))
        );

        $this->datetime = ($datetime = DateAndTime::format(
            'Y-m-d H:i:s.'.$timestamp->formatFraction($timestamp->fractions()),
            $timestamp->seconds()
        )) !== '' ? $datetime : throw new Error('Datetime is empty.');

        return $this;

    }

    /**
     * ### Add an interval to datetime
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Interval As parameter.
     * @uses \FireHub\Core\Support\Zwick\Interval::getYears() To get years from an interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::getMonths() To get months from an interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::getDays() To get days from an interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::getHours() To get hours from an interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::getMinutes() To get minutes from an interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::getSeconds() To get seconds from an interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::getMicroSeconds() To get microseconds from an interval.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::from() To create unix timestamp from string.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp:formatFraction() To format fractions as string.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::seconds() To get timestamp seconds.
     * @uses \FireHub\Core\Support\Zwick\DateTime::microSecond() To get microsecond of the time.
     * @uses \FireHub\Core\Support\LowLevel\NumInt::divide() To divide integer.
     * @uses \FireHub\Core\Support\LowLevel\NumFloat::remainder() To get the floating point remainder (modulo) of
     * division of the arguments.
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To convert data to type.
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::format() To format a Unix timestamp.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT As data type.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     * use FireHub\Core\Support\Zwick\Interval;
     *
     * $datetime = DateTime::now()->add(Interval::seconds(5));
     *
     * // 2023-08-30 20:10:05.569234
     * ```
     *
     * @param \FireHub\Core\Support\Zwick\Interval $interval <p>
     * Datetime interval.
     * </p>
     *
     * @return $this This datetime with added interval.
     */
    public function add (Interval $interval):self {

        $microseconds = $this->microSecond() + $interval->getMicroSeconds();
        $seconds = NumInt::divide($microseconds, 1000000) + $interval->getSeconds();
        $microseconds = Data::setType(NumFloat::remainder($microseconds, 1000000), Type::T_INT);

        $timestamp = UnixTimestamp::from(
            "$this->datetime {$interval->getYears()} years {$interval->getMonths()} months {$interval->getDays()} days {$interval->getHours()} hours {$interval->getMinutes()} minutes $seconds seconds"
        );

        $this->datetime = ($datetime = DateAndTime::format(
            'Y-m-d H:i:s.'.$timestamp->formatFraction($microseconds),
            $timestamp->seconds()
        )) !== '' ? $datetime : throw new Error('Datetime is empty.');

        return $this;

    }

    /**
     * ### Subtract interval to datetime
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Interval As parameter.
     * @uses \FireHub\Core\Support\Zwick\Interval::getYears() To get years from an interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::getMonths() To get months from an interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::getDays() To get days from an interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::getHours() To get hours from an interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::getMinutes() To get minutes from an interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::getSeconds() To get seconds from an interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::getMicroSeconds() To get microseconds from an interval.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::from() To create unix timestamp from string.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp:formatFraction() To format fractions as string.
     * @uses \FireHub\Core\Support\Zwick\UnixTimestamp::seconds() To get timestamp seconds.
     * @uses \FireHub\Core\Support\Zwick\DateTime::microSecond() To get microsecond of the time.
     * @uses \FireHub\Core\Support\LowLevel\NumFloat::remainder() To get the floating point remainder (modulo) of
     * division of the arguments.
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To convert data to type.
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::format() To format a Unix timestamp.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT As data type.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     * use FireHub\Core\Support\Zwick\Interval;
     *
     * $datetime = DateTime::now()->sub(Interval::seconds(5));
     *
     * // 2023-08-30 20:09:55.569234
     * ```
     *
     * @param \FireHub\Core\Support\Zwick\Interval $interval <p>
     * Datetime interval.
     * </p>
     *
     * @return $this This datetime with subtracted interval.
     */
    public function sub (Interval $interval):self {

        $microseconds = ($microseconds = $this->microSecond() - $interval->getMicroSeconds()) >= 0
            ? $microseconds
            : (($microseconds = 1000000 + Data::setType(
                NumFloat::remainder($microseconds, 1000000),
                Type::T_INT)) < 1000000
                ? $microseconds
                : 0
            )
        ;

        $timestamp = UnixTimestamp::from(
            "$this->datetime -{$interval->getYears()} years -{$interval->getMonths()} months -{$interval->getDays()} days -{$interval->getHours()} hours -{$interval->getMinutes()} minutes -{$interval->getSeconds()} seconds -{$interval->getMicroSeconds()} microseconds"
        );

        $this->datetime = ($datetime = DateAndTime::format(
            'Y-m-d H:i:s.'.$timestamp->formatFraction($microseconds),
            $timestamp->seconds()
        )) !== '' ? $datetime : throw new Error('Datetime is empty.');

        return $this;

    }

    /**
     * ### Difference between two dates
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Interval As return.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::year() To get year.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::month() To get month.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::day() To get day of the month.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::hour() To get current hour.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::minute() To get current minute.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::second() To get current second.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::microSecond() To get current micro second.
     * @uses \FireHub\Core\Support\Zwick\Interval::years() To add years to an interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::addMonths() To add months to an interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::addDays() To add days to an interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::addHours() To add hours to an interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::addMinutes() To add minutes to an interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::addSeconds() To add seconds to an interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::addMicroSeconds() To add microseconds to an interval.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     * use FireHub\Core\Support\Zwick\Interval;
     *
     * $datetime = DateTime::today()->difference(DateTime::yesterday());
     * ```
     *
     * @param \FireHub\Core\Support\Zwick\DateTime $date <p>
     * Datetime to compare.
     * </p>
     *
     * @return \FireHub\Core\Support\Zwick\Interval Interval difference between two dates.
     */
    public function difference (self $date):Interval {

        return Interval::years($this->year() - $date->year())
            ->addMonths($this->month() - $date->month())
            ->addDays($this->day() - $date->day())
            ->addHours($this->hour() - $date->hour())
            ->addMinutes($this->minute() - $date->minute())
            ->addSeconds($this->second() - $date->second())
            ->addMicroSeconds($this->microSecond() - $date->microSecond());

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\TimeZone To check if argument contains an instance of TimeZone.
     * @uses \FireHub\Core\Support\Zwick\DateTime::from() To create datetime from string.
     * @uses \FireHub\Core\Support\Zwick\Helpers\Parse To parse about any English textual datetime description into a
     * date/time.
     *
     * @throws Error If we could not convert string to timestamp or could not get or set default timezone.
     * @error\exeption E_WARNING if the time zone is not valid.
     *
     * @return self New datetime.
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
     */
    public function __toString ():string {

        return $this->datetime;

    }

}