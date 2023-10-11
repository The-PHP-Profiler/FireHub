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
    UnCallables
};
use FireHub\Core\Support\LowLevel\Constant;
use Error;

/**
 * ### FireHub base enum trait
 * @since 1.0.0
 */
trait BaseEnum {

    /**
     * ### UnCallables trait
     * @since 1.0.0
     */
    use UnCallables;

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Constant::value() To return the value of a constant.
     */
    public static function fromName (string $name):?static {

        try {

            $class = static::class;

            $value = Constant::value("$class::$name");

            return $value instanceof static ? $value : null;


        } catch (Error) {

            return null;

        }

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Constant::value() To return the value of a constant.
     *
     * @throws Error If constant name is empty.
     * @throws Error If we cannot get name from constant, it doesn't exist.
     */
    public static function getConstant (string $name):mixed {

        !empty($name) ?: throw new Error("Constant cannot be empty.");

        try {

            $class = static::class;

            return Constant::value("$class::$name");

        } catch (Error) {

            throw new Error("Cannot get name from constant $name, constant doesn't exist.");

        }

    }

}