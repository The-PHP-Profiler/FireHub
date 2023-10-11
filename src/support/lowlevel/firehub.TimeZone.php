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

namespace FireHub\Core\Support\LowLevel;

use FireHub\Core\Support\Enums\DateTime\Zone;
use Error;

use function date_default_timezone_get;
use function date_default_timezone_set;
use function timezone_abbreviations_list;

/**
 * ### Timezone low-level class
 *
 * A time zone is an area that observes a uniform standard time for legal, commercial and social purposes.
 * @since 1.0.0
 */
final class TimeZone {

    /**
     * ### Gets the default timezone used by all date/time functions in a script
     *
     * In order of preference, this function returns the default timezone by:
     * - reading the timezone set using the setDefaultTimezone() method (if any)
     * - reading the value of the 'date.timezone' ini option (if set)
     *
     * If none of the above succeed, date_default_timezone_get() will return a default timezone of UTC.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Zone To check for valid timezone.
     *
     * @throws Error If we cannot get the default timezone.
     *
     * @return \FireHub\Core\Support\Enums\DateTime\Zone|false Timezone enum.
     */
    public static function getDefaultTimezone ():Zone|false {

        return ($time_zone = Zone::tryFrom(date_default_timezone_get()))
            ? $time_zone
            : throw new Error('Cannot get default timezone.');

    }

    /**
     * ### Sets the default timezone used by all date/time functions in a script
     *
     * Method sets the default timezone used by all date/time functions.
     * Instead of using this function to set the default timezone in your script, you can also use the INI setting
     * 'date.timezone' to set the default timezone.
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Enums\DateTime\Zone $time_zone <p>
     * The timezone identifier.
     * </p>
     *
     * @return bool False if the timezone_identifier isn't valid, or true otherwise.
     */
    public static function setDefaultTimezone (Zone $time_zone):bool {

        return date_default_timezone_set($time_zone->value);

    }

    /**
     * ### Get an associative array containing dst, offset and the timezone name alias
     * @since 1.0.0
     *
     * @return array<string, array<int, array{
     *  dst: bool,
     *  offset: int,
     *  timezone_id: string|null
     * }>> List of timezone abbreviations.
     */
    public static function abbreviationList ():array {

        return timezone_abbreviations_list();

    }

}