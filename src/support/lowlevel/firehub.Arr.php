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

use FireHub\Core\Support\Enums\{
    Order, Sort
};
use Throwable;

use const ARRAY_FILTER_USE_BOTH;
use const ARRAY_FILTER_USE_KEY;
use const COUNT_NORMAL;
use const COUNT_RECURSIVE;
use const SORT_ASC;
use const SORT_DESC;

use function array_chunk;
use function array_column;
use function array_combine;
use function array_count_values;
use function array_diff;
use function array_diff_assoc;
use function array_diff_key;
use function array_diff_uassoc;
use function array_diff_ukey;
use function array_fill;
use function array_fill_keys;
use function array_filter;
use function array_flip;
use function array_intersect;
use function array_intersect_assoc;
use function array_intersect_key;
use function array_intersect_uassoc;
use function array_intersect_ukey;
use function array_is_list;
use function array_key_exists;
use function array_key_first;
use function array_key_last;
use function array_keys;
use function array_map;
use function array_merge;
use function array_merge_recursive;
use function array_multisort;
use function array_pad;
use function array_pop;
use function array_product;
use function array_push;
use function array_rand;
use function array_reduce;
use function array_replace;
use function array_replace_recursive;
use function array_reverse;
use function array_search;
use function array_shift;
use function array_slice;
use function array_splice;
use function array_sum;
use function array_udiff;
use function array_udiff_assoc;
use function array_udiff_uassoc;
use function array_uintersect;
use function array_uintersect_assoc;
use function array_uintersect_uassoc;
use function array_unique;
use function array_unshift;
use function array_values;
use function array_walk;
use function array_walk_recursive;
use function arsort;
use function asort;
use function count;
use function in_array;
use function krsort;
use function ksort;
use function range;
use function rsort;
use function shuffle;
use function sort;
use function uasort;
use function uksort;
use function usort;

/**
 * ### Array low level class
 *
 * An array in PHP is actually an ordered map. A map is a type that associates values to keys. This type is
 * optimized for several different uses; it can be treated as an array, list (vector),
 * hash table (an implementation of a map), dictionary, collection, stack, queue, and probably more.
 * As array values can be other arrays, trees and multidimensional arrays are also possible.
 * @since 1.0.0
 */
final class Arr {

    /**
     * ### Checks whether a given array is a list
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $array <p>
     * The array being evaluated.
     * </p>
     *
     * @return bool True if array is a list, false otherwise.
     *
     * @note This function returns true on empty arrays.
     */
    public static function isList (array $array):bool {

        return array_is_list($array);

    }

    /**
     * ### Pop the element off the end of array
     * @since 1.0.0
     *
     * @template TValue
     *
     * @param array<array-key, TValue> &$array <p>
     * Array to pop.
     * </p>
     *
     * @return TValue|null The last value of array. If array is empty (or is not an array), null will be returned.
     */
    public static function pop (array &$array):mixed {

        return array_pop($array);

    }

    /**
     * ### Push elements onto the end of array
     * @since 1.0.0
     *
     * @param array<array-key, mixed> &$array <p>
     * The input array.
     * </p>
     * @param mixed ...$values [optional] <p>
     * The values to push onto the end of the array.
     * </p>
     *
     * @return int The new number of elements in the array.
     *
     * @note If you use push to add one element to the array, it's better to use $array[] = because in that way
     * there is no overhead of calling a function.
     */
    public static function push (array &$array, mixed ...$values):int {

        return array_push($array, ...$values);

    }

    /**
     * ### Removes an item at the beginning of an array
     * @since 1.0.0
     *
     * @template TValue
     *
     * @param array<array-key, TValue> &$array <p>
     * Array to shift.
     * </p>
     *
     * @return TValue|null The shifted value, or null if array is empty or is not an array.
     *
     * @note This function will reset the array pointer of the input array after use.
     */
    public static function shift (array &$array):mixed {

        return array_shift($array);

    }

    /**
     * ### Prepend one or more elements to the beginning of an array
     * @since 1.0.0
     *
     * @param array<array-key, mixed> &$array <p>
     * The input array.
     * </p>
     * @param mixed ...$values [optional] <p>
     * The values to prepend.
     * </p>
     *
     * @return int The new number of elements in the array.
     *
     * @note Resets array's internal pointer to the first element.
     */
    public static function unshift (array &$array, mixed ...$values):int {

        return array_unshift($array, ...$values);

    }

    /**
     * ### Counts all elements in the array
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $array <p>
     * Array to count.
     * </p>
     * @param bool $multi_dimensional [optional] <p>
     * Count multidimensional items.
     * </p>
     *
     * @return non-negative-int Number of elements in array.
     *
     * @caution Method can detect recursion to avoid an infinite loop, but will emit an E_WARNING every time it does (in case the array contains itself more than once) and return a count higher than may be expected.
     */
    public static function count (array $array, bool $multi_dimensional = false):int {

        return count($array, $multi_dimensional ? COUNT_RECURSIVE : COUNT_NORMAL);

    }

