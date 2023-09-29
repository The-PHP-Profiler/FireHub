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

use function function_exists;
use function call_user_func;
use function register_shutdown_function;
use function register_tick_function;
use function unregister_tick_function;

/**
 * ### Function low level class
 *
 * Class allows you to obtain information about functions.
 * @since 1.0.0
 */
final class Func {

    /**
     * ### Checks if function name exist
     *
     * Checks the list of defined functions, both built-in (internal) and user-defined, for function.
     * @since 1.0.0
     *
     * @param non-empty-string $name <p>
     * The function name.
     * </p>
     *
     * @return bool True if interface exist, false otherwise.
     */
    public static function isFunction (string $name):bool {

        return function_exists($name);

    }

    /**
     * ### Call the callback
     *
     * Calls the callback given by the first parameter and passes the remaining parameters as arguments.
     * @since 1.0.0
     *
     * @template TReturn
     *
     * @param callable():TReturn $callback <p>
     * The callable to be called.
     * </p>
     * @param mixed ...$arguments <p>
     * Zero or more parameters to be passed to the callback.
     * </p>
     *
     * @return TReturn The return value of the callback.
     */
    public static function call (callable $callback, mixed ...$arguments):mixed {

        return call_user_func($callback, ...$arguments);

    }

    /**
     * ### Register a function for execution on shutdown
     *
     * Registers a callback to be executed after script execution finishes or exit() is called.
     *
     * Multiple calls to register_shutdown_function() can be made, and each will be called in the same order as they
     * were registered. If you call exit() within one registered shutdown function, processing will stop completely
     * and no other registered shutdown functions will be called.
     *
     * Shutdown functions may also call register_shutdown_function() themselves to add a shutdown function to the end
     * of the queue.
     * @since 1.0.0
     *
     * @param callable $callback <p>
     * The shutdown callback to register.
     * The shutdown callbacks are executed as the part of the request, so it's possible to send output from them and
     * access output buffers.
     * </p>
     * @param mixed ...$arguments <p>
     * It is possible to pass parameters to the shutdown function by passing additional parameters.
     * </p>
     *
     * @return void
     */
    public static function registerShutdown (callable $callback, mixed ...$arguments):void {

        register_shutdown_function($callback, ...$arguments);

    }

    /**
     * ### Register a function for execution on each tick
     *
     * Registers the given callback to be executed when a tick is called.
     * @since 1.0.0
     *
     * @param callable $callback <p>
     * The function to register.
     * </p>
     * @param mixed ...$arguments <p>
     * Parameters for callback function.
     * </p>
     *
     * @return void
     */
    public static function registerTick (callable $callback, mixed ...$arguments):void {

        register_tick_function($callback, ...$arguments);

    }

    /**
     * ### De-register a function for execution on each tick
     *
     * De-registers the function, so it is no longer executed when a tick is called.
     * @since 1.0.0
     *
     * @param callable $callback <p>
     * The function to de-register.
     * </p>
     *
     * @return void
     */
    public static function unregisterTick (callable $callback):void {

        unregister_tick_function($callback);

    }

}