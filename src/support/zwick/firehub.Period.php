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

use FireHub\Core\Support\Collections\{Collection, Type\Arr\Indexed, Type\Gen};
use FireHub\Core\Support\Enums\DateTime\Format\Predefined;
use Error;

/**
 * ### Date and time period between time and dates
 * @since 1.0.0
 *
 * @api
 */
final class Period extends Zwick {

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\DateTime As parameter.
     *
     * @param \FireHub\Core\Support\Zwick\DateTime $start <p>
     * The start date of the period.
     * </p>
     * @param \FireHub\Core\Support\Zwick\DateTime $end <p>
     * The en date of the period.
     * </p>
     *
     * @return void
     */
    private function __construct (
        private DateTime $start,
        private DateTime $end
    ) {}

    /**
     * ### Create new date and time period
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\DateTime As parameter.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Period;
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime1 = DateTime::from('2020-01-01 12:00:00');
     * $datetime2 = DateTime::from('2021-01-01 12:00:00');
     *
     * $period = Period::between($datetime1, $datetime2);
     *
     * // 2020-01-01 12:00:00 - 2021-01-01 12:00:00
     * ```
     *
     * @param \FireHub\Core\Support\Zwick\DateTime $start <p>
     * The start date of the period.
     * </p>
     * @param \FireHub\Core\Support\Zwick\DateTime $end <p>
     * The en date of the period.
     * </p>
     *
     * @return self New period from dates.
     */
    public static function between (DateTime $start, DateTime $end):self {

        return new self($start, $end);

    }

    /**
     * ### Change start date of the period
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Period;
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime1 = DateTime::from('2020-01-01 12:00:00');
     * $datetime2 = DateTime::from('2021-01-01 12:00:00');
     *
     * $period = Period::between($datetime1, $datetime2);
     *
     * $since = $period->since(DateTime::from('2019-01-01 12:00:00'));
     *
     * // 2019-01-01 12:00:00 - 2021-01-01 12:00:00
     * ```
     *
     * @param \FireHub\Core\Support\Zwick\DateTime $datetime <p>
     * The start date of the period.
     * </p>
     *
     * @return $this This period.
     */
    public function since (DateTime $datetime):self {

        $this->start = $datetime;

        return $this;

    }

    /**
     * ### Change end date of the period
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Period;
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime1 = DateTime::from('2020-01-01 12:00:00');
     * $datetime2 = DateTime::from('2021-01-01 12:00:00');
     *
     * $period = Period::between($datetime1, $datetime2);
     *
     * $until = $period->until(DateTime::from('2022-01-01 12:00:00'));
     *
     * // 2020-01-01 12:00:00 - 2022-01-01 12:00:00
     * ```
     *
     * @uses \FireHub\Core\Support\Zwick\DateTime As parameter.
     *
     * @param \FireHub\Core\Support\Zwick\DateTime $datetime <p>
     * The start date of the period.
     * </p>
     *
     * @return $this This period.
     */
    public function until (DateTime $datetime):self {

        $this->end = $datetime;

        return $this;

    }

    /**
     * ### Reduce the start of the period
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Interval As parameter.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Period;
     * use FireHub\Core\Support\Zwick\DateTime;
     * use FireHub\Core\Support\Zwick\Interval;
     *
     * $datetime1 = DateTime::from('2020-01-01 12:00:00');
     * $datetime2 = DateTime::from('2021-01-01 12:00:00');
     *
     * $period = Period::between($datetime1, $datetime2);
     *
     * $reduceStart = $period->reduceStart(Interval::months(2));
     *
     * // 2019-11-01 12:00:00 - 2021-01-01
     * ```
     *
     * @param \FireHub\Core\Support\Zwick\Interval $interval <p>
     * Interval to use.
     * </p>
     *
     * @return $this This period.
     */
    public function reduceStart (Interval $interval):self {

        $this->start->sub($interval);

        return $this;

    }

    /**
     * ### Extend the start of the period
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Interval As parameter.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Period;
     * use FireHub\Core\Support\Zwick\DateTime;
     * use FireHub\Core\Support\Zwick\Interval;
     *
     * $datetime1 = DateTime::from('2020-01-01 12:00:00');
     * $datetime2 = DateTime::from('2021-01-01 12:00:00');
     *
     * $period = Period::between($datetime1, $datetime2);
     *
     * $extendStart = $period->extendStart(Interval::months(2));
     *
     * // 2020-03-01 12:00:00 - 2021-01-01
     * ```
     *
     * @param \FireHub\Core\Support\Zwick\Interval $interval <p>
     * Interval to use.
     * </p>
     *
     * @return $this This period.
     */
    public function extendStart (Interval $interval):self {

        $this->start->add($interval);

        return $this;

    }