    /**
     * ### Counts all the values of an array
     * @since 1.0.0
     *
     * @template TValue of array-key
     *
     * @param array<array-key, TValue> $array <p>
     * The array of values to count.
     * </p>
     * @return array<TValue, positive-int> An associative array of values from input as keys and their count
     * as value.
     */
    public static function countValues (array $array):array {

        return array_count_values($array);

    }

    /**
     * ### Split an array into chunks
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array.
     * </p>
     * @param positive-int $length <p>
     * The size of each chunk.
     * If length is less than 1, it will default to 1.
     * </p>
     * @param bool $preserve_keys [optional] <p>
     * When set to true keys will be preserved.
     * Default is false which will reindex the chunk numerically.
     * </p>
     *
     * @return ($preserve_keys is true ? array<array<TKey, TValue>> : array<array<TValue>>)
     * Multidimensional numerically indexed array,
     * starting with zero, with each dimension containing size elements.
     */
    public static function chunk (array $array, int $length, bool $preserve_keys = false):array {

        return array_chunk($array, $length < 1 ? 1 : $length, $preserve_keys);

    }

    /**
     * ### Return the values from a single column in the input array
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<array-key, array<TKey, TValue>> $array <p>
     * A multidimensional array (record set) from which to pull a column of values.
     * </p>
     * @param TKey $key <p>
     * The column of values to return.
     * This value may be the integer key of the column you wish to retrieve,
     * or it may be the string key name for an associative array.
     * </p>
     * @param null|TKey $index [optional] <p>
     * The column to use as the index/keys for the returned array.
     * This value may be the integer key of the column, or it may be the string key name.
     * The value is cast as usual for array keys.
     * </p>
     *
     * @return ($index is null ? array<TValue> : array<TValue, TValue>) Array of values representing a single column
     * from the input array.
     */
    public static function column (array $array, int|string $key, int|string $index = null):array {

        return array_column($array, $key, $index);

    }

    /**
     * ### Creates an array by using one array for keys and another for its values
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<array-key, TKey> $keys <p>
     * Array of values to be used as keys. Illegal values for key will be converted to string.
     * </p>
     * @param array<array-key, TValue> $values <p>
     * Array of values to be used as values on combined array.
     * </p>
     *
     * @return array<TKey, TValue>|false The combined array, false if the number of elements for each array isn't
     * equal, if the arrays are empty or array key is not int nor string.
     */
    public static function combine (array $keys, array $values):array|false {

        try {

            return array_combine($keys, $values);

        } catch (Throwable) {

            return false;

        }

    }

    /**
     * ### Computes the difference of arrays using values for comparison
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array to compare from.
     * </p>
     * @param array<array-key, mixed> ...$excludes [optional] <p>
     * An array to compare against.
     * </p>
     *
     * @return array<TKey, TValue> An array containing all the entries from array1
     * that are not present in any of the other arrays.
     */
    public static function difference (array $array, array ...$excludes):array {

        return array_diff($array, ...$excludes);

    }

    /**
     * ### Computes the difference of arrays using values for comparison by using
     * a callback function for data comparison
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array to compare from.
     * </p>
     * @param array<array-key, mixed> $excludes <p>
     * An array to compare against.
     * </p>
     * @param callable(TValue $a, TValue $b):int<-1, 1> $callback <p>
     * The comparison function.
     * </p>
     *
     * @return array<TKey, TValue> An array containing all the entries from array1
     * that are not present in any of the other arrays.
     *
     * @note The comparison function must return an integer less than, equal to, or greater than zero if the first
     * argument is considered to be respectively less than, equal to, or greater than the second.
     */
    public static function differenceFunc (array $array, array $excludes, callable $callback):array {

        return array_udiff($array, $excludes, $callback);

    }

    /**
     * ### Computes the difference of arrays using keys for comparison
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array to compare from.
     * </p>
     * @param array<array-key, mixed> ...$excludes [optional] <p>
     * An array to compare against.
     * </p>
     *
     * @return array<TKey, TValue> An array containing all the entries from array1
     * that are not present in any of the other arrays.
     */
    public static function differenceKey (array $array, array ...$excludes):array {

        return array_diff_key($array, ...$excludes);

    }

    /**
     * ### Computes the difference of arrays using keys for comparison by using a callback function for data comparison
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array to compare from.
     * </p>
     * @param array<array-key, mixed> $excludes <p>
     * An array to compare against.
     * </p>
     * @param callable(TValue $a, TValue $b):int<-1, 1> $callback <p>
     * The comparison function.
     * </p>
     *
     * @return array<TKey, TValue> An array containing all the entries from array1
     * that are not present in any of the other arrays.
     *
     * @note The comparison function must return an integer less than, equal to, or greater than zero if the first argument is considered to be respectively less than, equal to, or greater than the second.
     */
    public static function differenceKeyFunc (array $array, array $excludes, callable $callback):array {

        return array_diff_ukey($array, $excludes, $callback);

    }

