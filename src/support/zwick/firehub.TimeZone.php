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

use FireHub\Core\Support\Enums\DateTime\Zone;
use FireHub\Core\Support\LowLevel\TimeZone as TimeZone_LL;
use Error;

/**
 * ### TimeZone support class
 * @since 1.0.0
 *
 * @api
 */
final class TimeZone extends Zwick {

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Zone As parameter.
     *
     * @param \FireHub\Core\Support\Enums\DateTime\Zone $timezone <p>
     * Choose one of provider TimeZone choices.
     * </p>
     */
    private function __construct (
        private readonly Zone $timezone
    ) {}

    /**
     * ### Create new timezone
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Zone As parameter.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\TimeZone;
     * use FireHub\Core\Support\Enums\DateTime\Zone;
     *
     * TimeZone::create(Zone::AMERICA_NEW_YORK);
     * ```
     *
     * @param \FireHub\Core\Support\Enums\DateTime\Zone $timezone <p>
     * Choose one of provider TimeZone choices.
     * </p>
     *
     * @return self New timezone.
     */
    public static function create (Zone $timezone):self {

        return new self( $timezone);

    }

    /**
     * ### Gets default timezone
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Zone As return.
     * @uses \FireHub\Core\Support\LowLevel\TimeZone::getDefaultTimezone() To get default timezone.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\TimeZone;
     *
     * $timezone = TimeZone::getDefaultTimeZone();
     *
     * $timezone->name;
     *
     * // EUROPE_ZAGREB
     * ```
     *
     * @throws Error If a system could not get the default timezone.
     *
     * @return \FireHub\Core\Support\Enums\DateTime\Zone Current timezone.
     *
     * @see \FireHub\Core\Support\Zwick\TimeZone::setDefaultTimeZone() Sets default timezone.
     */
    public static function getDefaultTimeZone ():Zone {

        return ($timezone = TimeZone_LL::getDefaultTimezone())
            ? $timezone
            : throw new Error('Could not get default timezone.');

    }

    /**
     * ### Sets default timezone
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Zone As parameter.
     * @uses \FireHub\Core\Support\LowLevel\TimeZone::setDefaultTimezone() To set default timezone.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\TimeZone;
     * use FireHub\Core\Support\Enums\DateTime\Zone;
     *
     * TimeZone::setDefaultTimeZone(Zone::AMERICA_NEW_YORK);
     *
     * $timezone = TimeZone::getDefaultTimeZone();
     *
     * $timezone->value;
     *
     * // America/New_York
     * ```
     *
     * @param \FireHub\Core\Support\Enums\DateTime\Zone $time_zone <p>
     * TimeZone enum.
     * </p>
     *
     * @throws Error If a system could not set default timezone.
     *
     * @return bool True if success, false otherwise.
     *
     * @see \FireHub\Core\Support\Zwick\TimeZone::getDefaultTimeZone() Gets default timezone.
     */
    public static function setDefaultTimeZone (Zone $time_zone):bool {

        return (TimeZone_LL::setDefaultTimezone($time_zone))
            ? true
            : throw new Error('Could not set default timezone.');

    }

    /**
     * ### Get timezone
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\TimeZone;
     * use FireHub\Core\Support\Enums\DateTime\Zone;
     *
     * $timezone = TimeZone::create(Zone::AMERICA_NEW_YORK);
     *
     * $timezone->get();
     *
     * // enum(FireHub\Core\Support\Enums\DateTime\Zone::AMERICA_NEW_YORK) : string 'America/NewYork'
     * ```
     * @example You can also get timezone name with a printing TimeZone object.
     * ```php
     * use FireHub\Core\Support\Zwick\TimeZone;
     * use FireHub\Core\Support\Enums\DateTime\Zone;
     *
     * $timezone = TimeZone::create(Zone::AMERICA_NEW_YORK);
     *
     * $timezone;
     *
     * // America/New_York
     * ```
     *
     * @return \FireHub\Core\Support\Enums\DateTime\Zone Timezone enum.
     */
    public function get ():Zone {

        return $this->timezone;

    }

    /**
     * ### Offset in seconds between selected timezone and Coordinated Universal Time(UTC)
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\TimeZone::abbreviationList() To get an associative array containing dst,
     * offset and the timezone name alias.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Zwick\TimeZone;
     * use FireHub\Core\Support\Enums\DateTime\Zone;
     *
     * $timezone = TimeZone::create(Zone::AMERICA_NEW_YORK);
     *
     * $timezone->offsetGMT();
     *
     * // -18000
     * ```
     * @example With daylight-saving time option.
     * ```php
     * use FireHub\Core\Support\Zwick\TimeZone;
     * use FireHub\Core\Support\Enums\DateTime\Zone;
     *
     * $timezone = TimeZone::create(Zone::AMERICA_NEW_YORK);
     *
     * $timezone->offsetGMT(true);
     *
     * // -14400
     * ```
     *
     * @param bool $dst [optional] <p>
     * Filter daylight saving time abbreviations.
     * </p>
     *
     * @throws Error If a system could not get timezone offset.
     *
     * @return int Time zone offset in seconds between selected timezone and Coordinated Universal Time(UTC).
     */
    public function offset (bool $dst = false):int {

        foreach (TimeZone_LL::abbreviationList() as $zones)
            foreach ($zones as $zone)
                if ($zone['timezone_id'] === $this->timezone->value && $zone['dst'] === $dst)
                    return $zone['offset'];

        return throw new Error('Could not get timezone offset.');

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
     */
    public function __toString ():string {

        return $this->timezone->value;

    }

}