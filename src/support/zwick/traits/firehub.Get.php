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

namespace FireHub\Core\Support\Zwick\Traits;

use FireHub\Core\Support\Enums\DateTime\ {
    Format\Format, Format\Year as YearFormat, Format\Month as MonthFormat,
    Format\Week as WeekFormat, Format\Day as DayFormat, Format\Time as TimeFormat,
    Unit\Years, Unit\Months
};
use FireHub\Core\Support\Enums\Data\Type;
use FireHub\Core\Support\LowLevel\ {
    Data, NumInt
};

/**
 * ### Get information about current date\time
 * @since 1.0.0
 */
trait Get {

    /**
     * ### Get millennium
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\NumInt::ceil() To round fractions up.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::year() To get year.
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Years::MILLENNIUM As years unit.
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Years::calculate() To calculate number of units.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->millennium();
     *
     * // 3
     * ```
     *
     * @return int Millennium.
     */
    public function millennium ():int {

        return NumInt::ceil(($this->year() + 1) / Years::MILLENNIUM->calculate());

    }

    /**
     * ### Get century
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\NumInt::ceil() To round fractions up.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::year() To get year.
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Years::CENTURY As years unit.
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Years::calculate() To calculate number of units.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->century();
     *
     * // 21
     * ```
     *
     * @return int Century.
     */
    public function century ():int {

        return NumInt::ceil(($this->year() + 1) / Years::CENTURY->calculate());

    }

    /**
     * ### Get decade in century
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\NumInt::ceil() To round fractions up.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::year() To get year.
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Years::DECADE As years unit.
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Years::calculate() To calculate number of units.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->decade();
     *
     * // 3
     * ```
     *
     * @return int Decade.
     */
    public function decade ():int {

        return NumInt::ceil(($this->year() + 1) / Years::DECADE->calculate());

    }

    /**
     * ### Get year
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To change type.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\Year::LONG As format type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT To set type as integer.
     *
     * @example Get Calendar year.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->year();
     *
     * // 2023
     * ```
     * @example Get Calendar short year.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->year(true);
     *
     * // 23
     * ```
     *
     * @param bool $short [optional] <p>
     * If true, two digit representation of a year will be returned.
     * </p>
     *
     * @return ($short is true ? int : string) Year.
     */
    public function year (bool $short = true):int|string {

        return $short
            ? Data::setType($this->parse(YearFormat::SHORT), Type::T_INT)
            : $this->parse(YearFormat::LONG);

    }

    /**
     * ### Get quarter in year
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\NumInt::ceil() To round fractions up.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::month() To get month.
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Months::QUARTER As month unit.
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Months::calculate() To calculate number of units.
     *
     * @example Get Calendar year.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->quarter();
     *
     * // 3
     * ```
     *
     * @return int Quarter in year.
     */
    public function quarter ():int {

        return NumInt::ceil(($this->month() + 1) / Months::QUARTER->calculate());

    }

    /**
     * ### Get month number
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To change type.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\Month::NUMERIC_SHORT As format type.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\Month::NUMERIC_LONG As format type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT To set type as integer.
     *
     * @example Get Calendar month as integer.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->month();
     *
     * // 7
     * ```
     * @example Get Calendar short month as string.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->month(false);
     *
     * // 07
     * ```
     *
     * @param bool $short [optional] <p>
     * If true, single digit representation of a month as integer will be returned.
     * </p>
     *
     * @return ($short is true ? int : string) Month number.
     */
    public function month (bool $short = true):int|string {

        return $short
            ? Data::setType($this->parse(MonthFormat::NUMERIC_SHORT), Type::T_INT)
            : $this->parse(MonthFormat::NUMERIC_LONG);

    }

    /**
     * ### The week number of the year
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To change type.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\WeekFormat::NUMBER As format type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT To set type as integer.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->week();
     *
     * // 12
     * ```
     *
     * @return int Week number of the year.
     */
    public function week ():int {

        return Data::setType($this->parse(WeekFormat::NUMBER), Type::T_INT);

    }

    /**
     * ### Get week in month
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\NumInt::ceil() To round fractions up.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::day() To day of the month.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::firstDay() To set calendar to first day of specified month.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::dayInWeek() To get day of the week, starting from Sunday with
     * value 0.
     *
     * @example Get Calendar year.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->quarter();
     *
     * // 3
     * ```
     *
     * @return int Week in month.
     */
    public function weekInMonth ():int {

        return NumInt::ceil(($this->day() + self::firstDay()->dayInWeek() - 1) / 7);

    }