    /**
     * ### Computes the difference of arrays with additional index check
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array to compare from.
     * </p>
     * @param array<array-key, mixed> ...$excludes [optional] <p>
     * An array to compare against.
     * </p>
     *
     * @return array<TKey, TValue> An array containing all the entries from array1
     * that are not present in any of the other arrays.
     */
    public static function differenceAssoc (array $array, array ...$excludes):array {

        return array_diff_assoc($array, ...$excludes);

    }

    /**
     * ### Computes the difference of arrays with additional index check by using a callback function for value comparison
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array to compare from.
     * </p>
     * @param array<array-key, mixed> $excludes <p>
     * An array to compare against.
     * </p>
     * @param callable(TValue $a, TValue $b):int<-1, 1> $callback <p>
     * The comparison function.
     * </p>
     *
     * @return array<TKey, TValue> An array containing all the entries from array1
     * that are not present in any of the other arrays.
     *
     * @note The comparison function must return an integer less than, equal to, or greater than zero if the first argument is considered to be respectively less than, equal to, or greater than the second.
     */
    public static function differenceAssocFuncValue (array $array, array $excludes, callable $callback):array {

        return array_udiff_assoc($array, $excludes, $callback);

    }

    /**
     * ### Computes the difference of arrays with additional index check by using a callback function for key comparison
     * @since 1.0.0
     *
     * @template TKey
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array to compare from.
     * </p>
     * @param array<array-key, mixed> $excludes <p>
     * An array to compare against.
     * </p>
     * @param callable(TKey $a, TKey $b):int<-1, 1> $callback <p>
     * The comparison function.
     * </p>
     *
     * @return array<TKey, TValue> An array containing all the entries from array1
     * that are not present in any of the other arrays.
     *
     * @note The comparison function must return an integer less than, equal to, or greater than zero if the first
     * argument is considered to be respectively less than, equal to, or greater than the second.
     */
    public static function differenceAssocFuncKey (array $array, array $excludes, callable $callback):array {

        return array_diff_uassoc($array, $excludes, $callback);

    }

    /**
     * ### Computes the difference of arrays with additional index check by using a callback function for key and value comparison
     * @since 1.0.0
     *
     * @template TKey
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array to compare from.
     * </p>
     * @param array<array-key, mixed> $excludes <p>
     * An array to compare against.
     * </p>
     * @param callable(TValue $a, TValue $b):int<-1, 1> $callback_value <p>
     * The comparison function for value.
     * </p>
     * @param callable(TKey $a, TKey $b):int<-1, 1> $callback_key <p>
     * The comparison function for key.
     * </p>
     *
     * @return array<TKey, TValue> An array containing all the entries from array1
     * that are not present in any of the other arrays.
     *
     * @note The comparison function must return an integer less than, equal to, or greater than zero if the first
     * argument is considered to be respectively less than, equal to, or greater than the second.
     */
    public static function differenceAssocFuncKeyValue (array $array, array $excludes, callable $callback_value, callable $callback_key):array {

        return array_udiff_uassoc($array, $excludes, $callback_value, $callback_key);

    }

    /**
     * ### Filter elements in an array
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs To find whether a variable is null.
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array to iterate over.
     * </p>
     * @param null|callable(TValue $value):bool $callback [optional] <p>
     * The callback function to use.
     * If no callback is supplied, all empty and false entries of array will be removed.
     * </p>
     * @param bool $pass_key [optional] <p>
     * Pass key as the argument to callback.
     * </p>
     * @param bool $pass_value [optional] <p>
     * Pass value as the argument to callback.
     * </p>
     *
     * @return array<TKey, TValue> Filtered array.
     *
     * @caution If the array is changed from the callback function (e.g. element added, deleted or unset)
     * the behavior of this function is undefined.
     */
    public static function filter (array $array, callable $callback = null, bool $pass_key = false, bool $pass_value = true):array {

        if (DataIs::null($callback)) return array_filter($array);

        return array_filter($array, $callback,
            $pass_key && $pass_value
                ? ARRAY_FILTER_USE_BOTH
                : ($pass_key ? ARRAY_FILTER_USE_KEY : 0)
        );

    }

    /**
     * ### Fill an array with values
     * @since 1.0.0
     *
     * @template TValue
     *
     * @param TValue $value <p>
     * Value to use for filling.
     * </p>
     * @param int $start_index <p>
     * The first index of the returned array.
     * </p>
     * @param non-negative-int $length <p>
     * Number of elements to insert. Must be greater than or equal to zero.
     * </p>
     *
     * @return array<int, TValue> Filled array.
     */
    public static function fill (mixed $value, int $start_index, int $length):array {

        return array_fill($start_index, $length, $value);

    }

    /**
     * ### Fill an array with values, specifying keys
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<array-key, TKey> $keys <p>
     * Array of values that will be used as keys. Illegal values for key will be converted to string.
     * </p>
     * @param TValue $value p>
     * Value to use for filling.
     * </p>
     *
     * @return array<TKey, TValue> The filled array.
     */
    public static function fillKeys (array $keys, mixed $value):array {

        return array_fill_keys($keys, $value);

    }

