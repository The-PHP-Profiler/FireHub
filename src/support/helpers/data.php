<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package
 *
 * This file contains all data functions.
 * @since 1.0.0
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2023 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @package FireHub\Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\Helpers\Data;

use FireHub\Core\Support\Enums\ {
    Data\Category, Data\Type, Operator\Comparison
};
use FireHub\Core\Support\LowLevel\Data;
use Error;

/**
 * ### Gets or sets data type
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\Enums\Data\Type As parameter.
 * @uses \FireHub\Core\Support\LowLevel\Data::setType() To set a data type.
 * @uses \FireHub\Core\Support\LowLevel\Data::getType() To get a data type.
 *
 * @param mixed $value <p>
 * The variable being type checked or being converted to type.
 * </p>
 * @param null|\FireHub\Core\Support\Enums\Data\Type $type [optional] <p>
 * Type to convert variable to, or omit to get a type.
 * </p>
 *
 * @throws Error If a type of value is unknown or trying to set value to resource.
 *
 * @return (
 *  $type is null
 *  ? \FireHub\Core\Support\Enums\Data\Type
 *  : ($type is Type::T_ARRAY
 *      ? array<array-key, mixed>
 *      : ($type is Type::T_STRING
 *          ? string
 *          : ($type is Type::T_INT
 *              ? int
 *              : ($type is Type::T_FLOAT
 *                  ? float
 *                  : ($type is Type::T_OBJECT
 *                      ? object
 *                      : ($type is Type::T_BOOL
 *                          ? bool
 *                          : ($type is Type::T_NULL
 *                              ? null
 *                              : ($type is Type::T_RESOURCE
 *                                  ? false
 *                                  : mixed))))))))
 * ) Converted value or false if conversion failed.
 */
function type (mixed $value, Type $type = null):mixed {

    return $type ? Data::setType($value, $type) : Data::getType($value);

}

/**
 * ### Checks if value is of a type
 * @since 1.0.0
 *
 * @param mixed $value <p>
 * The variable being type checked.
 * </p>
 * @param \FireHub\Core\Support\Enums\Data\Category|\FireHub\Core\Support\Enums\Data\Type $type<p>
 * Type or type category to compare to.
 * </p>
 *
 * @return bool True if values is of a type, false otherwise.
 */
function is_type (mixed $value, Category|Type $type):bool {

    return Comparison::IDENTICAL->compare(
        $type instanceof Category
            ? Data::getType($value)->category()
            : Data::getType($value),
        $type
    );

}

/**
 * ### Gets data type category
 * @since 1.0.0
 *
 * @param mixed $value <p>
 * The variable being category type checked.
 * </p>
 *
 * @return \FireHub\Core\Support\Enums\Data\Category Data type category.
 */
function category (mixed $value):Category {

    return type($value)->category();

}