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
 * ### UnCallables trait
 * @since 1.0.0
 */
trait UnCallables {

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws Error If method doesn't exist.
     */
    public function __call (string $method, array $arguments):mixed {

        throw new Error("Method $method doesn't exist in ".static::class);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws Error If static method doesn't exist.
     */
    public static function __callStatic (string $method, array $arguments):mixed {

        throw new Error("Method $method doesn't exist in ".static::class);

    }

}