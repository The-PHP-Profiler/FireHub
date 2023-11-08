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

use FireHub\Core\Support\Collections\ {
    Collection, Type\Arr\Associative
};
use FireHub\Core\Support\Enums\DateTime\Unit\ {
    Basic, Calculable, Days, Microseconds, Months, Years
};
use FireHub\Core\Support\LowLevel\ {
    Arr, StrSB
};
use Error;

/**
 * ### Date and time interval of time
 *
 * A date interval stores fixed amount of time (in years, months, days, hours...).
 * @since 1.0.0
 *
 * @method static self millenniums (int $number) ### Create an interval specifying a number of millenniums
 * @method static self centuries (int $number) ### Create an interval specifying a number of centuries
 * @method static self decades (int $number) ### Create an interval specifying a number of decades
 * @method static self years (int $number) ### Create an interval specifying a number of years
 * @method static self quarters (int $number) ### Create an interval specifying a number of quarters
 * @method static self months (int $number) ### Create an interval specifying a number of months
 * @method static self fortnights (int $number) ### Create an interval specifying a number of fortnights
 * @method static self weeks (int $number) ### Create an interval specifying a number of weeks
 * @method static self days (int $number) ### Create an interval specifying a number of days
 * @method static self hours (int $number) ### Create an interval specifying a number of hours
 * @method static self minutes (int $number) ### Create an interval specifying a number of minutes
 * @method static self seconds (int $number) ### Create an interval specifying a number of seconds
 * @method static self milliseconds (int $number) ### Create an interval specifying a number of milliseconds
 * @method static self microSeconds (int $number) ### Create an interval specifying a number of microseconds
 * @method int getYears () ### Get years from an interval
 * @method int getMonths () ### Get months from an interval
 * @method int getDays () ### Get days from an interval
 * @method int getHours () ### Get hours from an interval
 * @method int getMinutes () ### Get minutes from an interval
 * @method int getSeconds () ### Get seconds from an interval
 * @method int getMicroSeconds () ### Get microseconds from an interval
 * @method self addMillenniums (int $number) ### Add given number of millenniums to the current interval
 * @method self addCenturies (int $number) ### Add given number of centuries to the current interval
 * @method self addDecades (int $number) ### Add given number of decades to the current interval
 * @method self addYears (int $number) ### Add given number of years to the current interval
 * @method self addQuarters (int $number) ### Add given number of quarters to the current interval
 * @method self addMonths (int $number) ### Add given number of months to the current interval
 * @method self addFortnights (int $number) ### Add given number of fortnights to the current interval
 * @method self addWeeks (int $number) ### Add given number of weeks to the current interval
 * @method self addDays (int $number) ### Add given number of days to the current interval
 * @method self addHours (int $number) ### Add given number of hours to the current interval
 * @method self addMinutes (int $number) ### Add given number of minutes to the current interval
 * @method self addSeconds (int $number) ### Add given number of seconds to the current interval
 * @method self addMilliSeconds (int $number) ### Add given number of milliseconds to the current interval
 * @method self addMicroSeconds (int $number) ### Add given number of microseconds to the current interval
 * @method self subMillenniums (int $number) ### Sub given number of millenniums to the current interval
 * @method self subCenturies (int $number) ### Sub given number of centuries to the current interval
 * @method self subDecades (int $number) ### Sub given number of decades to the current interval
 * @method self subYears (int $number) ### Sub given number of years to the current interval
 * @method self subQuarters (int $number) ### Sub given number of quarters to the current interval
 * @method self subMonths (int $number) ### Sub given number of months to the current interval
 * @method self subFortnights (int $number) ### Sub given number of fortnights to the current interval
 * @method self subWeeks (int $number) ### Sub given number of weeks to the current interval
 * @method self subDays (int $number) ### Sub given number of days to the current interval
 * @method self subHours (int $number) ### Sub given number of hours to the current interval
 * @method self subMinutes (int $number) ### Sub given number of minutes to the current interval
 * @method self subSeconds (int $number) ### Sub given number of seconds to the current interval
 * @method self subMilliSeconds (int $number) ### Sub given number of milliseconds to the current interval
 * @method self subMicroSeconds (int $number) ### Sub given number of microseconds to the current interval
 *
 * @api
 */
final class Interval extends Zwick {

    /**
     * ### Interval units
     * @since 1.0.0
     *
     * @var \FireHub\Core\Support\Collections\Type\Arr\Associative<string, int>
     */
    private Associative $units;

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::create() As array collection type.
     *
     * @param array<\FireHub\Core\Support\Enums\DateTime\Unit\Basic> $basic_units <p>
     * List of basic date\time units.
     * </p>
     * @param array<\FireHub\Core\Support\Enums\DateTime\Unit\Calculable> $calculable_units <p>
     * List of calculable date\time units.
     * </p>
     *
     * @return void
     */
    private function __construct (
        private readonly array $basic_units,
        private readonly array $calculable_units
    ) {

        $this->units = Collection::create()->associative(function ():array {

            foreach ($this->basic_units as $basic_unit)
                $units[$basic_unit->value] = 0;

            return $units ?? [];

        });

    }

