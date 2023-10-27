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

use FireHub\Core\Base\ {
    BaseStatic, MasterStatic
};
use FireHub\Core\Support\Enums\{
    Order, Sort
};
use ArgumentCountError, Error, ValueError;

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
use function current;
use function end;
use function rsort;
use function in_array;
use function key;
use function krsort;
use function ksort;
use function next;
use function prev;
use function range;
use function reset;
use function shuffle;
use function sort;
use function uasort;
use function uksort;
use function usort;

/**
 * ### Array low-level class
 *
 * An array in PHP is actually an ordered map. A map is a type that associates values to keys. This type is
 * optimized for several different uses; it can be treated as an array, list (vector),
 * hash table (an implementation of a map), dictionary, collection, stack, queue, and probably more.
 * As array values can be other arrays, trees and multidimensional arrays are also possible.
 * @since 1.0.0
 */
final class Arr implements MasterStatic {

    /**
     * ### FireHub base static class trait
     * @since 1.0.0
     */
    use BaseStatic;

    /**
     * ### Checks whether a given array is a list
     *
     * Determines if the given array is a list.
     * An array is considered a list if its keys consist of consecutive numbers from 0 to count($array)-1.
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $array <p>
     * The array being evaluated.
     * </p>
     *
     * @return ($array is list ? true: false) True if an array is a list, false otherwise.
     *
     * @note This function returns true on empty arrays.
     */
    public static function isList (array $array):bool {

        return array_is_list($array);

    }

    /**
     * ### Pop the element off the end of array
     *
     * Pops and returns the last element value of the array, shortening the array by one element.
     * @since 1.0.0
     *
     * @template TValue
     *
     * @param array<array-key, TValue> &$array <p>
     * The array to get the value from.
     * </p>
     *
     * @return TValue|null The last value of an array. If an array is empty (or is not an array), null will be returned.
     *
     * @note This function will reset the array pointer of the input array after use.
     */
    public static function pop (array &$array):mixed {

        return array_pop($array);

    }

    /**
     * ### Push elements onto the end of array
     *
     * Method treats an array as a stack, and pushes the passed variables onto the end of array.
     * The length of array increases by the number of variables pushed.
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
     *
     * Shifts the first value of the array off and returns it, shortening the array by one element and moving
     * everything down. All numerical array keys will be modified to start counting from zero while literal keys won't
     * be affected.
     * @since 1.0.0
     *
     * @template TValue
     *
     * @param array<array-key, TValue> &$array <p>
     * Array to shift.
     * </p>
     *
     * @return TValue|null The shifted value, or null if an array is empty or is not an array.
     *
     * @note This function will reset the array pointer of the input array after use.
     */
    public static function shift (array &$array):mixed {

        return array_shift($array);

    }

    /**
     * ### Prepend one or more elements to the beginning of an array
     *
     * Method prepends passed elements to the front of the array.
     * Note that the list of elements is prepended as a whole, so that the prepended elements stay in the same order.
     * All numerical array keys will be modified to start counting from zero while literal keys won't be changed.
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
     * @param bool $multidimensional [optional] <p>
     * Count multidimensional items.
     * </p>
     *
     * @error\exeption E_WARNING if multidimensional is on and method detect recursion to avoid an infinite loop.
     *
     * @return non-negative-int Number of elements in an array.
     */
    public static function count (array $array, bool $multidimensional = false):int {

        return count($array, $multidimensional ? COUNT_RECURSIVE : COUNT_NORMAL);

    }

    /**
     * ### Counts all the values of an array
     *
     * Returns an array using the values of array (which must be int-s or strings)
     * as keys and their frequency in an array as values.
     * @since 1.0.0
     *
     * @template TValue of array-key
     *
     * @param array<array-key, TValue> $array <p>
     * The array of values to count.
     * </p>
     *
     * @error\exeption E_WARNING for every element that is not string or int.
     *
     * @return array<TValue, positive-int> An associative array of values from input as keys and their count
     * as value.
     */
    public static function countValues (array $array):array {

        return array_count_values($array);

    }

