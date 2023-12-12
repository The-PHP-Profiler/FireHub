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
 * ### Ordinal enum
 * @since 1.0.0
 */
enum Ordinal:string implements MasterEnum {

    /**
     * ### FireHub base enum class trait
     * @since 1.0.0
     */
    use BaseEnum;

    /**
     * @since 1.0.0
     */
    case FIRST = 'first';

    /**
     * @since 1.0.0
     */
    case SECOND = 'second';

    /**
     * @since 1.0.0
     */
    case THIRD = 'third';

    /**
     * @since 1.0.0
     */
    case FOURTH = 'fourth';

    /**
     * @since 1.0.0
     */
    case FIFTH = 'fifth';

    /**
     * @since 1.0.0
     */
    case SIXTH = 'sixth';

    /**
     * @since 1.0.0
     */
    case SEVENTH = 'seventh';

    /**
     * @since 1.0.0
     */
    case EIGHTH = 'eighth';

    /**
     * @since 1.0.0
     */
    case NINTH = 'ninth';

    /**
     * @since 1.0.0
     */
    case TENTH = 'tenth';

    /**
     * @since 1.0.0
     */
    case ELEVENTH = 'eleventh';

    /**
     * @since 1.0.0
     */
    case TWELFTH = 'twelfth';

    /**
     * @since 1.0.0
     */
    case NEXT = 'next';

    /**
     * @since 1.0.0
     */
    case LAST = 'last';

    /**
     * @since 1.0.0
     */
    case PREVIOUS = 'previous';

    /**
     * @since 1.0.0
     */
    case THIS = 'this';

}