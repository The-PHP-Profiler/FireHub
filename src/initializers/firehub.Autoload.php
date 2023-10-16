<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2023 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @package FireHub\Core\Initializers
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Initializers;

use FireHub\Core\Support\LowLevel\ {
    Arr, Cls, DataIs, File, SPLAutoload, StrSB
};
use Closure, Error;

use const FireHub\Core\Support\Constants\Path\DS;

/**
 * ### Automatically loads class and interfaces
 *
 * Autoload registers any number of autoloaders, enabling for classes and interfaces to be automatically loaded if they are currently not defined.
 * By registering autoloaders, FireHub is given a last chance to load the class or interface before it fails with an error.
 *
 * Any class-like construct may be autoloaded the same way. That includes classes, interfaces, traits, and enumerations.
 * @since 1.0.0
 *
 * @api
 */
final class Autoload {

    /**
     * ### List of active autoloaders
     * @since 1.0.0
     *
     * @var array<non-empty-string, Closure(class-string $class):void>
     */
    private static array $autoloaders = [];

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::keyExist() To check if the given key or index exists in the array.
     *
     * @param non-empty-string $alias <p>
     * Autoloader implementation name.
     * </p>
     * @param Closure(class-string $class):void $callback $callback <p>
     * The autoload function being registered.
     * </p>
     *
     * @throws Error If autoloader alias is empty or already exist.
     *
     * @return void
     */
    private function __construct (
        private readonly string $alias,
        private readonly Closure $callback
    ) {

        if (empty($this->alias)) throw new Error('Autoloader alias cannot be empty.');

        if (Arr::keyExist($this->alias, self::$autoloaders))
            throw new Error('Autoloader alias already exist.');

        self::$autoloaders[$this->alias] = $this->callback;

    }

    /**
     * ### Manually include a list of classes
     *
     * This list can be filled with classes that need to be loaded manually.
     * This is useful if autoload itself has classes that need to be loader fist.
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Initializers\Autoload;
     *
     * Autoload::load([
     *  \MyNamespace\MyClass::class,
     *  \MyNamespace\MyOtherClass::class,
     * ]);
     * ```
     *
     * @param class-string[] $classes <p>
     * List of classes to be preloaders.
     * These preloaders will be called in order as they are in the list.
     * </p>
     * @param callable(class-string $class):string $callback <p>
     * Get a class path for including.
     * </p>
     *
     * @throws Error If a system cannot preload class.
     * @error\exeption E_WARNING if a system cannot preload class.
     *
     * @return void
     */
    public static function include (array $classes, callable $callback):void {

        foreach ($classes as $class) if (!include($callback($class)))
            throw new Error("Cannot preload $class.");

    }

    /**
     * ### Register new autoload implementation
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Initializers\Autoload::$callback To invoke autoload function being registered.
     * @uses \FireHub\Core\Support\LowLevel\SPLAutoload::register() To register new autoload implementation.
     *
     * @example Registering new autoload implementation.
     *
     * First parameter is autoload name that can be later used to unregistered same autoloader, while the second
     * parameter should be a root path where all your classes are stored.
     *
     * ```php
     * use FireHub\Core\Initializers\Autoload;
     *
     * Autoload::register('MyApp', 'path_to_my_app/');
     * ```
     * @example Registering new autoload implementation with function.
     *
     * Alternatively, you can use callback instead of writing a direct root path. Callback should still return a root
     * path for your classes, but this way you can manipulate a returning result like in example bellow.
     *
     * note: you can return false if you want to filter the same results.
     *
     * ```php
     * use FireHub\Core\Initializers\Autoload;
     *
     * Autoload::register('MyApp', function (string $namespace, string $classname):string|false {
     *  if ($classname === 'SomeClassName') {
     *      return false;
     *  }
     *
     * \\ return $namespace.'\\'.$classname.'.class.php';
     * });
     * ```
     *
     * @param non-empty-string $alias <p>
     * Autoloader implementation name.
     * </p>
     * @param non-empty-string|callable(string $namespace, string $classname):(string|false) $path <p>
     * Folder path where autoloader will try to find classes.
     * All namespace components will be resolved as folders inside a root path.
     * </p>
     * @param bool $prepend [optional] <p>
     * If true, autoloader will be prepended queue instead of appending it.
     * </p>
     *
     * @throws Error If autoloader alias is empty.
     * @throws Error If autoloader alias already exists.
     * @throws Error If cannot register autoloader.
     *
     * @return void
     *
     * @see \FireHub\Core\Initializers\Autoload::unregister() Unregister autoload implementation.
     * @see \FireHub\Core\Initializers\Autoload::append() Register new autoload implementation at the end of the line.
     * @see \FireHub\Core\Initializers\Autoload::prepand() Register new autoload implementation at the beginning of
     * the line.
     *
     */
    public static function register (string $alias, string|callable $path, bool $prepend = false):void {

        $autoload = new self($alias, self::callback($path));

        if (!SPLAutoload::register(self::$autoloaders[$autoload->alias], $prepend)) {

            unset(self::$autoloaders[$alias]);
            throw new Error("Cannot register autoloader $alias.");

        }

    }

