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
 * ### UnCloneable trait
 * @since 1.0.0
 */
trait UnCloneable {

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws Error If you try to clone this object.
     */
    public function __clone ():void {

        throw new Error('You are not allowed to clone '.static::class);

    }

}