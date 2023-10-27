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

use FireHub\Core\Support\Enums\ {
    Data\Category, Data\Type, Operator\Comparison, Order
};
use FireHub\Core\Support\LowLevel\ {
    Arr, Data, DataIs
};
use ValueError;

use function FireHub\Core\Support\Helpers\Data\is_type;

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
function is_empty (array $array):bool {

    return Arr::count($array) === 0;

}

/**
 * ### Checks if an array is multidimensional
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\LowLevel\Arr::count() To count all array elements.
 * @uses \FireHub\Core\Support\LowLevel\Arr::filter() To filter elements in an array.
 * @uses \FireHub\Core\Support\LowLevel\DataIs::array() To check if the value is an array.
 *
 * @param array<array-key, mixed> $array <p>
 * Array to check.
 * </p>
 *
 * @return bool True if an array is multidimensional, false otherwise.
 *
 * @note That any array that has at least one item as an array will be considered as a multidimensional array.
 */
function is_multi_dimensional (array $array):bool {

    return Arr::count(Arr::filter($array, [DataIs::class, 'array'])) > 0;

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
 * ### Filter elements in an array recursively
 * @since 1.0.0
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @uses \FireHub\Core\Support\Enums\Operator\Comparison As parameter.
 * @uses \FireHub\Core\Support\Enums\Operator\Comparison::compare() To compare current enum with provided values.
 * @uses \FireHub\Core\Support\LowLevel\DataIs::array To check if the value is an array.
 * @uses \FireHub\Core\Support\Helpers\Array\is_empty() To check if an array is empty.
 *
 * @param int|string $key <p>
 * Key to filter on.
 * </p>
 * @param mixed $value <p>
 * Value to filter.
 * </p>
 * @param \FireHub\Core\Support\Enums\Operator\Comparison $operator <p>
 * Operator for filter.
 * </p>
 * @param array<TKey, TValue> $array <p>
 * The array to iterate over.
 * </p>
 * @param bool $keep_filtered [optional] <p>
 * If true, keep filtered items, remove otherwise.
 * </p>
 *
 * @return array<TKey, TValue> The filtered array.
 */
function filter_recursive (int|string $key, Comparison $operator, mixed $value, array $array, bool $keep_filtered = true):array {

    foreach ($array as $array_key => $array_value) {

        if (DataIs::array($array_value)) {

            $array_value = filter_recursive(
                $key,$operator, $value, $array_value, $keep_filtered
            );

            if (is_empty($array_value) || $keep_filtered
                ? !isset($array_value[$key])
                  || !($operator->compare($array_value[$key], $value))
                : isset($array_value[$key])
                  && ($operator->compare($array_value[$key], $value))
            ) unset($array[$array_key]);

        } else if ($array_key !== $key) unset($array[$array_key]);

    }

    return $array;

}

/**
 * ### Filter elements in an array recursively with value type
 * @since 1.0.0
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @uses \FireHub\Core\Support\Enums\Data\Category As parameter.
 * @uses \FireHub\Core\Support\Enums\Data\Type As parameter.
 * @uses \FireHub\Core\Support\LowLevel\DataIs::array To check if the value is an array.
 * @uses \FireHub\Core\Support\Helpers\Array\is_empty() To check if an array is empty.
 * @uses \FireHub\Core\Support\Helpers\Data\is_type() To check if the value is of a type.
 *
 * @param array-key $key <p>
 * Key to filter on.
 * </p>
 * @param \FireHub\Core\Support\Enums\Data\Category|\FireHub\Core\Support\Enums\Data\Type $type <p>
 * Type of value to filter.
 * </p>
 * @param array<TKey, TValue> $array <p>
 * The array to iterate over.
 * </p>
 * @param bool $keep_filtered [optional] <p>
 * If true, keep filtered items, remove otherwise.
 * </p>
 *
 * @return array<TKey, TValue> Filtered array.
 */
function filter_recursive_type (int|string $key, Category|Type $type, array $array, bool $keep_filtered = true):array {

    foreach ($array as $array_key => $array_value) {

        if (DataIs::array($array_value)) {

            $array_value = filter_recursive_type(
                $key, $type, $array_value
            );

            if (is_empty($array_value) || $keep_filtered
                ? !isset($array_value[$key])
                  || !(is_type($array_value[$key], $type))
                : isset($array_value[$key])
                  && (is_type($array_value[$key], $type))
            ) unset($array[$array_key]);

        } else if ($array_key !== $key) unset($array[$array_key]);

    }

    return $array;

}

/**
 * ### Sort multiple or multidimensional arrays
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\LowLevel\Arr::column() To return the values from a single column in the input array.
 * @uses \FireHub\Core\Support\LowLevel\Arr::multiSort() To sort multiple or multidimensional arrays.
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
 * @throws ValueError If array sizes are inconsistent.
 *
 * @return bool True on success, false otherwise.
 *
 * @note Resets array's internal pointer to the first element.
 */
function sortByMany (array &$array, array $fields):bool {

    $multi_sort = [];

    foreach ($fields as $column => $order) {

        $order = match($order) {
            Order::DESC => SORT_DESC,
            default => SORT_ASC
        };

        $multi_sort[] = [...Arr::column($array, $column)];

        $multi_sort[] = $order;

    }

    $multi_sort[] = &$array;

    return Arr::multiSort($multi_sort);

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