    /**
     * ### Register new autoload implementation at the end of the line
     *
     * Triggers register() method with prepend parameter set to false.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Initializers\Autoload::register() To register new autoload implementation.
     *
     * @param non-empty-string $alias <p>
     * Autoloader implementation name.
     * </p>
     * @param non-empty-string|callable(string $namespace, string $classname):(string|false) $path <p>
     * Folder path where autoloader will try to find classes.
     * All namespace components will be resolved as folders inside a root path.
     * </p>
     *
     * @throws Error If autoloader alias is empty.
     * @throws Error If autoloader alias already exists.
     * @throws Error If cannot register autoloader.
     *
     * @return void
     *
     * @see \FireHub\Core\Initializers\Autoload::register() Register autoload implementation.
     * @see \FireHub\Core\Initializers\Autoload::prepend() Register new autoload implementation at the beginning of the line.
     */
    public static function append (string $alias, string|callable $path):void {

        self::register($alias, $path);

    }

    /**
     * ### Register new autoload implementation at the beginning of the line
     *
     * Triggers register() method with prepend parameter set to true.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Initializers\Autoload::register() To register new autoload implementation.
     *
     * @param non-empty-string $alias <p>
     * Autoloader implementation name.
     * </p>
     * @param non-empty-string|callable(string $namespace, string $classname):(string|false) $path <p>
     * Folder path where autoloader will try to find classes.
     * All namespace components will be resolved as folders inside a root path.
     * </p>
     *
     * @throws Error If autoloader alias is empty.
     * @throws Error If autoloader alias already exists.
     * @throws Error If cannot register autoloader.
     *
     * @return void
     *
     * @see \FireHub\Core\Initializers\Autoload::register() Register autoload implementation.
     * @see \FireHub\Core\Initializers\Autoload::append() Register new autoload implementation at the end of the line.
     */
    public static function prepend (string $alias, string|callable $path):void {

        self::register($alias, $path, true);

    }

    /**
     * ### Unregister autoload implementation
     *
     * Removes a function from the autoload queue. If the queue is activated and empty after removing
     * the given function, then it will be deactivated.
     *
     * When this function results in the queue being deactivated, any autoload function that previously existed
     * will not be reactivated.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\SPLAutoload::unregister() To unregister autoload implementation.
     *
     * @example
     * ```php
     * use FireHub\Core\Initializers\Autoload;
     *
     * Autoload::unregister('MyApp');
     * ```
     *
     * @param non-empty-string $alias <p>
     * Autoloader implementation name.
     * </p>
     *
     * @return bool True if autoloader was unregistered, false otherwise.
     *
     * @see \FireHub\Core\Initializers\Autoload::register() Register autoload implementation.
     */
    public static function unregister (string $alias):bool {

        if (SPLAutoload::unregister(self::$autoloaders[$alias])) {

            unset(self::$autoloaders[$alias]);

            return true;

        }

        return false;

    }

