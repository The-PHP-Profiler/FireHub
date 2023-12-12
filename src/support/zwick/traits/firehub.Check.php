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

use FireHub\Core\Support\Zwick\DateTime;
use FireHub\Core\Support\Enums\Data\Type;
use FireHub\Core\Support\Enums\DateTime\ {
    Format\Format, Format\Predefined as PredefinedFormat, Format\Year as YearFormat, Format\TimeZone as TimeZoneFormat,
    Month, WeekDay
};
use FireHub\Core\Support\LowLevel\Data;

/**
 * ### Check information about current date\time
 * @since 1.0.0
 */
trait Check {

    /**
     * ### Check whether it's a leap year
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To change a type.
     * @uses \FireHub\Core\Support\Zwick\Traits\Check::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\Year::LEAP As format type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_BOOL To set a type as boolean.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->leapYear();
     *
     * // false
     * ```
     *
     * @return bool True if is leap year, false otherwise.
     */
    public function leapYear ():bool {

        return Data::setType($this->parse(YearFormat::LEAP), Type::T_BOOL);

    }

    /**
     * ### Check if DateTime is in January
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::month() To get month number.
     * @uses \FireHub\Core\Support\Enums\DateTime\Month::JANUARY As month name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isJanuary();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime being in January, false otherwise.
     */
    public function isJanuary ():bool {

        return $this->month() === Month::JANUARY->value;

    }

    /**
     * ### Check if DateTime is in February
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::month() To get month number.
     * @uses \FireHub\Core\Support\Enums\DateTime\Month::FEBRUARY As month name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isFebruary();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime being in February, false otherwise.
     */
    public function isFebruary ():bool {

        return $this->month() === Month::FEBRUARY->value;

    }

    /**
     * ### Check if DateTime is in March
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::month() To get month number.
     * @uses \FireHub\Core\Support\Enums\DateTime\Month::MARCH As month name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isMarch();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime being in March, false otherwise.
     */
    public function isMarch ():bool {

        return $this->month() === Month::MARCH->value;

    }

    /**
     * ### Check if DateTime is in April
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::month() To get month number.
     * @uses \FireHub\Core\Support\Enums\DateTime\Month::APRIL As month name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isApril();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime being in April, false otherwise.
     */
    public function isApril ():bool {

        return $this->month() === Month::APRIL->value;

    }

    /**
     * ### Check if DateTime is in May
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::month() To get month number.
     * @uses \FireHub\Core\Support\Enums\DateTime\Month::MAY As month name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isMay();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime is in May, false otherwise.
     */
    public function isMay ():bool {

        return $this->month() === Month::MAY->value;

    }

    /**
     * ### Check if DateTime is in June
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::month() To get month number.
     * @uses \FireHub\Core\Support\Enums\DateTime\Month::JUNE As month name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isJune();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime being in June, false otherwise.
     */
    public function isJune ():bool {

        return $this->month() === Month::JUNE->value;

    }

    /**
     * ### Check if DateTime is in July
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::month() To get month number.
     * @uses \FireHub\Core\Support\Enums\DateTime\Month::JULY As month name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isJuly();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime being in July, false otherwise.
     */
    public function isJuly ():bool {

        return $this->month() === Month::JULY->value;

    }

    /**
     * ### Check if DateTime is in August
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::month() To get month number.
     * @uses \FireHub\Core\Support\Enums\DateTime\Month::AUGUST As month name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isAugust();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime being in August, false otherwise.
     */
    public function isAugust ():bool {

        return $this->month() === Month::AUGUST->value;

    }

    /**
     * ### Check if DateTime is in September
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::month() To get month number.
     * @uses \FireHub\Core\Support\Enums\DateTime\Month::SEPTEMBER As month name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isSeptember();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime being in September, false otherwise.
     */
    public function isSeptember ():bool {

        return $this->month() === Month::SEPTEMBER->value;

    }

    /**
     * ### Check if DateTime is in October
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::month() To get month number.
     * @uses \FireHub\Core\Support\Enums\DateTime\Month::OCTOBER As month name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isOctober();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime being in October, false otherwise.
     */
    public function isOctober ():bool {

        return $this->month() === Month::OCTOBER->value;

    }

