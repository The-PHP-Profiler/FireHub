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
 * ### Day names notations enum
 * @since 1.0.0
 */
enum DayName:string implements MasterEnum {

    /**
     * ### FireHub base enum class trait
     * @since 1.0.0
     */
    use BaseEnum;

    /**
     * ### Date is today
     * @since 1.0.0
     */
    case TODAY = 'today';

    /**
     * ### Date is yesterday
     * @since 1.0.0
     */
    case YESTERDAY = 'yesterday';

    /**
     * ### Date is tomorrow
     * @since 1.0.0
     */
    case TOMORROW = 'tomorrow';

}