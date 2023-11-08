<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2023 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @package FireHub\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\Enums\DateTime;

use FireHub\Core\Base\ {
    BaseEnum, MasterEnum
};
use FireHub\Core\Support\LowLevel\StrSB;

/**
 * ### Week day names notations enum
 * @since 1.0.0
 */
enum WeekDay:int implements MasterEnum {

    /**
     * ### FireHub base enum class trait
     * @since 1.0.0
     */
    use BaseEnum;

    /**
     * @since 1.0.0
     */
    case MONDAY = 1;

    /**
     * @since 1.0.0
     */
    case TUESDAY = 2;

    /**
     * @since 1.0.0
     */
    case WEDNESDAY = 3;

    /**
     * @since 1.0.0
     */
    case THURSDAY = 4;

    /**
     * @since 1.0.0
     */
    case FRIDAY = 5;

    /**
     * @since 1.0.0
     */
    case SATURDAY = 6;

    /**
     * @since 1.0.0
     */
    case SUNDAY = 7;

    /**
     * ### Get week day name
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrSB::capitalize() To capitalize day name.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::toLower() To lowercase day name.
     *
     * @return non-empty-string Day name.
     */
    public function name ():string {

        /** @phpstan-ignore-next-line */
        return StrSB::capitalize(StrSB::toLower($this->name));

    }

    /**
     * ### Get short week day name
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrSB::capitalize() To capitalize day name.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::toLower() To lowercase day name.
     *
     * @return non-empty-string Short day name.
     */
    public function nameShort ():string {

        /** @phpstan-ignore-next-line */
        return StrSB::part($this->name(), 0, 3);

    }

}