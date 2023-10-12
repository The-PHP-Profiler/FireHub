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

namespace FireHub\Core\Support\Enums\Data;

use FireHub\Core\Base\ {
    BaseEnum, MasterEnum
};

/**
 * ### Data category enum
 *
 * Data category defines the category of data type.
 * @since 1.0.0
 */
enum Category implements MasterEnum {

    /**
     * ### FireHub base enum class trait
     * @since 1.0.0
     */
    use BaseEnum;

    /**
     * ### Scalar (predefined) category can hold only a single value
     * @since 1.0.0
     */
    case SCALAR;

    /**
     * ### Compound (user-defined) category can hold only multiple values
     * @since 1.0.0
     */
    case COMPOUND;

    /**
     * ### Special type
     * @since 1.0.0
     */
    case SPECIAL;

}