    /**
     * ### Check if DateTime is in November
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::month() To get month number.
     * @uses \FireHub\Core\Support\Enums\DateTime\Month::NOVEMBER As month name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isNovember();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime being in November, false otherwise.
     */
    public function isNovember ():bool {

        return $this->month() === Month::NOVEMBER->value;

    }

    /**
     * ### Check if DateTime is in December
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::month() To get month number.
     * @uses \FireHub\Core\Support\Enums\DateTime\Month::DECEMBER As month name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isDecember();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime being in December, false otherwise.
     */
    public function isDecember ():bool {

        return $this->month() === Month::DECEMBER->value;

    }

    /**
     * ### Check if DateTime is today
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Check::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Zwick\DateTime::today() To set DateTime to current date.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\Predefined::DATE As date format.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isToday();
     *
     * // true
     * ```
     *
     * @return bool True if is DateTime being today, false otherwise.
     */
    public function isToday ():bool {

        return $this->parse(PredefinedFormat::DATE) === DateTime::today()->parse(PredefinedFormat::DATE);

    }

    /**
     * ### Check if DateTime is yesterday
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Check::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Zwick\DateTime::yesterday() To set DateTime to yesterday date.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\Predefined::DATE As date format.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isYesterday();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime being yesterday, false otherwise.
     */
    public function isYesterday ():bool {

        return $this->parse(PredefinedFormat::DATE) === DateTime::yesterday()->parse(PredefinedFormat::DATE);

    }

    /**
     * ### Check if DateTime is tomorrow
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Check::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Zwick\DateTime::tomorrow() To set DateTime to tomorrow date.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\Predefined::DATE As date format.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isTomorrow();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime being tomorrow, false otherwise.
     */
    public function isTomorrow ():bool {

        return $this->parse(PredefinedFormat::DATE) === DateTime::tomorrow()->parse(PredefinedFormat::DATE);

    }

    /**
     * ### Check if DateTime is the first day of the month
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::day() To get day of the month.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isFirstOfMonth();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime being the first day of the month, false otherwise.
     */
    public function isFirstOfMonth ():bool {

        return $this->day() === 1;

    }

    /**
     * ### Check if DateTime is the last day of the month
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::day() To get day of the month.
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::month() To get month number.
     * @uses \FireHub\Core\Support\Zwick\DateTime::lastDay() To set datetime to last day of specified month.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\Month As month name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isLastOfMonth();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime being the last day of the month, false otherwise.
     */
    public function isLastOfMonth ():bool {

        return $this->day() === DateTime::lastDay(Month::from($this->month()))->day();

    }

    /**
     * ### Check if DateTime is the first day of the year
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::dayInYear() To get day of the year.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isFirstOfYear();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime being the first day of the year, false otherwise.
     */
    public function isFirstOfYear ():bool {

        return $this->dayInYear() === 1;

    }

    /**
     * ### Check if DateTime is the last day of the year.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::dayInYear() To get day of the year.
     * @uses \FireHub\Core\Support\Zwick\DateTime::lastDay() To set datetime to last day of specified month.
     * @uses \FireHub\Core\Support\Enums\DateTime\Month::DECEMBER As month name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isLastOfYear();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime being the last day of the year, false otherwise.
     */
    public function isLastOfYear ():bool {

        return $this->dayInYear() === DateTime::lastDay(Month::DECEMBER)->dayInYear();

    }

    /**
     * ### Check if DateTime is in Monday
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::dayInWeek() To get the day number of the week.
     * @uses \FireHub\Core\Support\Enums\DateTime\WeekDay::MONDAY As weekday name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isMonday();
     *
     * // false
     * ```
     *
     * @return bool True if is Monday, false otherwise.
     */
    public function isMonday ():bool {

        return $this->dayInWeek(true) === WeekDay::MONDAY->value;

    }

    /**
     * ### Check if DateTime is in Tuesday
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::dayInWeek() To get the day number of the week.
     * @uses \FireHub\Core\Support\Enums\DateTime\WeekDay::TUESDAY As weekday name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isTuesday();
     *
     * // false
     * ```
     *
     * @return bool True if is Tuesday, false otherwise.
     */
    public function isTuesday ():bool {

        return $this->dayInWeek(true) === WeekDay::TUESDAY->value;

    }

    /**
     * ### Check if DateTime is in Wednesday
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::dayInWeek() To get the day number of the week.
     * @uses \FireHub\Core\Support\Enums\DateTime\WeekDay::WEDNESDAY As weekday name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isWednesday();
     *
     * // false
     * ```
     *
     * @return bool True if is Wednesday, false otherwise.
     */
    public function isWednesday ():bool {

        return $this->dayInWeek(true) === WeekDay::WEDNESDAY->value;

    }

