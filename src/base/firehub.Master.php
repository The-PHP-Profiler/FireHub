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

use FireHub\Core\Support\Contracts\ {
    Cloneable, JsonSerializable, Overloadable, Serializable
};

/**
 * ### FireHub master interface
 * @since 1.0.0
 */
interface Master extends Prime, Cloneable, JsonSerializable, Overloadable, Serializable {

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