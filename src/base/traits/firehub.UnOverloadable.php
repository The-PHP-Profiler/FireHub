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

namespace FireHub\Core\Base\Traits;

use Error;

/**
 * ### UnOverloadable trait
 * @since 1.0.0
 */
trait UnOverloadable {

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws Error If you try to get property.
     *
     * @return never
     */
    public function __get (string $name):never {

        throw new Error("You are not allowed to get property $name in ".static::class);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws Error If you try to set property.
     *
     * @return never
     */
    public function __set (string $name, mixed $value):never {

        throw new Error("You are not allowed to set property $name to ".static::class);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws Error If property doesn't exist.
     *
     * @return never
     */
    public function __isset (string $name):never {

        throw new Error("Property $name doesn't exist in ".static::class);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws Error If property doesn't exist.
     *
     * @return never
     */
    public function __unset (string $name):never {

        throw new Error("Property $name doesn't exist in ".static::class);

    }

}