    /**
     * ### Get unit value from interval
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Basic As parameter.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Interval;
     *
     * $interval = Interval::days(10)->getDays();
     *
     * // 10
     * ```
     *
     * @param \FireHub\Core\Support\Enums\DateTime\Unit\Basic $unit <p>
     * Unit to add value.
     * </p>
     *
     * @return int Unit value.
     */
    public function get (Basic $unit):int {

        return $this->units->get($unit->value);

    }

    /**
     * ### Add unit value to interval
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Basic As parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Calculable As parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Calculable::parent() To get a parent enum case.
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Calculable::calculate() To calculate the number of units.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Interval;
     * use FireHub\Core\Support\Enums\DateTime\Unit\Days;
     *
     * $interval = Interval::days(10)->plus(Days::WEEK, 1);
     *
     * // 17
     * ```
     *
     * @param \FireHub\Core\Support\Enums\DateTime\Unit\Basic|\FireHub\Core\Support\Enums\DateTime\Unit\Calculable $unit <p>
     * Unit to add value.
     * </p>
     * @param int $number <p>
     * Value of unit.
     * </p>
     *
     * @return $this This interval.
     */
    public function plus (Basic|Calculable $unit, int $number):self {

        match (true) {
            $unit instanceof Calculable => $this->units[$unit->parent()->value] += ($unit->calculate() * $number),
            default => $this->units[$unit->value] += $number
        };

        return $this;

    }

    /**
     * ### Subtract units value to interval
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Basic As parameter.
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Calculable As parameter.
     * @uses \FireHub\Core\Support\Zwick\Interval::add() Add unit value.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Interval;
     * use FireHub\Core\Support\Enums\DateTime\Unit\Basic;
     *
     * $interval = Interval::days(10)->minus(Basic::DAY, 2);
     *
     * // 8
     * ```
     *
     * @param \FireHub\Core\Support\Enums\DateTime\Unit\Basic|\FireHub\Core\Support\Enums\DateTime\Unit\Calculable $unit <p>
     * Unit to add value.
     * </p>
     * @param int $number <p>
     * Value of unit.
     * </p>
     *
     * @return $this This interval.
     */
    public function minus (Basic|Calculable $unit, int $number):self {

        return $this->plus($unit, -$number);

    }

    /**
     * ### Convert unit name to unit enum
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Basic As return.
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Calculable As return.
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Basic::plural() To get plural from an enum case.
     * @uses \FireHub\Core\Support\Enums\DateTime\Unit\Calculable::plural() To get plural from an enum case.
     *
     * @param non-empty-string $name <p>
     * Unit name.
     * </p>
     *
     * @throws Error If unit $name doesn't exist.
     *
     * @return \FireHub\Core\Support\Enums\DateTime\Unit\Basic|\FireHub\Core\Support\Enums\DateTime\Unit\Calculable
     * Unit enum.
     */
    private function toUnit (string $name):Basic|Calculable {

        foreach ($this->basic_units as $basic_unit)
            if ($name === $basic_unit->plural()) return $basic_unit;

        foreach ($this->calculable_units as $calculable_unit)
            if ($name === $calculable_unit->plural()) return $calculable_unit;

        throw new Error("Unit $name doesn't exist.");

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Interval::toUnit() To convert unit mame to unit enum.
     * @uses \FireHub\Core\Support\Zwick\Interval::get() To get units value from interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::plus() To add units value to interval.
     * @uses \FireHub\Core\Support\Zwick\Interval::minus() To sub units value to interval.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::toLower() To make a string lowercase.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::part() To get part of string.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::startsWith() To check if a string starts with a given value.
     *
     * @param non-empty-string $method <p>
     * Method name.
     * </p>
     * @param array<array-key, int> $arguments <p>
     * List of arguments.
     * </p>
     *
     * @throws Error If unit or called method $name doesn't exist.
     *
     * @return $this|int This interval or unit value.
     *
     * @phpstan-ignore-next-line
     */
    public function __call (string $method, array $arguments):self|int {

        $method = StrSB::toLower($method);

        $unit = $this->toUnit(
            ($part = StrSB::part($method, 3)) !== ''
                ? $part
                : throw new Error("Unit name doesn't exist.")
        );

        return match (true) {
            StrSB::startsWith('get', $method) && $unit instanceof Basic => $this->get($unit),
            StrSB::startsWith('add', $method) => $this->plus($unit, ...$arguments),
            StrSB::startsWith('sub', $method) => $this->minus($unit, ...$arguments),
            default => throw new Error("Method $method doesn't exist.")
        };

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrSB::capitalize() To make a first character of string uppercased.
     * @uses \FireHub\Core\Support\LowLevel\Arr::merge() To merge all calculable enums.
     */
    public static function __callStatic (string $method, array $arguments):self {

        $add_method = 'add'.StrSB::capitalize($method);

        return (new self(Basic::cases(), Arr::merge(
            Years::cases(),
            Months::cases(),
            Days::cases(),
            Microseconds::cases()
        )))->$add_method(...$arguments);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative::join() To join collection items with a string.
     */
    public function __toString ():string {

        return $this->units->join(';', ' and ', symbol: '=');

    }

}