    /**
     * ### Get all registered autoloader implementations
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Initializers\Autoload;
     *
     * Autoload::implementations();
     *
     * // ['MyApp' => object, 'OtherImplementations' => object]
     * ```
     *
     * @return array<non-empty-string, Closure(class-string $class):void> List of autoloader implementations.
     */
    public static function implementations ():array {

        return self::$autoloaders;

    }

    /**
     * ### Try all registered autoload functions to load the requested class
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\SPLAutoload::load() To try all registered autoload functions to load the requested class.
     *
     * @example
     * ```php
     * use FireHub\Core\Initializers\Autoload;
     *
     * Autoload::load('\MyApp\MyClass');
     * ```
     *
     * @param class-string $class <p>
     * Fully qualified class name that is being called.
     * </p>
     *
     * @return void
     */
    public static function load (string $class):void {

        SPLAutoload::load($class);

    }

    /**
     * ### Invoke autoload function being registered
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Initializers\Autoload::classComponents() To get class components from class FQN.
     * @uses \FireHub\Core\Initializers\Autoload::invokeAutoload() To invoke _autoload method on class.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::callable() To verify that the contents of a variable can be
     * called as a function.
     * @uses \FireHub\Core\Support\LowLevel\File::isFile() To tell whether the path is a regular file.
     * @uses \FireHub\Core\Support\Constants\Path\DS As system definition for separating folders, platform specific.
     *
     * @param non-empty-string|callable(string $namespace, string $classname):(string|false) $path <p>
     * Folder path where autoloader will try to find classes.
     * All namespace components will be resolved as folders inside a root path.
     * </p>
     *
     * @throws Error If a class doesn't have at least two namespace levels or _autoload method is not declared as
     * static.
     *
     * @return Closure(class-string $class):void The autoload function being registered.
     */
    private static function callback (string|callable $path):Closure {

        return
            /**
             * ### The autoload function being registered
             *
             * @param class-string $class <p>
             * Fully qualified class name that is being loaded.
             * </p>
             *
             * @return void
             */
            function (string $class) use ($path):void {

                $class_components = self::classComponents($class);

                $path = DataIs::callable($path)
                    ? (($path_callable = $path($class_components['namespace'], $class_components['classname']))
                        ? $path_callable
                        : '')
                    : $path.DS.$class.'.php';

                if (File::isFile($path)) include $path;

                /** @phpstan-ignore-next-line */
                self::invokeAutoload($class);

            };

    }

    /**
     * ### Get class components from class FQN
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrSB::explode() To split a string by a string.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::implode() To join array elements with a string.
     * @uses \FireHub\Core\Support\LowLevel\Arr::lastKey() To get the last key from an array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::pop() To pop the element off the end of array.
     * @uses \FireHub\Core\Support\Constants\Path\DS As system definition for separating folders, platform specific.
     *
     * @param string $class <p>
     * Class FQN to resolve.
     * </p>
     *
     * @return array{namespace: string, classname: string} Class components.
     */
    private static function classComponents (string $class):array {

        $class_components = StrSB::explode($class, DS);

        $classname = $class_components[Arr::lastKey($class_components)];
        Arr::pop($class_components);

        foreach ($class_components as $key => $value) $class_components[$key] = strtolower($value);
        $namespace = StrSB::implode($class_components, DS);

        return [
            'namespace' => $namespace,
            'classname' => $classname
        ];

    }

    /**
     * ### Invoke _autoload method on class
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Cls::isClass() To check if class name exists.
     * @uses \FireHub\Core\Support\LowLevel\Cls::methodExist() To check if the class method exists.
     *
     * @param class-string $class <p>
     * Fully qualified class name that is being called.
     * </p>
     *
     * @throws Error If _autoload method is not declared as static.
     *
     * @return void
     */
    private static function invokeAutoload (string $class):void {

        try {

            if (Cls::isClass($class) && Cls::methodExist($class, '_autoload'))
                $class::_autoload();

        } catch (Error) {

            throw new Error("Method _autoload must be declared as static in $class.");

        }

    }

}