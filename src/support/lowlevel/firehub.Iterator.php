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

use Traversable;

use function iterator_apply;
use function iterator_count;
use function iterator_to_array;

/**
 * ### Iterator low level class
 *
 * An iterator are objects that can be iterated themselves internally.
 * @since 1.0.0
 */
final class Iterator {

    /**
     * ### Count the elements in an iterator
     * @since 1.0.0
     *
     * @param Traversable<mixed, mixed> $iterator <p>
     * The iterator being counted.
     * </p>
     *
     * @return non-negative-int Number of elements in iterator.
     */
    public static function count (iterable $iterator):int {

        return iterator_count($iterator);

    }

    /**
     * ### Copy the iterator into an array
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param Traversable<TKey, TValue> $iterator <p>
     * The iterator being copied.
     * </p>
     * @param bool $preserve_keys [optional] <p>
     * Whether to use the iterator element keys as index.
     * </p>
     *
     * @return ($preserve_keys is true ? array<TKey, TValue> : array<array-key, TValue>) An array containing items of the iterator.
     */
    public static function toArray (iterable $iterator, bool $preserve_keys = true):array {

        return iterator_to_array($iterator, $preserve_keys);

    }

    /**
     * ### Call a function for every element in an iterator
     * @since 1.0.0
     *
     * @template TKey
     * @template TValue
     *
     * @param Traversable<TKey, TValue> $iterator <p>
     * The iterator object to iterate over.
     * </p>
     * @param callable(TValue $value, TKey $key):bool $callback <p>
     * The callback function to call on every element
     * The function must return true in order to continue iterating over the iterator.
     * </p>
     * @param null|array<mixed> $arguments <p>
     * An array of arguments; each element of args is passed to the callback as separate argument.
     * </p>
     *
     * @return int Iteration count.
     */
    public static function apply (Traversable $iterator, callable $callback, array $arguments = null):int {

        return iterator_apply($iterator, $callback, $arguments);

    }

}