    /**
     * ### The day of the month
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To change type.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\DayFormat::NUMERIC_IN_MONTH_SHORT As format type.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\DayFormat::NUMERIC_IN_MONTH_LONG As format type.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\DayFormat::SUFFIX As format type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT To set type as integer.
     *
     * @example Get Calendar day in month as integer.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->day();
     *
     * // 2
     * ```
     * @example Get Calendar short day in month as string.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->day(false);
     *
     * // 02
     * ```
     * @example Get Calendar day in month with suffix.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->day(false, true);
     *
     * // 22nd
     * ```
     *
     * @param bool $short [optional] <p>
     * If true, single digit integer representation of a day in month will be returned.
     * </p>
     * @param bool $suffix [optional] <p>
     * If true, English ordinal suffix for the day of the month will be added.
     * </p>
     *
     * @return ($short is true ? ($suffix is false ? int : string) : string) Day in month number.
     */
    public function day (bool $short = true, bool $suffix = false):int|string {

        return $suffix
            ? ($short
                ? $this->parse(DayFormat::NUMERIC_IN_MONTH_SHORT).$this->parse(DayFormat::SUFFIX)
                : $this->parse(DayFormat::NUMERIC_IN_MONTH_LONG).$this->parse(DayFormat::SUFFIX)
            )
            : ($short
                ? Data::setType($this->parse(DayFormat::NUMERIC_IN_MONTH_SHORT), Type::T_INT)
                : $this->parse(DayFormat::NUMERIC_IN_MONTH_LONG)
            );

    }

    /**
     * ### The day of the year
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To change type.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\DayFormat::NUMBER aS format type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT To set type as integer.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->dayInYear();
     *
     * // 195
     * ```
     *
     * @return int Number of days in the given month.
     */
    public function dayInYear ():int {

        return Data::setType($this->parse(DayFormat::NUMBER), Type::T_INT) + 1;

    }

    /**
     * ### The day number of the week
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To change type.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\DayFormat::NUMERIC_IN_WEEK_ISO_8601 As format type.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\DayFormat::NUMERIC_IN_WEEK As format type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT To set type as integer.
     *
     * @example Get Calendar day in week.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     * use FireHub\Core\Support\Enums\DateTime\Ordinal;
     * use FireHub\Core\Support\Enums\DateTime\WeekDay;
     *
     * $datetime = DateTime::weekDay(Ordinal::LAST, WeekDay::SUNDAY)->dayInWeek();
     *
     * // 0
     * ```
     * @example Get Calendar day in week in iso8601 standard.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     * use FireHub\Core\Support\Enums\DateTime\Ordinal;
     * use FireHub\Core\Support\Enums\DateTime\WeekDay;
     *
     * $datetime = DateTime::weekDay(Ordinal::LAST, WeekDay::SUNDAY)->dayInWeek(true);
     *
     * // 7
     * ```
     *
     * @param bool $iso8601 [optional] <p>
     * If true, Monday will be the first day of the week, with value 1.
     * </p>
     *
     * @return int Day in week.
     */
    public function dayInWeek (bool $iso8601 = false):int {

        return Data::setType(
            $iso8601
                ? $this->parse(DayFormat::NUMERIC_IN_WEEK_ISO_8601)
                : $this->parse(DayFormat::NUMERIC_IN_WEEK), Type::T_INT
        );

    }

    /**
     * ### The day name of the week
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\DayFormat::TEXTUAL_IN_WEEK_SHORT As format type.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\DayFormat::TEXTUAL_IN_WEEK_LONG As format type.
     *
     * @example Get Calendar day name in week.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->dayNameInWeek();
     *
     * // Wednesday
     * ```
     * @example Get Calendar short day in week.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->dayNameInWeek(true);
     *
     * // Wed
     * ```
     *
     * @param bool $short [optional] <p>
     * If true, three digit representation of a day in week will be returned.
     * </p>
     *
     * @return string Day name in week.
     */
    public function dayNameInWeek (bool $short = false):string {

        return $short
            ? $this->parse(DayFormat::TEXTUAL_IN_WEEK_SHORT)
            : $this->parse(DayFormat::TEXTUAL_IN_WEEK_LONG);

    }

    /**
     * ### 24 type hour of the time
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To change type.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\TimeFormat::HOUR_SHORT_24 As format type.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\TimeFormat::HOUR_LONG_24 As format type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT To set type as integer.
     *
     * @example Get Calendar hour in 24-hour type format.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->hour();
     *
     * // 13
     * ```
     * @example Get Calendar hour in 24-hour type format as string format.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->hour(false);
     *
     * // 13
     * ```
     * @example Get Calendar hour with Ante meridiem and Post meridiem suffix.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->hour(false, true);
     *
     * // 13pm
     * ```
     *
     * @param bool $short [optional] <p>
     * If true, single digit representation of an hour in the day will be returned.
     * </p>
     *
     * @return ($short is true ? int : string) Hour of the time number.
     */
    public function hour (bool $short = true):int|string {

        return $short
            ? Data::setType($this->parse(TimeFormat::HOUR_SHORT_24), Type::T_INT)
            : $this->parse(TimeFormat::HOUR_LONG_24);

    }