    /**
     * ### Reduce the end of the period
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Interval As parameter.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Period;
     * use FireHub\Core\Support\Zwick\DateTime;
     * use FireHub\Core\Support\Zwick\Interval;
     *
     * $datetime1 = DateTime::from('2020-01-01 12:00:00');
     * $datetime2 = DateTime::from('2021-01-01 12:00:00');
     *
     * $period = Period::between($datetime1, $datetime2);
     *
     * $reduceEnd = $period->reduceEnd(Interval::months(2));
     *
     * // 2020-01-01 12:00:00 - 2020-11-01
     * ```
     *
     * @param \FireHub\Core\Support\Zwick\Interval $interval <p>
     * Interval to use.
     * </p>
     *
     * @return $this This period.
     */
    public function reduceEnd (Interval $interval):self {

        $this->end->sub($interval);

        return $this;

    }

    /**
     * ### Extend the end of the period
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Interval As parameter.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Period;
     * use FireHub\Core\Support\Zwick\DateTime;
     * use FireHub\Core\Support\Zwick\Interval;
     *
     * $datetime1 = DateTime::from('2020-01-01 12:00:00');
     * $datetime2 = DateTime::from('2021-01-01 12:00:00');
     *
     * $period = Period::between($datetime1, $datetime2);
     *
     * $extendEnd = $period->extendEnd(Interval::months(2));
     *
     * // 2020-01-01 12:00:00 - 2021-03-01
     * ```
     *
     * @param \FireHub\Core\Support\Zwick\Interval $interval <p>
     * Interval to use.
     * </p>
     *
     * @return $this This period.
     */
    public function extendEnd (Interval $interval):self {

        $this->end->add($interval);

        return $this;

    }

    /**
     * ### Get the start date of the period
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\DateTime As return-
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Period;
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime1 = DateTime::from('2020-01-01 12:00:00');
     * $datetime2 = DateTime::from('2021-01-01 12:00:00');
     *
     * $period = Period::between($datetime1, $datetime2);
     *
     * $start = $period->getStart();
     *
     * // 2020-01-01 12:00:00
     * ```
     *
     * @return \FireHub\Core\Support\Zwick\DateTime Start date of the period.
     */
    public function getStart ():DateTime {

        return $this->start;

    }

    /**
     * ### Get the end date of the period
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\DateTime As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Period;
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime1 = DateTime::from('2020-01-01 12:00:00');
     * $datetime2 = DateTime::from('2021-01-01 12:00:00');
     *
     * $period = Period::between($datetime1, $datetime2);
     *
     * $end = $period->getEnd();
     *
     * // 2021-01-01 12:00:00
     * ```
     *
     * @return \FireHub\Core\Support\Zwick\DateTime End date of the period.
     */
    public function getEnd ():DateTime {

        return $this->end;

    }

    /**
     * ### Get duration of a period
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\DateTime::difference() To get the difference between two dates.
     * @uses \FireHub\Core\Support\Zwick\Interval As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Period;
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime1 = DateTime::from('2020-01-01 12:00:00');
     * $datetime2 = DateTime::from('2021-01-01 12:00:00');
     *
     * $period = Period::between($datetime1, $datetime2);
     *
     * $duration = $period->duration();
     * ```
     *
     * @uses \FireHub\Core\Support\Zwick\DateTime::difference() To get the difference between two dates.
     *
     * @return \FireHub\Core\Support\Zwick\Interval Duration of a period.
     */
    public function duration ():Interval {

        return $this->end->difference($this->start);

    }

    /**
     * ### Check if provided datetime is inside this period
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\DateTime As parameter.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Period;
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime1 = DateTime::from('2020-01-01 12:00:00');
     * $datetime2 = DateTime::from('2021-01-01 12:00:00');
     *
     * $period = Period::between($datetime1, $datetime2);
     *
     * $in_period = $period->inPeriod(DateTime::from('2020-05-05'));
     *
     * // true
     * ```
     *
     * @return bool True if provided datetime is inside this period, false otherwise.
     */
    public function inPeriod (DateTime $datetime):bool {

        return (($datetime >= $this->start) && ($datetime <= $this->end));

    }