    /**
     * ### Check if DateTime is in Thursday
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::dayInWeek() To get the day number of the week.
     * @uses \FireHub\Core\Support\Enums\DateTime\WeekDay::THURSDAY As weekday name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isThursday();
     *
     * // false
     * ```
     *
     * @return bool True if is Thursday, false otherwise.
     */
    public function isThursday ():bool {

        return $this->dayInWeek(true) === WeekDay::THURSDAY->value;

    }

    /**
     * ### Check if DateTime is in Friday
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::dayInWeek() To get the day number of the week.
     * @uses \FireHub\Core\Support\Enums\DateTime\WeekDay::FRIDAY As weekday name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isFriday();
     *
     * // true
     * ```
     *
     * @return bool True if is Friday, false otherwise.
     */
    public function isFriday ():bool {

        return $this->dayInWeek(true) === WeekDay::FRIDAY->value;

    }

    /**
     * ### Check if DateTime is in Saturday
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::dayInWeek() To get the day number of the week.
     * @uses \FireHub\Core\Support\Enums\DateTime\WeekDay::SATURDAY As weekday name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isSaturday();
     *
     * // false
     * ```
     *
     * @return bool True if is Saturday, false otherwise.
     */
    public function isSaturday ():bool {

        return $this->dayInWeek(true) === WeekDay::SATURDAY->value;

    }

    /**
     * ### Check if DateTime is in Sunday
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::dayInWeek() To get the day number of the week.
     * @uses \FireHub\Core\Support\Enums\DateTime\WeekDay::SUNDAY As weekday name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isSunday();
     *
     * // false
     * ```
     *
     * @return bool True if is Sunday, false otherwise.
     */
    public function isSunday ():bool {

        return $this->dayInWeek(true) === WeekDay::SUNDAY->value;

    }

    /**
     * ### Check if DateTime is in weekday
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Check::isWeekend() To check if datetime is in weekend.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isWeekDay();
     *
     * // true
     * ```
     *
     * @return bool True if is weekend, false otherwise.
     */
    public function isWeekDay ():bool {

        return !$this->isWeekend();

    }

    /**
     * ### Check if datetime is in weekend
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::dayInWeek() To get the day number of the week.
     * @uses \FireHub\Core\Support\Enums\DateTime\WeekDay::SATURDAY As weekday name.
     * @uses \FireHub\Core\Support\Enums\DateTime\WeekDay::SUNDAY As weekday name.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isWeekend();
     *
     * // false
     * ```
     *
     * @return bool True if is weekend, false otherwise.
     */
    public function isWeekend ():bool {

        return $this->dayInWeek(true) === WeekDay::SATURDAY->value || $this->dayInWeek(true) === WeekDay::SUNDAY->value;

    }

    /**
     * ### Check if DateTime is at noon
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::hour() To get 24 type hours of the time.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isNoon();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime is at noon, false otherwise.
     */
    public function isNoon ():bool {

        return $this->hour() === 12;

    }

    /**
     * ### Check if DateTime is at midnight
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Traits\Get::hour() To get 24 type hours of the time.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->isMidnight();
     *
     * // false
     * ```
     *
     * @return bool True if is DateTime being at midnight, false otherwise.
     */
    public function isMidnight ():bool {

        return $this->hour() === 0;

    }

    /**
     * ### Check for daylight saving time
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To change a type.
     * @uses \FireHub\Core\Support\Zwick\Traits\Check::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\TimeZone::DAYLIGHT_SAVING_TIME As format type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_BOOL To set a type as boolean.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime = DateTime::now()->daylightSavingTime();
     *
     * // true
     * ```
     *
     * @return bool Whether the date timezone is in daylight-saving time.
     */
    public function daylightSavingTime ():bool {

        return Data::setType($this->parse(TimeZoneFormat::DAYLIGHT_SAVING_TIME), Type::T_BOOL);

    }

    /**
     * ### Gets date and/or time according to given format
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Enums\DateTime\Format\Format|non-empty-string $format <p>
     * Format enum or string accepted by date().
     * </p>
     *
     * @return string Formatted datetime.
     */
    abstract public function parse (Format|string $format):string;

}