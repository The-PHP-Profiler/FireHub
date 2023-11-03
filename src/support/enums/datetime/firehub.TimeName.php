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
 * ### Time names notations enum
 * @since 1.0.0
 */
enum TimeName:string implements MasterEnum {

    /**
     * ### FireHub base enum class trait
     * @since 1.0.0
     */
    use BaseEnum;

    /**
     * ### Current time
     * @since 1.0.0
     */
    case NOW = 'now';

    /**
     * ### Middle of the day
     * @since 1.0.0
     */
    case NOON = 'noon';

    /**
     * ### Middle of the night
     * @since 1.0.0
     */
    case MIDNIGHT = 'midnight';

}