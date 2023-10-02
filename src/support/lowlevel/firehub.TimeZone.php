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

use function date_default_timezone_get;
use function date_default_timezone_set;
use function timezone_abbreviations_list;

/**
 * ### Timezone low level class
 *
 * A time zone is an area which observes a uniform standard time for legal, commercial and social purposes.
 * @since 1.0.0
 */
final class TimeZone {

    /**
     * ### Gets the default timezone used by all date/time functions in a script
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\DateTime\Zone To check for valid timezone.
     *
     * @return \FireHub\Core\Support\Enums\DateTime\Zone|false Timezone, false if timezone doesn't exist.
     */
    public static function getDefaultTimezone ():Zone|false {

        $default = date_default_timezone_get();

        return ($time_zone = Zone::tryFrom($default)) ? $time_zone : false;

    }

    /**
     * ### Sets the default timezone used by all date/time functions in a script
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
     * ### Get associative array containing dst, offset and the timezone name alias
     * @since 1.0.0
     *
     * @return array<string, array<int, array{
     *  dst: bool,
     *  offset: int,
     *  timezone_id: string|null
     * }>> List of timezone abbreviations.
     */
    public static function abbreviationsList ():array {

        return timezone_abbreviations_list();

    }

}