    /**
     * ### Computes the intersection of arrays using values for comparison
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array with main values to check.
     * </p>
     * @param array<TKey, TValue> ...$arrays [optional] <p>
     * An array to compare values against.
     * </p>
     *
     * @return array<TKey, TValue> The filtered array.
     *
     * @note Two elements are considered equal if and only if (string) $elem1 === (string) $elem2.
     * In words: when the string representation is the same.
     */
    public static function intersect (array $array, array ...$arrays):array {

        return array_intersect($array, ...$arrays);

    }

    /**
     * ### Computes the intersection of arrays using values for comparison by using a callback function for data comparison
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param callable(TValue $a, TValue $b):int<-1, 1> $callback <p>
     * The comparison function.
     * </p>
     * @param array<TKey, TValue> $array <p>
     * The array to compare from.
     * </p>
     * @param array<TKey, TValue> $excludes <p>
     * An array to compare against.
     * </p>
     *
     * @return array<TKey, TValue> An array containing all the entries from array1 that are not present in any of the other arrays.
     *
     * @note Two elements are considered equal if and only if (string) $elem1 === (string) $elem2.
     * In words: when the string representation is the same.
     */
    public static function intersectFunc (callable $callback, array $array, array $excludes):array {

        return array_uintersect($array, $excludes, $callback);

    }

    /**
     * ### Computes the intersection of arrays using keys for comparison
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array with main values to check.
     * </p>
     * @param array<TKey, TValue> ...$arrays [optional] <p>
     * An array to compare values against.
     * </p>
     *
     * @return array<TKey, TValue> The filtered array.
     */
    public static function intersectKey (array $array, array ...$arrays):array {

        return array_intersect_key($array, ...$arrays);

    }

    /**
     * ### Computes the intersection of arrays using keys for comparison by using a callback function for data comparison
     * @since 1.0.0
     *
     * @template TKey
     * @template TValue
     *
     * @param callable(TKey $a, TKey $b):int<-1, 1> $callback <p>
     * The comparison function.
     * </p>
     * @param array<TKey, TValue> $array <p>
     * The array to compare from.
     * </p>
     * @param array<TKey, TValue> $excludes <p>
     * An array to compare against.
     * </p>
     *
     * @return array<TKey, TValue> An array containing all the entries from array1
     * that are not present in any of the other arrays.
     */
    public static function intersectKeyFunc (callable $callback, array $array, array $excludes):array {

        return array_intersect_ukey($array, $excludes, $callback);

    }

    /**
     * ### Computes the intersection of arrays with additional index check
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array with main values to check.
     * </p>
     * @param array<TKey, TValue> ...$arrays [optional] <p>
     * An array to compare values against.
     * </p>
     *
     * @return array<TKey, TValue> The filtered array.
     *
     * @note The two values from the key → value pairs are considered equal only if (string) $elem1 === (string) $elem2.
     * In other words a strict type check is executed so the string representation must be the same.
     */
    public static function intersectAssoc (array $array, array ...$arrays):array {

        return array_intersect_assoc($array, ...$arrays);

    }

    /**
     * ### Computes the intersection of arrays with additional index check by using a callback function for value comparison
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param callable(TValue $a, TValue $b):int<-1, 1> $callback $callback <p>
     * The comparison function.
     * </p>
     * @param array<TKey, TValue> $array <p>
     * The array to compare from.
     * </p>
     * @param array<TKey, TValue> $excludes <p>
     * An array to compare against.
     * </p>
     *
     * @return array<TKey, TValue> An array containing all the entries from array1
     * that are not present in any of the other arrays.
     */
    public static function intersectAssocFuncValue (callable $callback, array $array, array $excludes):array {

        return array_uintersect_assoc($array, $excludes, $callback);

    }

    /**
     * ### Computes the intersection of arrays with additional index check by using a callback function for key comparison
     * @since 1.0.0
     *
     * @template TKey
     * @template TValue
     *
     * @param callable(TKey $a, TKey $b):int<-1, 1> $callback <p>
     * The comparison function.
     * </p>
     * @param array<TKey, TValue> $array <p>
     * The array to compare from.
     * </p>
     * @param array<TKey, TValue> $excludes <p>
     * An array to compare against.
     * </p>
     *
     * @return array<TKey, TValue> An array containing all the entries from array1
     * that are not present in any of the other arrays.
     *
     * @note The comparison function must return an integer less than, equal to, or greater than zero if the first
     * argument is considered to be respectively less than, equal to, or greater than the second.
     */
    public static function intersectAssocFuncKey (callable $callback, array $array, array $excludes):array {

        return array_intersect_uassoc($array, $excludes, $callback);

    }

