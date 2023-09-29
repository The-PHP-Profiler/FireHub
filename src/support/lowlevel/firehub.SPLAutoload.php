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

use function spl_autoload;
use function spl_autoload_call;
use function spl_autoload_extensions;
use function spl_autoload_functions;
use function spl_autoload_register;
use function spl_autoload_unregister;

/**
 * ### SPL Autoload low level class
 * @since 1.0.0
 */
final class SPLAutoload {

    /**
     * ### Default autoload implementation
     *
     * This function is intended to be used as a default implementation for autoload().
     * If nothing else is specified and register method is called without any parameters
     * then this function will be used for any later call to autoload.
     * @since 1.0.0
     *
     * @param class-string $class <p>
     * The name of the class (and namespace) being instantiated.
     * </p>
     * @param null|string $file_extensions [optional] <p>
     * By default, it checks all include paths to contain filenames built up by the lowercase class name appended by the
     * filename extensions .inc and .php.
     * </p>
     *
     * @return void
     */
    public static function default (string $class, string $file_extensions = null):void {

        spl_autoload($class, $file_extensions);

    }

    /**
     * ### Register and return default file extensions for default autoload
     *
     * This function can modify and check the file extensions that the built-in autoload fallback function
     * spl_autoload() will be using.
     * @since 1.0.0
     *
     * @param null|non-empty-string $file_extensions [optional] <p>
     * If null, it simply returns the current list of extensions each separated by comma.
     * To modify the list of file extensions, simply invoke the functions with the new list of file extensions to use
     * in a single string with each extension separated by comma.
     * </p>
     *
     * @return string A comma delimited list of default file extensions for default method.
     */
    public static function defaultExtensions (string $file_extensions = null):string {

        return spl_autoload_extensions($file_extensions);

    }

    /**
     * ### Register new autoload implementation
     *
     * Register a function with the provided autoload queue. If the queue is not yet activated it will be activated.
     *
     * If there must be multiple autoload functions, this method allows for this.
     * It effectively creates a queue of autoload functions, and runs through each of them in the order they are defined.
     * @since 1.0.0
     *
     * @param null|callable(class-string $class):void $callback [optional] <p>
     * The autoload function being registered.
     *
     * note: If null, then the default implementation of spl_autoload() will be registered.
     * </p>
     * @param bool $prepend [optional] <p>
     * If true, autoloader will be prepended queue instead of appending it.
     * </p>
     *
     * @return bool True if autoloader was registered, false otherwise.
     */
    public static function register (?callable $callback = null, bool $prepend = false):bool {

        /** @phpstan-ignore-next-line */
        return spl_autoload_register($callback, true, $prepend);

    }

    /**
     * ### Unregister autoload implementation
     *
     * Removes a function from the autoload queue. If the queue is activated and empty after removing the given
     * function then it will be deactivated.
     *
     * When this function results in the queue being deactivated, any autoload function that previously existed will
     * not be reactivated.
     * @since 1.0.0
     *
     * @param callable(class-string $class):void $callback [optional] <p>
     * The autoload function being unregistered.
     * </p>
     *
     * @return bool True if autoloader was unregistered, false otherwise.
     */
    public static function unregister (callable $callback):bool {

        return spl_autoload_unregister($callback);

    }

    /**
     *
     * ### Get all registered autoload functions
     * @since 1.0.0
     *
     * @return array<callable(class-string $class):void> An array of all registered autoload functions.
     * If no function is registered, or the autoload queue is not activated, then the return value will be an empty
     * array.
     */
    public static function functions ():array {

        return spl_autoload_functions();

    }

    /**
     * ### Try all registered autoload functions to load the requested class
     * @since 1.0.0
     *
     * @param class-string $class <p>
     * Fully qualified class name that is being called.
     * </p>
     *
     * @return void
     */
    public static function load (string $class):void {

        spl_autoload_call($class);

    }

}