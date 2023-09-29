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

use function get_class;
use function get_mangled_object_vars;
use function get_object_vars;

/**
 * ### Object low level class
 *
 * Class allows you to obtain information about objects.
 * @since 1.0.0
 */
final class Obj extends ClsObj {

    /**
     * ### Returns the name of the class of an object
     * @since 1.0.0
     *
     * @param object $object <p>
     * The tested object.
     * </p>
     *
     * @return class-string The name of the class of which object is an instance.
     */
    public static function className (object $object):string {

        return get_class($object);

    }

    /**
     * ### Gets the properties of the given object
     * @since 1.0.0
     *
     * @param object $object <p>
     * An object instance.
     * </p>
     *
     * @return array<non-empty-string, mixed> An associative array of defined object accessible non-static properties for the
     * specified object in scope.
     */
    public static function properties (object $object):array {

        /** @phpstan-ignore-next-line */
        return get_object_vars($object);

    }

    /**
     * ### Gets the class public property values
     *
     * Returns an array whose elements are the object's properties.
     * The keys are the member variable names, with a few notable exceptions: private variables have the class name
     * prepended to the variable name, and protected variables have a * prepended to the variable name.
     * These prepended values have NUL bytes on either side.
     * Uninitialized typed properties are silently discarded.
     * @since 1.0.0
     *
     * @param object $object <p>
     * An object instance.
     * </p>
     *
     * @return array<non-empty-string, mixed> An array containing all properties, regardless of visibility, of object.
     */
    public static function mangledProperties (object $object):array {

        return get_mangled_object_vars($object);

    }

}