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

namespace FireHub\Core\Support\LowLevel;

use Error;

use function constant;
use function define;
use function defined;

/**
 * ### Constant low-level class
 *
 * Class allows you to obtain information about constants.
 * @since 1.0.0
 */
final class Constant {

    /**
     * ### Returns the value of a constant
     *
     * Method value() is useful if you need to retrieve the value of a constant, but do not know its name.
     * I.e., it is stored in a variable or returned by a function.
     * @since 1.0.0
     *
     * @param non-empty-string $name <p>
     * The constant name.
     * </p>
     *
     * @throws Error If the constant is not defined.
     *
     * @return mixed The value of the constant.
     *
     * @note This function works also with class constants and enum cases.
     */
    public static function value (string $name):mixed {

        return constant($name);

    }

    /**
     * ### Defines a named constant
     *
     * Defines a named constant at runtime.
     * @since 1.0.0
     *
     * @param non-empty-string $name <p>
     * The name of the constant.
     * </p>
     * @param null|scalar|array<array-key, mixed> $value <p>
     * The value of the constant.
     * </p>
     *
     * @return bool True on success or false on failure.
     */
    public static function define (string $name, array|bool|float|int|null|string $value):bool {

        return define($name, $value);

    }

    /**
     * ### Checks whether a given named constant exists
     *
     * This function works also with class constants and enum cases.
     * @since 1.0.0
     *
     * @param non-empty-string $name <p>
     * The constant name.
     * </p>
     *
     * @return bool True if the named constant given by name parameter has been defined, false otherwise.
     *
     * @note This function works also with class constants and enum cases.
     */
    public static function defined (string $name):bool {

        return defined($name);

    }

}