    /**
     * ### Computes the intersection of arrays with additional index check by using a callback function for key and value comparison
     * @since 1.0.0
     *
     * @template TKey
     * @template TValue
     *
     * @param callable(TValue $a, TValue $b):int<-1, 1> $callback_value <p>
     * ```callable(TValue $a, TValue $b):int<-1, 1>```
     * The comparison function for value.
     * </p>
     * @param callable(TKey $a, TKey $b):int<-1, 1> $callback_key <p>
     * ```callable(TKey $a, TKey $b):int<-1, 1>```
     * The comparison function for key.
     * </p>
     * @param array<TKey, TValue> $array <p>
     * The array to compare from.
     * </p>
     * @param array<TKey, TValue> $excludes <p>
     * An array to compare against.
     * </p>
     *
     * @return array<TKey, TValue> An array containing all the entries from array1
     * that are not present in any of the other arrays.
     *
     * @note The comparison function must return an integer less than, equal to, or greater than zero
     * if the first argument is considered to be respectively less than, equal to, or greater than the second.
     */
    public static function intersectAssocFuncKeyValue (callable $callback_value, callable $callback_key, array $array, array $excludes):array {

        return array_uintersect_uassoc($array, $excludes, $callback_value, $callback_key);

    }

    /**
     * ### Exchanges all keys with their associated values in array
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue of array-key
     *
     * @param array<TKey, TValue> $array <p>
     * The array to flip.
     * </p>
     *
     * @return array<TValue, TKey> The flipped array.
     */
    public static function flip (array $array):array {

        return array_flip($array);

    }

    /**
     * ### Checks if the given key or index exists in the array
     * @since 1.0.0
     *
     * @param array-key $key <p>
     * Key to check.
     * </p>
     * @param array<int|string, mixed> $array <p>
     * An array with keys to check.
     * </p>
     *
     * @return bool True if key exist in array, false otherwise.
     *
     * @note Method will search for the keys in the first dimension only.
     * Nested keys in multidimensional arrays will not be found.
     */
    public static function keyExist (int|string $key, array $array):bool {

        return array_key_exists($key, $array);

    }

    /**
     * ### Return all the keys or a subset of the keys of an array
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * An array containing keys to return.
     * </p>
     * @param TValue $filter [optional] <p>
     * If specified, then only keys containing these values are returned.
     * </p>
     *
     * @return array<TKey> An array of all the keys in input.
     */
    public static function keys (array $array, mixed $filter = null):array {

        return $filter
            ? array_keys($array, $filter, true)
            : array_keys($array);

    }

    /**
     * ### Get first key from array
     * @since 1.0.0
     *
     * @template TKey of array-key
     *
     * @param array<TKey, mixed> $array <p>
     * An array.
     * </p>
     *
     * @return null|TKey First key from array or null if array is empty.
     */
    public static function firstKey (array $array):null|int|string {

        return array_key_first($array);

    }

    /**
     * ### Get last key from array
     * @since 1.0.0
     *
     * @template TKey of array-key
     *
     * @param array<TKey, mixed> $array <p>
     * An array.
     * </p>
     *
     * @return null|TKey Last key from array or null if array is empty.
     */
    public static function lastKey (array $array):null|int|string {

        return array_key_last($array);

    }

    /**
     * ### Applies the callback to the elements of the given array
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * Array to run through the callback function.
     * </p>
     * @param callable(TValue $value):mixed $callback <p>
     * Callback function to run for each element in each array.
     * </p>
     *
     * @return array<TKey, mixed> Array containing all the elements of arr1 after applying the callback function.
     */
    public static function map (array $array, callable $callback):array {

        return array_map($callback, $array);

    }

    /**
     * ### Merges the elements of one or more arrays together
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> ...$arrays [optional] <p>
     * Variable list of arrays to merge.
     * </p>
     *
     * @return array<TKey, TValue>, TValue The resulting array.
     *
     * @note If the input arrays have the same string keys, then the later value for that key will overwrite the previous one.
     * @note If the arrays contain numeric keys, the later value will be appended.
     * @note Numeric keys will be renumbered.
     */
    public static function merge (array ...$arrays):array {

        return array_merge(...$arrays);

    }

    /**
     * ### Merge two or more arrays recursively
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> ...$arrays [optional] <p>
     * Variable list of arrays to recursively merge.
     * </p>
     *
     * @return array<TKey, TValue> The resulting array.
     *
     * @note If the input arrays have the same string keys,
     * then the values for these keys are merged together into an array, and this is done recursively,
     * so that if one of the values is an array itself, the function will merge it with
     * a corresponding entry in another array too.
     * If, however, the arrays have the same numeric key, the later value will not overwrite the original value,
     * but will be appended.
     */
    public static function mergeRecursive (array ...$arrays):array {

        return array_merge_recursive(...$arrays);

    }