    /**
     * ### Split an array into chunks
     *
     * Chunks an array into arrays with length elements.
     * The last chunk may contain less than length elements.
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
     * If the length is less than 1, it will default to 1.
     * </p>
     * @param bool $preserve_keys [optional] <p>
     * When set to true, keys will be preserved.
     * Default is false that will reindex the chunk numerically.
     * </p>
     *
     * @throws ValueError If length is less than 1.
     *
     * @return ($preserve_keys is true ? list<array<TKey, TValue>> : list<list<TValue>>)
     * Multidimensional numerically indexed array,
     * starting with zero, with each dimension containing size elements.
     */
    public static function chunk (array $array, int $length, bool $preserve_keys = false):array {

        return array_chunk($array, $length, $preserve_keys);

    }

    /**
     * ### Return the values from a single column in the input array
     *
     * Returns the values from a single column of the array, identified by the argument key. Optionally, an argument key
     * may be provided to index the values in the returned array by the values from the index argument column of the
     * input array.
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
     *
     * Creates an array by using the values from the $keys array as keys and the values from the $values array as the
     * corresponding values.
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<array-key, TKey> $keys <p>
     * Array of values to be used as keys.
     * Illegal values for a key will be converted to string.
     * </p>
     * @param array<array-key, TValue> $values <p>
     * Array of values to be used as values on a combined array.
     * </p>
     *
     * @throws ValueError If arguments $keys and $values don't have the same number of elements.
     *
     * @return array<TKey, TValue> The combined array.
     */
    public static function combine (array $keys, array $values):array {

        return array_combine($keys, $values);

    }

    /**
     * ### Computes the difference of arrays using values for comparison
     *
     * Compares an array against one or more other arrays and returns the values in array that are not present in any of
     * the other arrays.
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
     * @return array<TKey, TValue> An array containing all the entries from $array
     * that are not present in any of the other arrays.
     */
    public static function difference (array $array, array ...$excludes):array {

        return array_diff($array, ...$excludes);

    }

    /**
     * ### Computes the difference of arrays using values for comparison by using a callback function for data
     * comparison
     *
     * Computes the difference of arrays by using a callback function for data comparison. This is unlike difference()
     * which uses an internal function for comparing the data.
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
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument
     * is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     *
     * @return array<TKey, TValue> An array containing all the entries from array1
     * that are not present in any of the other arrays.
     *
     * @caution Returning non-integer values from the comparison function, such as float, will result in an internal
     * cast to int of the callback's return value. So values such as 0.99 and 0.1 will both be cast to an integer
     * value of 0, which will compare such values as equal.
     *
     */
    public static function differenceFunc (array $array, array $excludes, callable $callback):array {

        return array_udiff($array, $excludes, $callback);

    }

    /**
     * ### Computes the difference of arrays using keys for comparison
     *
     * Compares the keys from array against the keys from arrays and returns the difference. This function is like
     * difference() except the comparison is done on the keys instead of the values.
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
     * @return array<TKey, TValue> Returns an array containing all the entries from array whose keys are absent from
     * all the other arrays.
     */
    public static function differenceKey (array $array, array ...$excludes):array {

        return array_diff_key($array, ...$excludes);

    }

