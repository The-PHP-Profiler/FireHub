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

use FireHub\Core\Support\Enums\Data\Type;
use Throwable;

use function gettype;
use function serialize;
use function settype;
use function unserialize;

/**
 * ### Data low level class
 *
 * Class contains variable handling methods.
 * @since 1.0.0
 */
final class Data {

    /**
     * ### Gets data type
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_BOOL As data type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT As data type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_FLOAT As data type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_STRING As data type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_ARRAY As data type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_OBJECT As data type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_NULL As data type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_RESOURCE As data type.
     *
     * @param mixed $value <p>
     * The variable being type checked.
     * </p>
     *
     * @return \FireHub\Core\Support\Enums\Data\Type|false Type of data, false if type is unknown.
     */
    public static function getType (mixed $value):Type|false {

        return match (gettype($value)) {
            'boolean' => Type::T_BOOL,
            'integer' => Type::T_INT,
            'double' => Type::T_FLOAT,
            'string' => Type::T_STRING,
            'array' => Type::T_ARRAY,
            'object' => Type::T_OBJECT,
            'NULL' => Type::T_NULL,
            'resource', 'resource (closed)' => Type::T_RESOURCE,
            default => false
        };

    }

    /**
     * ### Sets data type
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_BOOL As data type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_INT As data type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_FLOAT As data type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_ARRAY As data type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_OBJECT As data type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_NULL As data type.
     * @uses \FireHub\Core\Support\Enums\Data\Type::T_RESOURCE As data type.
     *
     * @param mixed $value <p>
     * The variable being converted to type.
     * </p>
     * @param \FireHub\Core\Support\Enums\Data\Type $type <p>
     * Type to convert variable to.
     * </p>
     *
     * @return (
     *      $type is Type::T_ARRAY
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
     *                                  : mixed)))))))
     * ) Converted value or false if conversion failed.
     */
    public static function setType (mixed $value, Type $type):mixed {

        // resource is not settable
        if ($type === Type::T_RESOURCE) return false;

        settype($value, match ($type) {
            Type::T_BOOL => 'boolean',
            Type::T_INT => 'integer',
            Type::T_FLOAT => 'double',
            Type::T_ARRAY => 'array',
            Type::T_OBJECT => 'object',
            Type::T_NULL => 'NULL',
            default => 'string'

        });

        return $value;

    }

    /**
     * ### Generates storable representation of data
     * @since 1.0.0
     *
     * @param scalar|array<array-key, mixed>|object|null $value
     *
     * @return string|false String containing a byte-stream representation of value that can be stored anywhere,
     * false otherwise.
     *
     * @note This is a binary string which may include null bytes, and needs to be stored and handled as such.
     * For example, serialize() output should generally be stored in a BLOB field in a database, rather than a CHAR or
     * TEXT field.
     */
    public static function serializeValue (string|int|float|bool|array|object|null $value):string|false {

        try {

            return serialize($value);

        } catch (Throwable) { // anonymous classes and functions cannot be serialized

            return false;

        }

    }

    /**
     * ### Creates a PHP value from a stored representation
     * @since 1.0.0
     *
     * @param non-empty-string $data <p>
     * The serialized string.
     * </p>
     * @param bool|array<class-string> $allowed_classes [optional] <p>
     * Either an array of class names which should be accepted, false to accept no classes,
     * or true to accept all classes.
     * </p>
     * @param int $max_depth [optional] <p>
     * The maximum depth of structures permitted during unserialization, and is intended to prevent stack overflows.
     * </p>
     *
     * @return mixed The converted value is returned, false otherwise.
     */
    public static function unserializeValue (string $data, bool|array $allowed_classes = false, int $max_depth = 4096):mixed {

        return match ($data) {
            'b:0;', 'N;' => false, // false if $data is serialized false already or $data is NULL
            default => ($unserialized_data = unserialize(
                $data,
                ['allowed_classes' => $allowed_classes, 'max_depth' => $max_depth])
            ) ? $unserialized_data : false
        };

    }

}