    /**
     * ### Sort multiple or multidimensional arrays
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::firstKey() To get first key from array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::column() To return the values from a single column in the input array.
     * @uses \FireHub\Core\Support\Enums\Order::DESC As order enum.
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<array<TKey, TValue>> &$array <p>
     * A multidimensional array being sorted.
     * </p>
     * @param array<array<TKey, string|\FireHub\Core\Support\Enums\Order>> $fields <p>
     * List of fields to sort by.
     * </p>
     *
     * @return bool True on success, false otherwise.
     *
     * @note Resets array's internal pointer to the first element.
     */
    public static function sortByMany (array &$array, array $fields):bool {

        $multi_sort = [];

        foreach ($fields as $field) {

            $column = self::firstKey($field);
            $order = isset($column) ? $field[$column] : null;

            $order = match($order) {
                Order::DESC => SORT_DESC,
                default => SORT_ASC
            };

            /** @phpstan-ignore-next-line */
            $multi_sort[] = [...self::column($array, $column)];

            $multi_sort[] = $order;

        }

        $multi_sort[] = &$array;

        try {

            /** @phpstan-ignore-next-line */
            array_multisort(...$multi_sort);

        } catch (Throwable) {

            return false;

        }

        return true;

    }

    /**
     * ### Pad array to the specified length with a value
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     * @template TPadValue
     *
     * @param array<TKey, TValue> $array <p>
     * Initial array of values to pad.
     * </p>
     * @param int $length <p>
     * New size of the array.
     * If length is positive then the array is padded on the right, if it's negative then on the left.
     * If the absolute value of length is less than or equal to the length of the array then no padding takes place.
     * </p>
     * @param TPadValue $value <p>
     * Value to pad if input is less than length.
     * </p>
     *
     * @return array<int|TKey, TValue|TPadValue> A copy of the input padded to size specified by pad_size with value
     * pad_value.
     *
     * @caution Keys can be re-numbered.
     */
    public static function pad (array $array, int $length, mixed $value):array {

        return array_pad($array, $length, $value);

    }

    /**
     * ### Calculate the product of values in an array
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $array <p>
     * The array.
     * </p>
     *
     * @return int|float The product of values in an array.
     */
    public static function product (array $array):int|float {

        return array_product($array);

    }

    /**
     * ### Pick one or more random keys out of an array
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $array <p>
     * The input array.
     * </p>
     * @param positive-int $number [optional] <p>
     * Specifies how many entries should be picked.
     * </p>
     *
     * @return array<int, array-key>|int|string|false When picking only one entry, array_rand() returns the key for a
     * random entry. Otherwise, an array of keys for the random entries is returned.
     */
    public static function random (array $array, int $number = 1):int|string|array|false {

        try {

            return array_rand($array, $number);

        } catch (Throwable) {

            return false;

        }

    }

    /**
     * ### Iteratively reduce the array to a single value using a callback function
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $array <p>
     * The input array.
     * </p>
     * @param callable(mixed $carry, mixed $item):mixed $callback <p>
     * The callable function.
     * </p>
     * @param mixed $initial [optional] <p>
     * If the optional initial is available, it will be used at the beginning of the process,
     * or as a final result in case the array is empty.
     * </p>
     *
     * @return mixed Resulting value or null if the array is empty and initial is not passed.
     */
    public static function reduce (array $array, callable $callback, mixed $initial = null):mixed {

        return array_reduce($array, $callback, $initial);

    }

    /**
     * ### Replaces elements from passed arrays into the first array
     * @since 1.0.0
     *
     * @template TValue
     *
     * @param array<TValue> $array <p>
     * The array in which elements are replaced.
     * </p>
     * @param array<TValue> ...$replacements<p>
     * Arrays from which elements will be extracted. Values from later arrays overwrite the previous values.
     * </p>
     *
     * @return array<TValue> The resulting array.
     */
    public static function replace (array $array, array ...$replacements):array {

        return array_replace($array, ...$replacements);

    }

    /**
     * ### Replace two or more arrays recursively
     * @since 1.0.0
     *
     * @template TValue
     *
     * @param array<TValue> $array <p>
     * The array in which elements are replaced.
     * </p>
     * @param array<TValue> ...$replacements<p>
     * Arrays from which elements will be extracted. Values from later arrays overwrite the previous values.
     * </p>
     *
     * @return array<TValue> The resulting array.
     *
     * @note If the input arrays have the same string keys, then the values for these keys are merged together
     * into an array, and this is done recursively, so that if one of the values is an array itself,
     * the function will merge it with a corresponding entry in another array too.
     * If, however, the arrays have the same numeric key, the later value will not overwrite the original value, but
     * will be appended.
     */
    public static function replaceRecursive (array $array, array ...$replacements):array {

        return array_replace_recursive($array, ...$replacements);

    }

    /**
     * ### Reverse the order of array items
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * Array to reverse.
     * </p>
     * @param bool $preserve_keys [optional] <p>
     * Whether you want to preserve keys from original array or not.
     * Non-numeric keys are not affected by this setting and will always be preserved.
     * </p>
     *
     * @return ($preserve_keys is true ? array<TKey, TValue> : array<array-key, TValue>) The reversed array.
     */
    public static function reverse (array $array, bool $preserve_keys = false):array {

        return array_reverse($array, $preserve_keys);

    }