    /**
     * ### Computes the difference of arrays using keys for comparison by using a callback function for data comparison
     *
     * Compares the keys from array against the keys from arrays and returns the difference. This function is like
     * difference() except the comparison is done on the keys instead of the values.
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
    public static function differenceKeyFunc (array $array, array $excludes, callable $callback):array {

        /** @phpstan-ignore-next-line */
        return array_diff_ukey($array, $excludes, $callback);

    }

    /**
     * ### Computes the difference of arrays with additional index check
     *
     * Compares an array against arrays and returns the difference.
     * Unlike difference(), the array keys are also used in the comparison.
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
     * ### Computes the difference of arrays with additional index check by using a callback function for value
     * comparison
     *
     * Computes the difference of arrays with additional index check, compares data by a callback function.
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
    public static function differenceAssocFuncValue (array $array, array $excludes, callable $callback):array {

        return array_udiff_assoc($array, $excludes, $callback);

    }

    /**
     * ### Computes the difference of arrays with additional index check by using a callback function for key comparison
     *
     * Compares an array against arrays and returns the difference.
     * Unlike difference(), the array keys are used in the comparison.
     * Unlike differenceAssoc(), a user-supplied callback function is used for the indices comparison, not internal
     * function.
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
     * ### Computes the difference of arrays with additional index check by using a callback function for key and
     * value comparison
     *
     * Computes the difference of arrays with additional index check, compares data and indexes by a callback function.
     * Note that the keys are used in the comparison unlike difference() and differenceFunc().
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
     *
     * Iterates over each value in the array passing them to the callback function.
     * If the callback function returns true, the current value from an array is returned into the result array.
     * Array keys are preserved, and may result in gaps if the array was indexed. The result array can be re-indexed
     * using the values() function.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::null To find whether a variable is null.
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array to iterate over.
     * </p>
     * @param null|callable(TValue $value, TKey $key):bool $callback [optional] <p>
     * The callback function to use.
     * If no callback is supplied, all empty and false entries of an array will be removed.
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
     * @caution If the array is changed from the callback function (e.g., an element added, deleted or unset)
     * the behavior of this function is undefined.
     */
    public static function filter (array $array, callable $callback = null, bool $pass_key = false, bool $pass_value = true):array {

        if (DataIs::null($callback)) return array_filter($array);

        /** @phpstan-ignore-next-line */
        return array_filter($array, $callback,
            $pass_key && $pass_value
                ? ARRAY_FILTER_USE_BOTH
                : ($pass_key ? ARRAY_FILTER_USE_KEY : 0)
        );

    }

    /**
     * ### Fill an array with valued
     *
     * Fills an array with count entries of the value of the value parameter,
     * keys starting at the start_index parameter.
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
     * @throws ValueError If $length is out of range.
     *
     * @return array<int, TValue> Filled array.
     */
    public static function fill (mixed $value, int $start_index, int $length):array {

        return array_fill($start_index, $length, $value);

    }

    /**
     * ### Fill an array with values, specifying keys
     *
     * Fills an array with the value of the value parameter, using the values of the $keys array as keys.
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<array-key, TKey> $keys <p>
     * Array of values that will be used as keys.
     * Illegal values for a key will be converted to string.
     * </p>
     * @param TValue $value p>
     * Value to use for filling.
     * </p>
     *
     * @throws Error If key could not be converted to string.
     *
     * @return array<TKey, TValue> The filled array.
     */
    public static function fillKeys (array $keys, mixed $value):array {

        return array_fill_keys($keys, $value);

    }

    /**
     * ### Computes the intersection of arrays using values for comparison
     *
     * Returns an array containing all the values of array that are present in all the arguments.
     * Note that keys are preserved.
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
     * ### Computes the intersection of arrays using values for comparison by using a callback function for data
     * comparison
     *
     * Computes the intersection of arrays, compares data by a callback function.
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
     * @return array<TKey, TValue> Arrays containing all the entries from $array that are not present in any of the
     * other arrays.
     *
     * @note Two elements are considered equal if and only if (string) $elem1 === (string) $elem2.
     * In words: when the string representation is the same.
     */
    public static function intersectFunc (callable $callback, array $array, array $excludes):array {

        return array_uintersect($array, $excludes, $callback);

    }

    /**
     * ### Computes the intersection of arrays using keys for comparison
     *
     * Returns an array containing all the entries of array which have keys that are present in all the arguments.
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
     * ### Computes the intersection of arrays using keys for comparison by using a callback function for data
     * comparison
     *
     * Returns an array containing all the values of array which have matching keys
     * that are present in all the arguments.
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
     * @return array<TKey, TValue> An array containing all the entries from $array
     * that are not present in any of the other arrays.
     */
    public static function intersectKeyFunc (callable $callback, array $array, array $excludes):array {

        return array_intersect_ukey($array, $excludes, $callback);

    }

    /**
     * ### Computes the intersection of arrays with additional index check
     *
     * Returns an array containing all the values of array that are present in all the arguments.
     * Note that the keys are also used in the comparison unlike in intersect().
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
     * In other words, a strict type check is executed, so the string representation must be the same.
     */
    public static function intersectAssoc (array $array, array ...$arrays):array {

        return array_intersect_assoc($array, ...$arrays);

    }

    /**
     * ### Computes the intersection of arrays with additional index check by using a callback function for value
     * comparison
     *
     * Computes the intersection of arrays with additional index check, compares data by a callback function.
     * Note that the keys are used in the comparison unlike in intersectFunc().
     * The data is compared by using a callback function.
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
     * ### Computes the intersection of arrays with additional index check by using a callback function for key
     * comparison
     *
     * Computes the intersection of arrays with additional index check, compares data and indexes by separate
     * callback functions.
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
     * ### Computes the intersection of arrays with additional index check by using a callback function for key and
     * value comparison
     *
     * Computes the intersection of arrays with additional index check, compares data and indexes by separate
     * callback functions.
     * @since 1.0.0
     *
     * @template TKey
     * @template TValue
     *
     * @param callable(TValue $a, TValue $b):int<-1, 1> $callback_value <p>
     * The comparison function for value.
     * </p>
     * @param callable(TKey $a, TKey $b):int<-1, 1> $callback_key <p>
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
     * ### Exchanges all keys with their associated values in an array
     *
     * Returns an array in flip order; i.e., keys from an array become values and values from an array become keys.
     * Note that the values of array need to be valid keys, i.e., they need to be either int or string.
     * A warning will be emitted if a value has the wrong type,
     * and the key/value pair in question will not be included in the result.
     * If a value has several occurrences, the latest key will be used as its value, and all others will be lost.
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue of array-key
     *
     * @param array<TKey, TValue> $array <p>
     * The array to flip.
     * </p>
     *
     * @error\exeption E_WARNING if values on $array argument are neither int nor string.
     *
     * @return array<TValue, TKey> The flipped array.
     */
    public static function flip (array $array):array {

        return array_flip($array);

    }

    /**
     * ### Checks if the given key or index exists in the array
     *
     * Returns true if the given key is set in the array. Key can be any value possible for an array index.
     * @since 1.0.0
     *
     * @param array-key $key <p>
     * Key to check.
     * </p>
     * @param array<int|string, mixed> $array <p>
     * An array with keys to check.
     * </p>
     *
     * @return bool True if key exists in an array, false otherwise.
     *
     * @note Method will search for the keys in the first dimension only.
     * Nested keys in multidimensional arrays will not be found.
     */
    public static function keyExist (int|string $key, array $array):bool {

        return array_key_exists($key, $array);

    }

    /**
     * ### Return all the keys or a subset of the keys of an array
     *
     * Returns the keys, numeric and string, from the array.
     * If a $filter is specified, then only the keys for that value are returned.
     * Otherwise, all the keys from the array are returned.
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
     * ### Get first key from an array
     *
     * Get the first key of the given array without affecting the internal array pointer.
     * @since 1.0.0
     *
     * @template TKey of array-key
     *
     * @param array<TKey, mixed> $array <p>
     * An array.
     * </p>
     *
     * @return null|TKey First key from $array or null if an array is empty.
     */
    public static function firstKey (array $array):null|int|string {

        return array_key_first($array);

    }

    /**
     * ### Get last key from array
     *
     * Get the last key of the given array without affecting the internal array pointer.
     * @since 1.0.0
     *
     * @template TKey of array-key
     *
     * @param array<TKey, mixed> $array <p>
     * An array.
     * </p>
     *
     * @return null|TKey Last key from $array or null if an array is empty.
     */
    public static function lastKey (array $array):null|int|string {

        return array_key_last($array);

    }

    /**
     * ### Applies the callback to the elements of the given array
     *
     * Returns an array containing the results of applying the callback to the corresponding value of an array (and
     * arrays if more arrays are provided) used as arguments for the callback.
     * The number of parameters that the callback function accepts should match the number of arrays passed to map().
     * Excess input arrays are ignored.
     * An ArgumentCountError is thrown if an insufficient number of arguments is provided.
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
     * Null can be passed as a value to $callback to perform a zip operation on multiple arrays.
     * If only an array is provided, map() will return the input array.
     * </p>
     *
     * @throws ArgumentCountError If an insufficient number of arguments is provided.
     *
     * @return array<TKey, mixed> Array containing all the elements of arr1 after applying the callback function.
     */
    public static function map (array $array, callable $callback):array {

        return array_map($callback, $array);

    }

    /**
     * ### Merges the elements of one or more arrays together
     *
     * Merges the elements of one or more arrays together so that the values of one are appended
     * to the end of the previous one. It returns the resulting array.
     * If the input arrays have the same string keys, then the later value for that key will overwrite the previous one.
     * If, however, the arrays contain numeric keys, the later value will not overwrite the original value, but will
     * be appended.
     * Values in the input arrays with numeric keys will be renumbered with incrementing keys starting from zero in
     * the result array.
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> ...$arrays [optional] <p>
     * Variable list of arrays to merge.
     * </p>
     *
     * @return array<TKey, TValue> The resulting array.
     *
     * @note If the input arrays have the same string keys, then the later value for that key will overwrite the
     * previous one.
     * @note If the arrays contain numeric keys, the later value will be appended.
     * @note Numeric keys will be renumbered.
     */
    public static function merge (array ...$arrays):array {

        return array_merge(...$arrays);

    }

    /**
     * ### Merge two or more arrays recursively
     *
     * Merges the elements of one or more arrays together so that the values of one are appended to the end of the
     * previous one. It returns the resulting array.
     * If the input arrays have the same string keys, then the values for these keys are merged into an array,
     * and this is done recursively, so that if one of the values is an array itself, the function will merge it with
     * a corresponding entry in another array too. If, however, the arrays have the same numeric key, the later value
     * will not overwrite the original value, but will be appended.
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
     */
    public static function mergeRecursive (array ...$arrays):array {

        return array_merge_recursive(...$arrays);

    }

    /**
     * ### Sort multiple or multidimensional arrays
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $multi_sort <p>
     * A multidimensional array being sorted.
     * </p>
     *
     * @throws ValueError If array sizes are inconsistent.
     *
     * @return bool True on success, false otherwise.
     *
     * @note Resets array's internal pointer to the first element.
     */
    public static function multiSort (array $multi_sort):bool {

        /** @phpstan-ignore-next-line */
        return array_multisort(...$multi_sort);

    }

    /**
     * ### Pad array to the specified length with a value
     *
     * Returns a copy of the array padded to size specified by $length with value $value.
     * If the length is positive, then the array is padded on the right if it's negative then on the left.
     * If the absolute value of length is less than or equal to the length of the array, then no padding takes place.
     * It is possible to add at most 1048576 elements at a time.
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
     * If the length is positive, then the array is padded on the right if it's negative then on the left.
     * If the absolute value of length is less than or equal to the length of the array, then no padding takes place.
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
     *
     * Returns the product of values in an array.
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $array <p>
     * The array.
     * </p>
     *
     * @return int|float The product as an integer or float.
     */
    public static function product (array $array):int|float {

        return array_product($array);

    }

    /**
     * ### Pick one or more random keys out of an array
     *
     * Picks one or more random entries out of an array, and returns the key (or keys) of the random entries.
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $array <p>
     * The input array.
     * </p>
     * @param positive-int $number [optional] <p>
     * Specifies how many entries should be picked.
     * </p>
     *
     * @throws ValueError If $number is not between 1 and the number of elements in argument.
     *
     * @return array<int, array-key>|int|string When picking only one entry, array_rand() returns the key for a
     * random entry. Otherwise, an array of keys for the random entries is returned.
     */
    public static function random (array $array, int $number = 1):int|string|array {

        return array_rand($array, $number);

    }

    /**
     * ### Iteratively reduce the array to a single value using a callback function
     *
     * Iteratively applies the callback function to the elements of the array, to reduce the array to a single value.
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
     *
     * Replaces the values of array with values having the same keys in each of the following arrays.
     * If a key from the first array exists in the second array, its value will be replaced by the value from the
     * second array. If the key exists in the second array, and not the first, it will be created in the first array.
     * If a key only exists in the first array, it will be left as is. If several arrays are passed for replacement,
     * they will be processed in order, the later arrays overwriting the previous values.
     * Method is not recursive, it will replace values in the first array by whatever type is in the second array.
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
     *
     * Replaces the values of array with the same values from all the following arrays.
     * If a key from the first array exists in the second array, its value will be replaced by the value from the
     * second array. If the key exists in the second array, and not the first, it will be created in the first array.
     * If a key only exists in the first array, it will be left as is. If several arrays are passed for replacement,
     * they will be processed in order, the later array overwriting the previous values.
     *
     * When the value in the first array is scalar, it will be replaced by the value in the second array, may it be
     * scalar or array. When the value in the first array and the second array are both arrays, replaceRecursive() will
     * replace their respective values recursively.
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
    public static function replaceRecursive (array $array, array ...$replacements):array {

        return array_replace_recursive($array, ...$replacements);

    }

    /**
     * ### Reverse the order of array items
     *
     * Takes an input array and returns a new array with the order of the elements reversed.
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * Array to reverse.
     * </p>
     * @param bool $preserve_keys [optional] <p>
     * Whether you want to preserve keys from an original array or not.
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
     * If $value is a string, the comparison is done in a case-sensitive manner.
     * </p>
     * @param array<TKey, TValue> $array <p>
     * Array to search.
     * </p>
     *
     * @return TKey|false The key for value if it is found in the array, false otherwise.
     *
     * @warning This method may return Boolean false, but may also return a non-Boolean value which evaluates to false.
     * Please read the section on Booleans for more information. Use the === operator for testing the return value of
     * this function.
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
     * If the offset is non-negative, the sequence will start at that offset in the array.
     * If the offset is negative, the sequence will start that far from the end of the array.
     * </p>
     * @param null|int $length [optional] <p>
     * If length is given and is positive, then the sequence will have that many elements in it.
     * If length is given and is negative, then the sequence will stop that many elements from the end of the array.
     * If it is omitted, then the sequence will have everything from offset up until the end of the array.
     * </p>
     * @param bool $preserve_keys [optional] <p>
     * Note that array_slice will reorder and reset the array indices by default.
     * You can change this behavior by setting preserve_keys to true.
     * </p>
     *
     * @return ($preserve_keys is true ? array<TKey, TValue> : array<TKey|int, TValue>) Sliced array.
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
     * Whether you want to preserve keys from an original array or not.
     * </p>
     * @param \FireHub\Core\Support\Enums\Sort $flag [optional] <p>
     * Sorting a flag.
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
     * Sorting a flag.
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
     * ### Sorts an array by values using a user-defined comparison function
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
     * Whether you want to preserve keys from an original array or not.
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
     *
     * Removes the elements designated by offset and length from the array $array, and replaces them with the elements
     * of the replacement array, if supplied.
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * Array to splice.
     * </p>
     * @param int $offset <p>
     * If the offset is positive, then the start of the removed portion is at that offset from the beginning of the
     * input array.
     * If the offset is negative, then it starts that far from the end of the input array.
     * </p>
     * @param null|int $length [optional] <p>
     * If length is omitted, removes everything from offset to the end of the array.
     * If length is specified and is positive, then that many elements will be removed.
     * If length is specified and is negative, then the end of the removed portion will be that many elements from
     * the end of the array.
     * </p>
     * @param mixed $replacement [optional] <p>
     * If a replacement array is specified, then the removed elements will be replaced with elements from this array.
     * If offset and length are such that nothing is removed, then the elements from the replacement array or array
     * are inserted in the place specified by the offset.
     * Keys in a replacement array are not preserved.
     * </p>
     *
     * @return array<TKey|int, TValue> Spliced array.
     *
     * @note Numerical keys in an array are not preserved.
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
     *
     * Takes an input array and returns a new array without duplicate values.
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
     * @note The new array will preserve associative keys, and reindex others.
     * @note This method is not intended to work on multidimensional arrays.
     */
    public static function unique (array $array):array {

        return array_unique($array, SORT_REGULAR);

    }

    /**
     * ### Return all the values from an array
     *
     * Returns all the values from the array and indexes the array numerically.
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
     *
     * Applies the user-defined callback function to each element of the array $array.
     * Method is not affected by the internal array pointer of an array.
     * Method will walk through the entire array regardless of pointer position.
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array to apply a user function.
     * </p>
     * @param callable(TValue $value, TKey $key):mixed $callback <p>
     * Typically, function name takes on two parameters.
     * The array parameter's value is the first, and the key/index second.
     * If a function name needs to be working with the actual values of the array,
     * specify the first parameter of function name as a reference.
     * Then, any changes made to those elements will be made in the original array itself.
     * Users may not change the array itself from the callback function, e.g., add/delete elements, unset elements, etc.
     * </p>
     *
     * @throws ArgumentCountError If the $callback function requires more than 2 parameters.
     *
     * @return bool True on success, false otherwise.
     */
    public static function walk (array &$array, callable $callback):bool {

        return array_walk($array, $callback);

    }

    /**
     * ### Apply a user function recursively to every member of an array
     *
     * Applies the user-defined callback function to each element of the array.
     * This function will recurse into deeper arrays.
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array to apply a user function.
     * </p>
     * @param callable(TValue $value, TKey $key):mixed $callback <p>
     * Typically, function name takes on two parameters.
     * The array parameter's value is the first, and the key/index second.
     * If a function name needs to be working with the actual values of the array,
     * specify the first parameter of function name as a reference.
     * Then, any changes made to those elements will be made in the original array itself.
     * Users may not change the array itself from the callback function.
     * E.g., Add/delete elements, unset elements, etc.
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
     * note: If a needle is a string, the comparison is done in a case-sensitive manner.
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
     * Step should be given as a positive number.
     * If not specified, a step will default to 1.
     * </p>
     *
     * @throws ValueError If $step exceeds the specified range.
     *
     * @return array<int, TValue>|false An array of elements from start to end, inclusive, false otherwise.
     *
     * @note Character sequence values are limited to a length of one. If a length greater than one is entered.
     * only the first character is used.
     */
    public static function range (int|float|string $start, int|float|string $end, int|float $step = 1):array|false {

        return range($start, $end, $step);

    }

    /**
     * ### Shuffle an array
     *
     * This function shuffles (randomizes the order of the elements in) an array.
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $array <p>
     * The array.
     * </p>
     *
     * @return true Always returns true.
     *
     * @note This function assigns new keys to the elements in array.
     * It will remove any existing keys that may have been assigned, rather than just reordering the keys.
     * @note Resets array's internal pointer to the first element.
     */
    public static function shuffle (array &$array):true {

        return shuffle($array);

    }

    /**
     * ### Fetch a key from an array
     *
     * Method key() returns the index element of the current array position.
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array.
     * </p>
     *
     * @return null|TKey The key() function simply returns the key of the array element
     * that's currently being pointed to by the internal pointer.
     * It does not move the pointer in any way.
     * If the internal pointer points beyond the end of the elements list or the array is empty, key() returns null.
     */
    public static function key (array $array):int|string|null {

        return key($array);

    }

    /**
     * ### Return the current element in an array
     *
     * Every array has an internal pointer to its "current" element, which is initialized to the first element
     * inserted into the array.
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array <p>
     * The array.
     * </p>
     *
     * @return TValue|false The current() function simply returns the value of the array element that is being pointed
     * to with the internal pointer.
     * It does not move the pointer in any way.
     * If the internal pointer points beyond the end of the elements list or the array is empty, current() returns
     * false.
     *
     * @warning This function may return Boolean false, but may also return
     * a non-Boolean value which evaluates to false.
     * Please read the section on Booleans for more information.
     * Use the === operator for testing the return value of this function.
     *
     * @note The results of calling current() on an empty array and on an array, whose internal pointer points beyond
     * the end of the elements is indistinguishable from a bool false element.
     * To properly traverse an array which may contain false elements, see the foreach control structure.
     * To still use current() and properly check if the value is really an element of the array,
     * the key() of the current() element should be checked to be strictly different from null.
     */
    public static function current (array $array):mixed {

        return current($array);

    }

    /**
     * ### Rewind the internal array pointer
     *
     * Method prev() behaves just like next(), except it rewinds the internal array pointer one place instead of
     * advancing it.
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> &$array <p>
     * The input array.
     * </p>
     *
     * @return TValue|false Returns the array value in the previous place that's
     * pointed to by the internal array pointer,
     * or false if there are no more elements.
     *
     * @warning This function may return Boolean false, but may also return
     * a non-Boolean value which evaluates to false.
     * Please read the section on Booleans for more information.
     * Use the === operator for testing the return value of this function.
     *
     * @note  The beginning of an array is indistinguishable from a bool false element.
     * To make the distinction, check that the key() of the prev() element is not null.
     */
    public static function prev (array &$array):mixed {

        return prev($array);

    }

    /**
     * ### Advance the internal pointer of an array
     *
     * Method next() behaves like current(), with one difference.
     * It advances the internal array pointer one place forward before returning the element value.
     * That means it returns the next array value and advances the internal array pointer by one.
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> &$array <p>
     * The array being affected.
     * </p>
     *
     * @return TValue|false Returns the array value in the next place that's pointed to by the internal array
     * pointer, or false if there are no more elements.
     *
     * @warning This function may return Boolean false, but may also return
     * a non-Boolean value which evaluates to false.
     * Please read the section on Booleans for more information.
     * Use the === operator for testing the return value of this function.
     *
     * @note The end of an array is indistinguishable from a bool false element.
     * To properly traverse an array which may contain false elements, see the foreach function.
     * To still use next() and properly check if the end of the array has been reached, verify that the key() is null.
     */
    public static function next (array &$array):mixed {

        return next($array);

    }

    /**
     * ### Set the internal pointer of an array to its first element
     *
     * Method reset() rewinds array's internal pointer to the first element
     * and returns the value of the first array element.
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> &$array <p>
     * The input array.
     * </p>
     *
     * @return TValue|false Returns the value of the first array element, or false if the array is empty.
     *
     * @warning This function may return Boolean false, but may also return
     * a non-Boolean value which evaluates to false.
     * Please read the section on Booleans for more information.
     * Use the === operator for testing the return value of this function.
     *
     * @note  The return value for an empty array is indistinguishable from the return value in case
     * of an array which has a bool false first element.
     * To properly check the value of the first element of an array which may contain false elements, first check the
     * count() of the array, or check that key() is not null, after calling reset().
     */
    public static function reset (array &$array):mixed {

        return reset($array);

    }

    /**
     * ### Set the internal pointer of an array to its last element
     *
     * Method end() advances array's internal pointer to the last element, and returns its value.
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param array<TKey, TValue> &$array <p>
     * The array.
     * Reference passes this array because it is modified by the function.
     * This means you must pass it a real variable and not a function returning an array because only actual
     * variables may be passed by reference.
     * </p>
     *
     * @return TValue|false Returns the value of the last element or false for an empty array.
     */
    public static function end (array &$array):mixed {

        return end($array);

    }

}