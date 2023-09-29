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

use Throwable;

use function get_class_methods;
use function get_parent_class;
use function method_exists;
use function property_exists;
use function is_a;
use function is_subclass_of;

/**
 * ### Class and object low level class
 *
 * Class allows you to obtain information about classes and objects.
 * @since 1.0.0
 */
abstract class ClsObj {

    /**
     * ### Gets the class or object methods names
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Cls::isClass() To check if $class is class.
     *
     * @param class-string|object $object_or_class <p>
     * The class name or an object instance.
     * </p>
     *
     * @return array<string>|false Returns an array of method names defined for the class,
     * or false if class doesn't exist.
     *
     * @note Result depends on the current scope.
     */
    final public static function methods (string|object $object_or_class):array|false {

        try {

            return get_class_methods($object_or_class);

        } catch (Throwable) {

            return false;

        }

    }

    /**
     * ### Retrieves the parent class name for object or class
     * @since 1.0.0
     *
     * @param class-string|object $object_or_class <p>
     * The tested object or class name. This parameter is optional if called from the object's method.
     * </p>
     *
     * @return class-string|false The name of the parent class of the class of which object_or_class is an instance
     * or the name.
     *
     * @note If the object does not have a parent or the class given does not exist false will be returned.
     * @note If called without parameter outside object, this function returns false.
     */
    final public static function parentClass (string|object $object_or_class):string|false {

        return get_parent_class($object_or_class);

    }

    /**
     * ### Checks if the class method exists
     * @since 1.0.0
     *
     * @param class-string|object $object_or_class <p>
     * An object instance or a class name.
     * </p>
     * @param non-empty-string $method <p>
     * The method name.
     * </p>
     *
     * @return bool True if the method given by method has been defined for the given object_or_class, false otherwise.
     *
     * @note Using this function will use any registered autoloaders if the class is not already known.
     */
    public static function methodExist (string|object $object_or_class, string $method):bool {

        return method_exists($object_or_class, $method);

    }

    /**
     * ### Checks if the object or class has a property
     * @since 1.0.0
     *
     * @param class-string|object $object_or_class <p>
     * The class name or an object of the class to test for.
     * </p>
     * @param non-empty-string $property <p>
     * The name of the property.
     * </p>
     *
     * @return null|bool True if the property exists, false if it doesn't exist or null in case of an error.
     *
     * @note This method cannot detect properties that are magically accessible using the __get magic method.
     * @note Using this function will use any registered autoloaders if the class is not already known.
     */
    final public static function propertyExist (string|object $object_or_class, string $property):?bool {

        return property_exists($object_or_class, $property);

    }

    /**
     * ### Checks whether the object or class is of a given type or subtype
     * @since 1.0.0
     *
     * @param class-string|object $object_or_class <p>
     * A class name or an object instance.
     * </p>
     * @param class-string $class <p>
     * The class or interface name.
     * </p>
     * @param bool $autoload [optional] <p>
     * Whether to allow this function to load the class automatically through the __autoload magic method.
     * </p>
     *
     * @return bool True if the object is of this object type or has this object type as one of its supertypes,
     * false otherwise.
     */
    public static function ofClass (string|object $object_or_class, string $class, bool $autoload = true):bool {

        return is_a($object_or_class, $class, $autoload);

    }

    /**
     * ### Checks if class has this class as one of its parents or implements it
     * @since 1.0.0
     *
     * @param class-string|object $object_or_class <p>
     * The tested class. No error is generated if the class does not exist.
     * </p>
     * @param class-string $class <p>
     * The class or interface name.
     * </p>
     * @param bool $autoload [optional] <p>
     * Whether to allow this function to load the class automatically through the __autoload magic method.
     * </p>
     *
     * @return bool True if the object is of this object or lass type or has this object type as one of its supertypes,
     * false otherwise.
     */
    public static function subClassOf (string|object $object_or_class, string $class, bool $autoload = true):bool {

        return is_subclass_of($object_or_class, $class, $autoload);

    }

}