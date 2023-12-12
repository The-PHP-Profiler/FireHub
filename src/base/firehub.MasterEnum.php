<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2023 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @package FireHub\Core\Base
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Base;

/**
 * ### FireHub master enum interface
 * @since 1.0.0
 */
interface MasterEnum extends Prime {

    /**
     * ### Get value from enum name
     * @since 1.0.0
     *
     * @param non-empty-string $name <p>
     * Enum name.
     * </p>
     *
     * @return ?static Enum from enum name, or null if enum not found.
     */
    public static function fromName (string $name):?static;

    /**
     * ### Get value from constant name
     * @since 1.0.0
     *
     * @param non-empty-string $name <p>
     * Constant name.
     * </p>
     *
     * @return mixed Value from constant name.
     */
    public static function getConstant (string $name):mixed;

}