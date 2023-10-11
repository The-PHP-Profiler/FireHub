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
 * ### UnJsonSerializable trait
 * @since 1.0.0
 */
trait UnJsonSerializable {

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws Error If you try to json_encode an object.
     */
    public function jsonSerialize ():array {

        throw new Error('You are not allowed to json_encode '.static::class);

    }

}