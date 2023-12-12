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

namespace FireHub\Core\Support\Enums\DateTime;

use FireHub\Core\Base\ {
    BaseEnum, MasterEnum
};

/**
 * ### Date and time unit names enum
 * @since 1.0.0
 */
enum Unit:string implements MasterEnum {

    /**
     * ### FireHub base enum class trait
     * @since 1.0.0
     */
    use BaseEnum;

    /**
     * @since 1.0.0
     */
    case YEAR = 'year';

    /**
     * @since 1.0.0
     */
    case MONTH = 'month';

    /**
     * @since 1.0.0
     */
    case DAY = 'day';

    /**
     * @since 1.0.0
     */
    case HOUR = 'hour';

    /**
     * @since 1.0.0
     */
    case MINUTE = 'minute';

    /**
     * @since 1.0.0
     */
    case SECOND = 'second';

    /**
     * @since 1.0.0
     */
    case MILLISECOND = 'millisecond';

    /**
     * @since 1.0.0
     */
    case MICROSECOND = 'microsecond';

    /**
     * @since 1.0.0
     */
    case FORTNIGHT = 'fortnight';

    /**
     * @since 1.0.0
     */
    case WEEK = 'week';

    /**
     * @since 1.0.0
     */
    case WEEKDAY = 'weekday';

}