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

use FireHub\Core\Base\ {
    Base, Master
};
use FireHub\Core\Support\Contracts\Stringable;
use FireHub\Core\Support\Enums\ {
    Data\Type, DateTime\Format\Predefined, Side
};
use FireHub\Core\Support\LowLevel\ {
    Data, DataIs, DateAndTime, StrSB
};
use Error, ValueError;

/**
 * ### Zwick abstract class
 * @since 1.0.0
 */
abstract class Zwick implements Master, Stringable {

    /**
     * ### FireHub base class trait
     * @since 1.0.0
     */
    use Base;

    /**
     * ### Create date\time string according to a specified format
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Format\Predefined As parameter.
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::get() To get date/time information.
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::parseFromFormat() To get information about a given date
     * formatted according to the specified format.
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::microtime() To get current Unix microseconds.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::string() To find whether the type of variable is a string.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::pad() To pad a string to a certain length with another string.
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To convert data to type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_STRING As data type.
     * @uses \FireHub\Core\Support\Enums\Side::LEFT As side enum.
     *
     * @param \FireHub\Core\Support\Enums\DateTime\Format\Predefined|non-empty-string $format <p>
     * Format accepted by date with some extras.
     * </p>
     * @param non-empty-string $datetime <p>
     * A date/time string.
     * </p>
     *
     * @throws Error If date\time $datetime has a wrong format.
     * @throws ValueError If $datetime contains NULL-bytes.
     *
     * @return non-empty-string Date from parsed data.
     */
    final protected static function dateFromFormat (Predefined|string $format, string $datetime):string {

        $current_datetime = DateAndTime::get();

        $parse = DateAndTime::parseFromFormat(
            DataIs::string($format) ? $format : $format->value, $datetime
        )
            ?: throw new Error("Date\Time $datetime has wrong format");

        $year = $parse['year'] !== false
            ? $parse['year']
            : $current_datetime['year'];

        $month = $parse['month'] !== false
            ? $parse['month']
            : $current_datetime['mon'];

        $day = $parse['day'] !== false
            ? $parse['day']
            : $current_datetime['mday'];

        $hour = $parse['hour'] !== false
            ? $parse['hour']
            : ($parse['year'] === false ? $current_datetime['hours'] : 0);

        $minute = $parse['minute'] !== false
            ? $parse['minute']
            : ($parse['year'] === false ? $current_datetime['minutes'] : 0);

        $second = $parse['second'] !== false
            ? $parse['second']
            : ($parse['year'] === false ? $current_datetime['seconds'] : 0);

        $microsecond = StrSB::pad(
            Data::setType(
                $parse['fraction'] !== false
                    ? $parse['fraction'] * 1000000
                    : ($parse['year'] === false && DateAndTime::microtime()),
                Type::T_STRING),
            6, '0', Side::LEFT
        );

        /** @phpstan-ignore-next-line */
        return "$year-$month-$day $hour:$minute:$second.$microsecond";

    }

    /**
     * ### Format fractions as string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrSB::part() To get part of string.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::pad() To pad a string to a certain length with another string.
     * @uses \FireHub\Core\Support\LowLevel\Data::setType() To convert data to type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_STRING As data type.
     * @uses \FireHub\Core\Support\Enums\Side::LEFT As side.
     *
     * @param int $fractions <p>
     * Fractions to format.
     * </p>
     * @param bool $milliseconds [optional] <p>
     * If true, the method will return formatted milliseconds instead of microseconds.
     * </p>
     *
     * @return non-empty-string Formatted fractions of second.
     */
    final protected function formatFraction (int $fractions, bool $milliseconds = false):string {

        /** @phpstan-ignore-next-line */
        return StrSB::part(
            StrSB::pad(
                Data::setType($fractions, Type::T_STRING),
                6, '0', Side::LEFT
            ),
            0, $milliseconds ? 3 : 6
        );

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws Error If method doesn't exist.
     */
    public function __call (string $method, array $arguments):mixed {

        throw new Error("Method $method doesn't exist in ".static::class);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @return self New Zwick class.
     */
    abstract public static function __callStatic (string $method, array $arguments):self;

}