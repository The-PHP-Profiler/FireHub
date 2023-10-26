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

use FireHub\Core\Base\Traits\ {
    UnCallables, UnCloneable, UnJsonSerializable, UnOverloadable, UnSerializable
};
use FireHub\Core\Support\LowLevel\Constant;
use Error;

/**
 * ### FireHub base class trait
 * @since 1.0.0
 */
trait Base {

    /**
     * ### UnCallables trait
     * @since 1.0.0
     */
    use UnCallables;

    /**
     * ### UnCloneable trait
     * @since 1.0.0
     */
    use UnCloneable;

    /**
     * ### UnJsonSerializable trait
     * @since 1.0.0
     */
    use UnJsonSerializable;

    /**
     * ### UnOverloadable trait
     * @since 1.0.0
     */
    use UnOverloadable;

    /**
     * ### UnSerializable trait
     * @since 1.0.0
     */
    use UnSerializable;

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Constant::value() To return the value of a constant.
     *
     * @throws Error If constant name is empty or constant doesn't exist.
     */
    public static function getConstant (string $name):mixed {

        !empty($name) ?: throw new Error("Constant cannot be empty.");

        try {

            $class = static::class;

            return Constant::value("$class::$name");

        } catch (Error) {

            throw new Error("Cannot get name from constant: $name, constant doesn't exist.");

        }

    }

}