    /**
     * ### Searches the array for a given value and returns the first corresponding key if successful
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param mixed $value <p>
     * The searched value.
     * </p>
     * @param array<TKey, TValue> $array <p>
     * Array to search.
     * </p>
     *
     * @return TKey|false The key for value if it is found in the array, false otherwise.
     */
    public static function search (mixed $value, array $array):int|string|false {

        return array_search($value, $array, true);

    }

    /**
     * ### Extract a slice of the array
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The input array.
     * </p>
     * @param int $offset <p>
     * If offset is non-negative, the sequence will start at that offset in the array.
     * If offset is negative, the sequence will start that far from the end of the array.
     * </p>
     * @param null|int $length [optional] <p>
     * If length is given and is positive, then the sequence will have that many elements in it.
     * If length is given and is negative then the sequence will stop that many elements from the end of the array.
     * If it is omitted, then the sequence will have everything from offset up until the end of the array.
     * </p>
     * @param bool $preserve_keys [optional] <p>
     * Note that array_slice will reorder and reset the array indices by default.
     * You can change this behaviour by setting preserve_keys to true.
     * </p>
     *
     * @return ($preserve_keys is true ? array<TKey, TValue> : array<TValue>) Sliced array.
     */
    public static function slice (array $array, int $offset, int $length = null, bool $preserve_keys = false):array {

        return array_slice($array, $offset, $length, $preserve_keys);

    }

    /**
     * ### Sorts array
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\Order::ASC As parameter.
     * @uses \FireHub\Core\Support\Enums\Sort::SORT_REGULAR As parameter.
     *
     * @param array<array-key, mixed> &$array <p>
     * Array to sort.
     * </p>
     * @param \FireHub\Core\Support\Enums\Order $order [optional] <p>
     * Order type.
     * </p>
     * @param bool $preserve_keys [optional] <p>
     * Whether you want to preserve keys from original array or not.
     * </p>
     * @param \FireHub\Core\Support\Enums\Sort $flag [optional] <p>
     * Sorting flag.
     * </p>
     *
     * @return true Always true.
     *
     * @note Resets array's internal pointer to the first element.
     */
    public static function sort (array &$array, Order $order = Order::ASC, bool $preserve_keys = false, Sort $flag = Sort::SORT_REGULAR):true {

        /**
         * PHPStan report return bool, but that is wrong.
         * @phpstan-ignore-next-line
         */
        return $order === Order::ASC
            ? ($preserve_keys
                ? asort($array, $flag->value)
                : sort($array, $flag->value))
            : ($preserve_keys
                ? arsort($array, $flag->value)
                : rsort($array, $flag->value));

    }

    /**
     * ### Sorts array by key
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\Order::ASC As parameter.
     * @uses \FireHub\Core\Support\Enums\Sort::SORT_REGULAR As parameter.
     *
     * @param array<int|string, mixed> &$array <p>
     * Array to sort.
     * </p>
     * @param \FireHub\Core\Support\Enums\Order $order [optional] <p>
     * Order type.
     * </p>
     * @param \FireHub\Core\Support\Enums\Sort $flag [optional] <p>
     * Sorting flag.
     * </p>
     *
     * @return true Always true.
     *
     * @note Resets array's internal pointer to the first element.
     */
    public static function sortByKey (array &$array, Order $order = Order::ASC, Sort $flag = Sort::SORT_REGULAR):true {

        return $order === Order::ASC
            ? ksort($array, $flag->value)
            : krsort($array, $flag->value);

    }

    /**
     * ### Sorts array by values using a user-defined comparison function
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> &$array <p>
     * Array to sort.
     * </p>
     * @param callable(TValue $a, TValue $b):int<-1, 1> $callback <p>
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument
     * is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     * @param bool $preserve_keys [optional] <p>
     * Whether you want to preserve keys from original array or not.
     * </p>
     *
     * @return true Always true.
     *
     * @note Resets array's internal pointer to the first element.
     */
    public static function sortBy (array &$array, callable $callback, bool $preserve_keys = false):true {

        return $preserve_keys
            ? uasort($array, $callback)
            : usort($array, $callback);

    }

    /**
     * ### Sorts array by key using a user-defined comparison function
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> &$array <p>
     * Array to sort.
     * </p>
     * @param callable(TKey $a, TKey $b):int<-1, 1> $callback <p>
     * The callback comparison function. Function cmp_function should accept two parameters which will be filled
     * by pairs of array keys. The comparison function must return an integer less than, equal to, or greater than
     * zero if the first argument is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     *
     * @return true Always true.
     *
     * @note Resets array's internal pointer to the first element.
     */
    public static function sortKeyBy (array &$array, callable $callback):true {

        return uksort($array, $callback);

    }