    /**
     * ### 12 hour type of the time
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To change type.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\TimeFormat::HOUR_SHORT_12 As format type.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\TimeFormat::HOUR_LONG_12 As format type.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\TimeFormat::MERDIEM_LC As format type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT To set type as integer.
     *
     * @example Get Calendar hour in 12-hour type format.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->hourShort();
     *
     * // 1
     * ```
     * @example Get Calendar hour in 12-hour type format as string format.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->hourShort(false);
     *
     * // 01
     * ```
     * @example Get Calendar hour with Ante meridiem and Post meridiem suffix.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->hourShort(true, true);
     *
     * // 1pm
     * ```
     *
     * @param bool $short [optional] <p>
     * If true, single digit integer representation of an hour in month will be returned.
     * </p>
     * @param bool $meridiem [optional] <p>
     * If true, Ante meridiem and Post meridiem suffix for the hour will be added.
     * </p>
     *
     * @return ($short is true ? ($meridiem is false ? int : string) : string) Hour of the time number.
     */
    public function hourShort (bool $short = true, bool $meridiem = false):int|string {

        return $meridiem
            ? ($short
                ? $this->parse(TimeFormat::HOUR_SHORT_12).$this->parse(TimeFormat::MERDIEM_LC)
                : $this->parse(TimeFormat::HOUR_LONG_12).$this->parse(TimeFormat::MERDIEM_LC)
            )
            : ($short
                ? Data::setType($this->parse(TimeFormat::HOUR_SHORT_12), Type::T_INT)
                : $this->parse(TimeFormat::HOUR_SHORT_12)
            );

    }

    /**
     * ### Ante meridiem and Post meridiem suffix for the hour
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\TimeFormat::MERDIEM_LC As format type.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->merdiem();
     *
     * // am
     * ```
     *
     * @return string Ante meridiem (am) or Post meridiem (pm).
     */
    public function merdiem ():string {

        return $this->parse(TimeFormat::MERDIEM_LC);

    }

    /**
     * ### Minute of the time
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To change type.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\TimeFormat::MINUTES As format type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT To set type as integer.
     *
     * @example Get Calendar minute as integer in short format.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->minute();
     *
     * // 5
     * ```
     * @example Get Calendar minute in long format.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->minute(false);
     *
     * // 05
     * ```
     *
     * @param bool $short [optional] <p>
     * If true, single digit integer representation of the minute in hour will be returned.
     * </p>
     *
     * @return ($short is true ? int : string) Minute of the time number.
     */
    public function minute (bool $short = true):int|string {

        return $short
            ? Data::setType($this->parse(TimeFormat::MINUTES), Type::T_INT)
            : $this->parse(TimeFormat::MINUTES);

    }

    /**
     * ### Second of the time
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To change type.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\TimeFormat::SECONDS As format type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT To set type as integer.
     *
     * @example Get Calendar second as integer in short format.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->second();
     *
     * // 7
     * ```
     * @example Get Calendar second in long format.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->second(false);
     *
     * // 07
     * ```
     *
     * @param bool $short [optional] <p>
     * If true, single digit representation of the second in minute will be returned.
     * </p>
     *
     * @return ($short is true ? int : string) Seconds of the time number.
     */
    public function second (bool $short = true):int|string {

        return $short
            ? Data::setType($this->parse(TimeFormat::SECONDS), Type::T_INT)
            : $this->parse(TimeFormat::SECONDS);

    }

    /**
     * ### Millisecond of the time
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To change type.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\TimeFormat::MILLISECONDS As format type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT To set type as integer.
     *
     * @example Get Calendar milliSecond as integer in short format.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->milliSecond();
     *
     * // 45
     * ```
     * @example Get Calendar milliSecond in long format.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->milliSecond(false);
     *
     * // 045
     * ```
     *
     * @param bool $short [optional] <p>
     * If true, single digit integer representation of the millisecond in second will be returned.
     * </p>
     *
     * @return ($short is true ? int : string) Milliseconds of the time number.
     */
    public function milliSecond (bool $short = true):int|string {

        return $short
            ? Data::setType($this->parse(TimeFormat::MILLISECONDS), Type::T_INT)
            : $this->parse(TimeFormat::MILLISECONDS);

    }

    /**
     * ### Microsecond of the time
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To change a type.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\TimeFormat::MICROSECONDS As format type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT To set a type as integer.
     *
     * @example Get Calendar microSecond as integer in short format.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->microSecond();
     *
     * // 1409
     * ```
     * @example Get Calendar microSecond in long format.
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->microSecond(false);
     *
     * // 001409
     * ```
     *
     * @param bool $short [optional] <p>
     * If true, single digit representation of the microsecond in second will be returned.
     * </p>
     *
     * @return ($short is true ? int : string) Microsecond of the time number.
     */
    public function microSecond (bool $short = true):int|string {

        return $short
            ? Data::setType($this->parse(TimeFormat::MICROSECONDS), Type::T_INT)
            : $this->parse(TimeFormat::MICROSECONDS);

    }

}