    /**
     * ### Check if period is currently in progress
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Period::inPeriod() To check if provided datetime is inside this period.
     * @uses \FireHub\Core\Support\Zwick\DateTime::now() To create datetime with current date and time.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Period;
     * use FireHub\Core\Support\Zwick\DateTime;
     *
     * $datetime1 = DateTime::from('2020-01-01 12:00:00');
     * $datetime2 = DateTime::from('2021-01-01 12:00:00');
     *
     * $period = Period::between($datetime1, $datetime2);
     *
     * $in_period = $period->inProgress();
     *
     * // false
     * ```
     *
     * @return bool True if period is currently in progress, false otherwise.
     */
    public function inProgress ():bool {

        return $this->inPeriod(DateTime::now());

    }

    /**
     *
     * ### Iterate over a set of dates and times, recurring at regular intervals, over a given period
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::create() As array collection type.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed As return.
     * @uses \FireHub\Core\Support\Zwick\DateTime::from() To create datetime from string.
     * @uses \FireHub\Core\Support\Zwick\DateTime::add() To add an interval to datetime.
     * @uses \FireHub\Core\Support\Zwick\DateTime::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\Predefined::DATE_MICRO_TIME As datetime format.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Period;
     * use FireHub\Core\Support\Zwick\DateTime;
     * use FireHub\Core\Support\Zwick\Interval;
     *
     * $datetime1 = DateTime::from('2020-01-01 12:00:00');
     * $datetime2 = DateTime::from('2021-01-01 12:00:00');
     *
     * $period = Period::iterate($datetime1, $datetime2);
     *
     * $iterate = $period->iterate(Interval::months(1));
     * ```
     *
     * @param \FireHub\Core\Support\Zwick\Interval $every <p>
     * During iteration, this will contain the current date within the period.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Indexed<\FireHub\Core\Support\Zwick\DateTime>
     */
    public function iterate (Interval $every):Indexed {

        return Collection::create(function () use ($every):array {

            $collection = [];

            $datetime = $this->start;

            while ($datetime <= $this->end)
                $collection[] =
                    DateTime::from($datetime->add($every)
                        ->parse(Predefined::DATE_MICRO_TIME));

            return $collection;

        });

    }

    /**
     *
     * ### Iterate over a set of dates and times, recurring at regular intervals, over a given period
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::lazy() As lazy collection type.
     * @uses \FireHub\Core\Support\Collections\Type\Gen As return.
     * @uses \FireHub\Core\Support\Zwick\DateTime::from() To create datetime from string.
     * @uses \FireHub\Core\Support\Zwick\DateTime::add() To add an interval to datetime.
     * @uses \FireHub\Core\Support\Zwick\DateTime::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\Predefined::DATE_MICRO_TIME As datetime format.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\Period;
     * use FireHub\Core\Support\Zwick\DateTime;
     * use FireHub\Core\Support\Zwick\Interval;
     *
     * $datetime1 = DateTime::from('2020-01-01 12:00:00');
     * $datetime2 = DateTime::from('2021-01-01 12:00:00');
     *
     * $period = Period::lazyIterate($datetime1, $datetime2);
     *
     * $iterate = $period->iterate(Interval::months(1));
     * ```
     *
     * @param \FireHub\Core\Support\Zwick\Interval $every <p>
     * During iteration, this will contain the current date within the period.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Gen<int, \FireHub\Core\Support\Zwick\DateTime>
     */
    public function lazyIterate (Interval $every):Gen {

        return Collection::lazy(function () use ($every):\Generator {

            $datetime = $this->start;

            while ($datetime <= $this->end) {
                yield DateTime::from($datetime->add($every)
                    ->parse(Predefined::DATE_MICRO_TIME));
            }

        });

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws Error If method doesn't exist.
     */
    public static function __callStatic (string $method, array $arguments):self {

        throw new Error("Method $method doesn't exist in ".self::class);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\DateTime::parse() To get date and/or time according to given format.
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\Predefined::DATE_MICRO_TIME As datetime format.
     */
    public function __toString ():string {

        return $this->start->parse(Predefined::DATE_MICRO_TIME)
            .' - '
            .$this->end->parse(Predefined::DATE_MICRO_TIME);

    }

}