    /**
     * ### Remove a portion of the array and replace it with something else
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * Array to splice.
     * </p>
     * @param int $offset <p>
     * If offset is positive then the start of removed portion is at that offset from the beginning of the input array.
     * If offset is negative then it starts that far from the end of the input array.
     * </p>
     * @param null|int $length [optional] <p>
     * If length is omitted, removes everything from offset to the end of the array.
     * If length is specified and is positive, then that many elements will be removed.
     * If length is specified and is negative then the end of the removed portion will be that many elements from
     * the end of the array.
     * </p>
     * @param mixed $replacement [optional] <p>
     * If replacement array is specified, then the removed elements are replaced with elements from this array.
     * If offset and length are such that nothing is removed, then the elements from the replacement array or array
     * was inserted in the place specified by the offset.
     * Keys in replacement array are not preserved.
     * </p>
     *
     * @return array<TKey|int, TValue> Spliced array.
     *
     * @note Numerical keys in array are not preserved.
     * @note If replacement is not an array, it will be typecast to one (i.e. (array) $replacement).
     * This may result in unexpected behavior when using an object or null replacement.
     */
    public static function splice (array &$array, int $offset, int $length = null, mixed $replacement = []):array {

        return array_splice($array, $offset, $length, $replacement);

    }

    /**
     * ### Calculate the sum of values in an array
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $array <p>
     * The input array.
     * </p>
     *
     * @return int|float The sum of values as an integer or float; 0 if the array is empty.
     */
    public static function sum (array $array):int|float {

        return array_sum($array);

    }

    /**
     * ### Removes duplicate values from an array
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array to remove duplicates.
     * </p>
     *
     * @return array<TKey, TValue> The filtered array.
     *
     * @note New array will preserve associative keys, and reindex others.
     * @note This method is not intended to work on multidimensional arrays.
     */
    public static function unique (array $array):array {

        return array_unique($array, SORT_REGULAR);

    }

    /**
     * ### Return all the values from array
     * @since 1.0.0
     *
     * @template TValue
     *
     * @param array<array-key, TValue> $array <p>
     * The array.
     * </p>
     *
     * @return array<TValue> An indexed array of values.
     */
    public static function values (array $array):array {

        return array_values($array);

    }

    /**
     * ### Apply a user function to every member of an array
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array to apply a user function.
     * </p>
     * @param callable(TValue $value, TKey $key):mixed $callback <p>
     * Typically, function name takes on two parameters. The array parameter's value being the first, and the key/index second.
     * If function name needs to be working with the actual values of the array,
     * specify the first parameter of function name as a reference.
     * Then, any changes made to those elements will be made in the original array itself.
     * Users may not change the array itself from the callback function. e.g. Add/delete elements, unset elements, etc.
     * If the array that walk is applied to is changed, the behavior of this function is undefined, and unpredictable.
     * </p>
     *
     * @return bool True on success, false otherwise.
     */
    public static function walk (array &$array, callable $callback):bool {

        return array_walk($array, $callback);

    }

    /**
     * ### Apply a user function recursively to every member of an array
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array to apply a user function.
     * </p>
     * @param callable(TValue $value, TKey $key):mixed $callback <p>
     * Typically, function name takes on two parameters. The array parameter's value being the first, and the key/index second.
     * If function name needs to be working with the actual values of the array,
     * specify the first parameter of function name as a reference.
     * Then, any changes made to those elements will be made in the original array itself.
     * Users may not change the array itself from the callback function. e.g. Add/delete elements, unset elements, etc.
     * If the array that walk is applied to is changed, the behavior of this function is undefined, and unpredictable.
     * </p>
     *
     * @return bool True on success, false otherwise.
     */
    public static function walkRecursive (array &$array, callable $callback):bool {

        return array_walk_recursive($array, $callback);

    }

    /**
     * ### Checks if a value exists in an array
     * @since 1.0.0
     *
     * @param mixed $value <p>
     * The searched value.
     * note: If needle is a string, the comparison is done in a case-sensitive manner.
     * </p>
     * @param array<array-key, mixed> $array <p>
     * The array.
     * </p>
     *
     * @return bool True if value is found in the array, false otherwise.
     */
    public static function inArray (mixed $value, array $array):bool {

        return in_array($value, $array, true);

    }

    /**
     * ### Create an array containing a range of elements
     * @since 1.0.0
     *
     * @template TValue of int|float|string
     *
     * @param TValue $start <p>
     * First value of the sequence.
     * </p>
     * @param TValue $end <p>
     * The sequence is ended upon reaching the end value.
     * </p>
     * @param int|float $step [optional] <p>
     * If a step value is given, it will be used as the increment between elements in the sequence.
     * Step should be given as a positive number. If not specified, step will default to 1.
     * </p>
     *
     * @return array<int, TValue>|false An array of elements from start to end, inclusive, false otherwise.
     */
    public static function range (int|float|string $start, int|float|string $end, int|float $step = 1):array|false {

        try {

            return range($start, $end, $step);

        } catch (Throwable) {

            return false;

        }

    }

    /**
     * ### Shuffle an array
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $array <p>
     * The array.
     * </p>
     *
     * @return true Always returns true.
     */
    public static function shuffle (array &$array):true {

        return shuffle($array);

    }

}