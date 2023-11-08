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
use FireHub\Core\Support\LowLevel\StrSB;

/**
 * ### Month names enum
 * @since 1.0.0
 */
enum Month:int implements MasterEnum {

    /**
     * ### FireHub base enum class trait
     * @since 1.0.0
     */
    use BaseEnum;

    /**
     * @since 1.0.0
     */
    case JANUARY = 1;

    /**
     * @since 1.0.0
     */
    case FEBRUARY = 2;

    /**
     * @since 1.0.0
     */
    case MARCH = 3;

    /**
     * @since 1.0.0
     */
    case APRIL = 4;

    /**
     * @since 1.0.0
     */
    case MAY = 5;

    /**
     * @since 1.0.0
     */
    case JUNE = 6;

    /**
     * @since 1.0.0
     */
    case JULY = 7;

    /**
     * @since 1.0.0
     */
    case AUGUST = 8;

    /**
     * @since 1.0.0
     */
    case SEPTEMBER = 9;

    /**
     * @since 1.0.0
     */
    case OCTOBER = 10;

    /**
     * @since 1.0.0
     */
    case NOVEMBER = 11;

    /**
     * @since 1.0.0
     */
    case DECEMBER = 12;

    /**
     * ### Get month name
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrSB::capitalize() To capitalize month name.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::toLower() To lowercase month name.
     *
     * @return non-empty-string Month name.
     */
    public function name ():string {

        /** @phpstan-ignore-next-line */
        return StrSB::capitalize(StrSB::toLower($this->name));

    }

    /**
     * ### Get short month name
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrSB::capitalize() To capitalize month name.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::toLower() To lowercase month name.
     *
     * @return non-empty-string Month name.
     */
    public function nameShort ():string {

        /** @phpstan-ignore-next-line */
        return StrSB::part($this->name(), 0, 3);

    }

}