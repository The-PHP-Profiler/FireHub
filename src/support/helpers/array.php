<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package
 *
 * This file contains all array functions.
 * @since 1.0.0
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2023 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @package FireHub\Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\Helpers\Array;

use FireHub\Core\Support\LowLevel\ {
    Arr, DataIs
};
use ValueError;

/**
 * ### Checks if an array is empty
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\LowLevel\Arr::count() To count array elements.
 *
 * @param array<array-key, mixed> $array <p>
 * Array to check.
 * </p>
 *
 * @return bool True if an array is empty, false otherwise
 */
function isEmpty (array $array):bool {

    return Arr::count($array) === 0;

}

/**
 * ### Get first value from array
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\LowLevel\Arr::firstKey() To get the first key from an array.
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @param array<TKey, TValue> $array <p>
 * The array.
 * </p>
 *
 * @return null|TValue First value from an array or null if an array is empty.
 */
function first (array $array):mixed {

    $key = Arr::firstKey($array);

    return isset($key) ? $array[$key] : null;

}

/**
 * ### Get last value from array
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\LowLevel\Arr::lastKey() To get the last key from an array.
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @param array<TKey, TValue> $array <p>
 * The array.
 * </p>
 *
 * @return null|TValue Last value from array or null if an array is empty.
 */
function last (array $array):mixed {

    $key = Arr::lastKey($array);

    return isset($key) ? $array[$key] : null;


}

/**
 * ### Searches recursively the array for a given value and returns the first corresponding key if successful
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\LowLevel\DataIs::array() To check if the value is an array.
 *
 * @param mixed $needle <p>
 * The searched value.
 * </p>
 * @param array<array-key, mixed> $haystack <p>
 * Array to search.
 * </p>
 *
 * @return array<array-key, mixed>|false The full key array path for needle if it is found in the array, false
 * otherwise.
 *
 * @api
 */
function search_recursive (mixed $needle, array $haystack):array|false {

    foreach ($haystack as $key => $value) {

        if ($needle === $value) return [$key => $needle];

        else if (DataIs::array($value)) {

            $callback = search_recursive($needle, $value);

            if ($callback) return [$key => $callback];

        }

    }

    return false;

}

/**
 * ### Removes unique values from an array
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\LowLevel\Arr::differenceAssoc() To compute the difference of arrays with additional index
 * check.
 * @uses \FireHub\Core\Support\LowLevel\Arr::unique() To remove duplicate values from an array.
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @param array<TKey, TValue> $array <p>
 * The array to remove unique values.
 * </p>
 *
 * @return array<TKey, TValue> The array duplicates.
 */
function duplicates (array $array):array {

    return Arr::differenceAssoc($array, Arr::unique($array));

}

/**
 * ### Get all values from array except for those with the specified keys
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\LowLevel\Arr::intersectKey() To compute the difference of arrays using keys for
 * comparison.
 * @uses \FireHub\Core\Support\LowLevel\Arr::flip To exchange all keys with their associated values in an array.
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @param array<TKey, TValue> $array <p>
 * The array to filter items.
 * </p>
 * @param list<array-key> $keys <p>
 * List of keys to return.
 * </p>
 *
 * @error\exeption E_WARNING if values on $array argument are neither int nor string.
 *
 * @return array<TKey, TValue> The filtered array.
 */
function except (array $array, array $keys):array {

    return Arr::differenceKey($array, Arr::flip($keys));

}

/**
 * ### Get all values from an array with the specified keys
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\LowLevel\Arr::intersectKey() To compute the intersection of arrays using keys for
 * comparison.
 * @uses \FireHub\Core\Support\LowLevel\Arr::flip To exchange all keys with their associated values in an array.
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @param array<TKey, TValue> $array <p>
 * The array to filter items.
 * </p>
 * @param list<array-key> $keys <p>
 * List of keys to return.
 * </p
 *
 * @error\exeption E_WARNING if values on $array argument are neither int nor string.
 *
 * @return array<TKey, TValue> The filtered array.
 */
function only (array $array, array $keys):array {

    /** @phpstan-ignore-next-line */
    return Arr::intersectKey($array, Arr::flip($keys));

}

/**
 * ### Pick one or more random values out of the array
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\LowLevel\DataIs::array() To check if the value is an array.
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @param array<TKey, TValue> $array <p>
 * Array from we are picking random items.
 * </p>
 * @param positive-int $number [optional] <p>
 * Specifies how many entries you want to pick.
 * </p>
 * @param bool $preserve_keys [optional] <p>
 * Whether you want to preserve keys from an original array or not.
 * </p>
 *
 * @throws ValueError If $number is not between 1 and the number of elements in argument.
 *
 * @return ($preserve_keys is true ? mixed|array<TKey, TValue> : mixed|list<TValue>)
 * If you are picking only one entry, return the value for a random entry.
 * Otherwise, it returns an array of values for the random entries.
 */
function random (array $array, int $number = 1, bool $preserve_keys = false):mixed {

    $keys = Arr::random($array, $number);

    if (!DataIs::array($keys)) return $array[$keys];

    $items = [];
    if ($preserve_keys) foreach ($keys as $key) $items[$key] = $array[$key];
    else foreach ($keys as $key) $items[] = $array[$key];

    return $items;

}

/**
 * ### Shuffle array items with keys preserved
 * @since 1.0.0
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @uses \FireHub\Core\Support\Helpers\Array\isEmpty() To check if an array is empty.
 * @uses \FireHub\Core\Support\LowLevel\Arr::keys() To get array keys.
 * @uses \FireHub\Core\Support\LowLevel\Arr::shuffle() To shuffle array items.
 *
 * @param array<TKey, TValue> $array <p>
 * An array to shuffle.
 * </p>
 *
 * @return array<TKey, TValue> Shuffled array.
 */
function shuffle (array $array):array {

    $items = [];

    $keys = Arr::keys($array);

    Arr::shuffle($keys);

    foreach($keys as $key) $items[$key] = $array[$key];

    /** @phpstan-ignore-next-line */
    return $items;

}