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

namespace FireHub\Core\Support\Zwick\Helpers;

use FireHub\Core\Base\ {
    Base, Master
};
use FireHub\Core\Support\Enums\DateTime\ {
    DayName, Month, Ordinal, TimeName, WeekDay, Unit\Unit
};
use FireHub\Core\Support\LowLevel\ {
    DataIs, DateAndTime
};

/**
 * ### Parse about any English textual datetime description into a date/time
 * @since 1.0.0
 */
final class Parse implements Master {

    /**
     * ### FireHub base class trait
     * @since 1.0.0
     */
    use Base;

    /**
     * ### Current date/time
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\TimeName::NOW As time name.
     *
     * @return non-empty-string
     */
    public static function now ():string {

        return TimeName::NOW->value;

    }

    /**
     * ### Today's date
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\TimeName::MIDNIGHT As default parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\DayName::TODAY To get the current date.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::string() To check if $at is a string.
     *
     * @param \FireHub\Core\Support\Enums\DateTime\TimeName|non-empty-string $at [optional] <p>
     * Sets time.
     * </p>
     *
     * @return non-empty-string
     */
    public static function today (TimeName|string $at = TimeName::MIDNIGHT):string {

        return DayName::TODAY->value.' '.(DataIs::string($at) ? $at : $at->value);

    }

    /**
     * ### Yesterday's date
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\TimeName::MIDNIGHT As default parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\DayName::YESTERDAY To get yesterday's date.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::string() To check if $at is a string.
     *
     * @param \FireHub\Core\Support\Enums\DateTime\TimeName|non-empty-string $at [optional] <p>
     * Sets time for datetime timestamp.
     * </p>
     *
     * @return non-empty-string
     */
    public static function yesterday (TimeName|string $at = TimeName::MIDNIGHT):string {

        return DayName::YESTERDAY->value.' '.(DataIs::string($at) ? $at : $at->value);

    }

    /**
     * ### Tomorrow's date
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\TimeName::MIDNIGHT As default parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\DayName::TOMORROW To get tomorrow's date.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::string() To check if $at is a string.
     *
     * @param \FireHub\Core\Support\Enums\DateTime\TimeName|non-empty-string $at [optional] <p>
     * Sets time for datetime timestamp.
     * </p>
     *
     * @return non-empty-string
     */
    public static function tomorrow (TimeName|string $at = TimeName::MIDNIGHT):string {

        return DayName::TOMORROW->value.' '.(DataIs::string($at) ? $at : $at->value);

    }

    /**
     * ### Relative date and time
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Unit As parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\TimeName::NOW As default parameter.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::string() To check if $at is a string.
     *
     * @param int $number <p>
     * Number, positive or negative.
     * </p>
     * @param \FireHub\Core\Support\Enums\DateTime\Unit\Unit $unit <p>
     * Unit to use.
     * </p>
     * @param \FireHub\Core\Support\Enums\DateTime\TimeName|non-empty-string $at [optional] <p>
     * Sets time for datetime timestamp.
     * </p>
     *
     * @return non-empty-string
     */
    public static function relative (int $number, Unit $unit, TimeName|string $at = TimeName::NOW):string {

        return $number.' '.$unit->plural().' '.(DataIs::string($at) ? $at : $at->value);

    }

    /**
     * ### First day of specified month
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Month As parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\Month::name() To get month name.
     * @uses \FireHub\Core\Support\Enums\DateTime\TimeName::MIDNIGHT As default parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\Ordinal::FIRST To get the first day of the first of the selected month.
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::formatInteger() To format a Unix timestamp as integer.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::string() To check if $at is a string.
     *
     * @param null|\FireHub\Core\Support\Enums\DateTime\Month $month [optional] <p>
     * Sets month for datetime, or current month if null.
     * </p>
     * @param null|non-negative-int $year [optional] <p>
     * Sets year for datetime, or current year if null.
     * </p>
     * @param \FireHub\Core\Support\Enums\DateTime\TimeName|non-empty-string $at [optional] <p>
     * Sets time for datetime timestamp.
     * </p>
     *
     * @return non-empty-string
     */
    public static function firstDay (?Month $month = null, ?int $year = null, TimeName|string $at = TimeName::MIDNIGHT):string {

        return Ordinal::FIRST->value.
            ' day of '
            .($month ? $month->name() : Month::from(DateAndTime::formatInteger('m') ?: 1)->name())
            .' '
            .($year ?? '')
            .' '
            .(DataIs::string($at) ? $at : $at->value);

    }

    /**
     * ### Last day of specified month
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Month As parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\Month::name() To get month name.
     * @uses \FireHub\Core\Support\Enums\DateTime\TimeName::MIDNIGHT As default parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\Ordinal::LAST To get the last day of the first of the selected month.
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::formatInteger() To format a Unix timestamp as integer.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::string() To check if $at is a string.
     *
     * @param null|\FireHub\Core\Support\Enums\DateTime\Month $month [optional] <p>
     * Sets month for datetime, or current month if null.
     * </p>
     * @param null|non-negative-int $year [optional] <p>
     * Sets year for datetime, or current year if null.
     * </p>
     * @param \FireHub\Core\Support\Enums\DateTime\TimeName|non-empty-string $at [optional] <p>
     * Sets time for datetime timestamp.
     * </p>
     *
     * @return non-empty-string
     */
    public static function lastDay (?Month $month, ?int $year = null, TimeName|string $at = TimeName::MIDNIGHT):string {

        return Ordinal::LAST->value
            .' day of '
            .($month ? $month->name() : Month::from(DateAndTime::formatInteger('m') ?: 1)->name())
            .' '
            .($year ?? '')
            .' '
            .(DataIs::string($at) ? $at : $at->value);

    }

    /**
     * ### Specified weekday name and month
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\WeekDay s parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\Month As parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\Month::name() To get month name.
     * @uses \FireHub\Core\Support\Enums\DateTime\WeekDay::name() To get week day name.
     * @uses \FireHub\Core\Support\Enums\DateTime\TimeName::MIDNIGHT As default parameter.
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::formatInteger() To format a Unix timestamp as integer.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::string() To check if $at is a string.
     *
     * @param \FireHub\Core\Support\Enums\DateTime\Ordinal $ordinal <p>
     * Sets ordinal value for datetime.
     * </p>
     * @param \FireHub\Core\Support\Enums\DateTime\WeekDay $weekday <p>
     * Sets weekday name for datetime.
     * </p>
     * @param null|\FireHub\Core\Support\Enums\DateTime\Month $month [optional] <p>
     * Sets month for datetime, or current month if null.
     * </p>
     * @param null|non-negative-int $year [optional] <p>
     * Sets year for datetime, or current year if null.
     * </p>
     * @param \FireHub\Core\Support\Enums\DateTime\TimeName|non-empty-string $at [optional] <p>
     * Sets time for datetime timestamp.
     * </p>
     *
     * @return non-empty-string
     */
    public static function weekDay (Ordinal $ordinal, WeekDay $weekday, ?Month $month = null, ?int $year = null, TimeName|string $at = TimeName::MIDNIGHT):string {

        return $ordinal->value
            .' '.$weekday->name()
            .' of '
            .($month ? $month->name() : Month::from(DateAndTime::formatInteger('m') ?: 1)->name())
            .' '
            .($year ?? '')
            .' '
            .(DataIs::string($at) ? $at : $at->value);

    }

}