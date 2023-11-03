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

namespace FireHub\Core\Support\Collections;

use FireHub\Core\Support\Contracts\ {
    Countable, IterablesAgg
};
use FireHub\Core\Support\Collections\Helpers\Convert;
use Closure;

/**
 * ### Collection contract
 * @since 1.0.0
 *
 * @template TKey
 * @template TValue
 *
 * @extends \FireHub\Core\Support\Contracts\IterablesAgg<TKey, TValue>
 */
interface Collectable extends IterablesAgg, Countable {

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @param Closure $callable <p>
     * Data from a callable source.
     * </p>
     *
     * @return void
     */
    public function __construct (Closure $callable);

    /**
     * ### Get storage data
     * @since 1.0.0
     *
     * @return iterable<TKey, TValue> Storage data.
     */
    public function storage ():iterable;

    /**
     * ### Get collection as an array
     * @since 1.0.0
     *
     * @return array<array-key, mixed> Collection items as an array.
     */
    public function all ():array;

    /**
     * ### Get first item from collection
     * @since 1.0.0
     *
     * @param null|callable(TValue $value):TValue $callback [optional] <p>
     * If callback is used, the method will return the first item that passes truth test.
     * </p>
     *
     * @return null|TValue First item from a collection.
     */
    public function first (callable $callback = null):mixed;

    /**
     * ### Get first key from collection
     * @since 1.0.0
     *
     * @param null|callable(TValue $value):TKey $callback [optional] <p>
     * If callback is used, the method will return the first key that passes truth test.
     * </p>
     *
     * @return null|TKey First key from a collection.
     */
    public function firstKey (callable $callback = null):mixed;

    /**
     * ### Get last item from collection
     * @since 1.0.0
     *
     * @param null|callable(TValue $value):TValue $callback [optional] <p>
     * If callback is used, the method will return the last item that passes truth test.
     * </p>
     *
     * @return null|TValue Last item from a collection.
     */
    public function last (callable $callback = null):mixed;

    /**
     * ### Get last key from collection
     * @since 1.0.0
     *
     * @param null|callable(TValue $value):TKey $callback [optional] <p>
     * If callback is used, the method will return the last key that passes truth test.
     * </p>
     *
     * @return null|TKey Last key from a collection.
     */
    public function lastKey (callable $callback = null):mixed;

    /**
     * ### Call user generated function on each item in collection
     * @since 1.0.0
     *
     * @param callable(TValue $value):(false|void) $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return bool True if each item in the collection has iterated, false otherwise.
     */
    public function each (callable $callback):bool;

    /**
     * ### Determines whether a collection contains a given item
     * @since 1.0.0
     *
     * @param TValue $value <p>
     * The value to check.
     * </p>
     *
     * @return bool True if a collection contains checked item, false otherwise.
     */
    public function contains (mixed $value):bool;

    /**
     * ### Determines whether collection doesn't contain a given item
     * @since 1.0.0
     *
     * @param TValue $value <p>
     * The value to check.
     * </p>
     *
     * @return bool True if a collection doesn't contain checked item, false otherwise.
     */
    public function doesntContains (mixed $value):bool;

    /**
     * ### Convert collection to different one
     * @since 1.0.0
     *
     * @return \FireHub\Core\Support\Collections\Helpers\Convert<TKey, TValue> As return.
     */
    public function convert ():Convert;

    /**
     * ### Verify that all items of a collection pass a given truth test
     * @since 1.0.0
     *
     * @param callable(TValue $value):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return bool True if all items in the collection passed given truth test, false otherwise.
     */
    public function every (callable $callback):bool;

    /**
     * ### Filter items from collection
     * @since 1.0.0
     *
     * @param callable(TValue $value):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return static<TKey, TValue> New filtered collection.
     */
    public function filter (callable $callback):self;

    /**
     * ### Check if a collection is empty
     * @since 1.0.0
     *
     * @return bool True if a collection is empty, false otherwise.
     */
    public function isEmpty ():bool;

    /**
     * ### Check if a collection is not empty
     * @since 1.0.0
     *
     * @return bool True if a collection is not empty, false otherwise.
     */
    public function isNotEmpty ():bool;

    /**
     * ### Applies the callback to each collection item
     * @since 1.0.0
     *
     * @param callable(TValue $value):mixed $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return static<TKey, mixed> New modified collection.
     */
    public function map (callable $callback):self;

    /**
     * ### Merge new collections into current one
     * @since 1.0.0
     *
     * @param self<TKey, TValue> ...$collections <p>
     * Collections to merge.
     * </p>
     *
     * @return static<TKey, TValue> New merged collection.
     */
    public function merge (self ...$collections):self;

    /**
     * ### Reject items from collection
     * @since 1.0.0
     *
     * @param callable(TValue $value):bool $callback $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return static<TKey, TValue> New rejected collection.
     */
    public function reject (callable $callback):self;

    /**
     * ### Searches the collection for a given value and returns the first corresponding key if successful
     * @since 1.0.0
     *
     * @param TValue $value <p>
     * The searched value.
     * If value is a string, the comparison is done in a case-sensitive manner.
     * </p>
     *
     * @return TKey|false The key if it is found in the collection, false otherwise.
     * If value is found in a collection more than once, the first matching key is returned.
     */
    public function search (mixed $value):mixed;

    /**
     * ### Execute the given callback when the first argument given to the method evaluates to true
     * @since 1.0.0
     *
     * @param bool $condition <p>
     * Condition to meet.
     * </p>
     * @param callable(static):mixed $condition_meet <p>
     * Callback if condition is meet.
     * </p>
     * @param null|callable(static):mixed $condition_not_meet [optional] <p>
     * Callback if condition is not meet.
     * </p>
     *
     * @return static<TKey, TValue> This collection.
     */
    public function when (bool $condition, callable $condition_meet, ?callable $condition_not_meet = null):self;

    /**
     * ### Execute the given callback when the collection is empty
     * @since 1.0.0
     *
     * @param callable(static):mixed $callback $callback <p>
     * Callback if a collection is empty.
     * </p>
     *
     * @return static<TKey, TValue> This collection.
     */
    public function whenEmpty (callable $callback):self;

    /**
     * ### Execute the given callback when the collection is not empty
     * @since 1.0.0
     *
     * @param callable(static):mixed $callback <p>
     * Callback if a collection is not empty.
     * </p>
     *
     * @return static<TKey, TValue> This collection.
     */
    public function whenNotEmpty (callable $callback):self;

    /**
     * ### Execute the given callback unless the first argument given to the method evaluates to true
     * @since 1.0.0
     *
     * @param bool $condition <p>
     * Condition to meet.
     * </p>
     * @param callable($this):mixed $condition_meet <p>
     * Callback if condition is meet.
     * </p>
     * @param null|callable($this):mixed $condition_not_meet [optional] <p>
     * Callback if condition is not meet.
     * </p>
     *
     * @return static<TKey, TValue> This collection.
     */
    public function unless (bool $condition, callable $condition_meet, ?callable $condition_not_meet = null):self;

}