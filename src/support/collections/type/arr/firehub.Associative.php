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

namespace FireHub\Core\Support\Collections\Type\Arr;

use FireHub\Core\Support\Collections\Collectable;
use FireHub\Core\Support\Collections\Traits\Chunkable;
use FireHub\Core\Support\Enums\ {
    Order, Sort
};
use FireHub\Core\Support\LowLevel\ {
    Arr, DataIs
};
use Closure, Error, TypeError, ValueError;

use function FireHub\Core\Support\Helpers\Array\ {
    except, first, only, random
};

/**
 * ### Associative array collection type
 *
 * Collections that use named keys that you assign to them.
 * @since 1.0.0
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @extends \FireHub\Core\Support\Collections\Type\Arr\aArr<TKey, TValue>
 */
final class Associative extends aArr {

    /**
     * ### Allows chunking the collection
     * @since 1.0.0
     *
     * @use \FireHub\Core\Support\Collections\Traits\Chunkable<TKey, TValue>
     */
    use Chunkable;

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::invoke() To invoke storage.
     *
     * @param Closure():array<TKey, TValue> $callable <p>
     * Data from a callable source.
     * </p>
     */
    public function __construct (
        protected Closure $callable
    ) {

        $this->storage = $this->invoke($this->callable);

    }

    /**
     * @inheritDoc
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'
     * ]);
     *
     * $collection->each(function ($value, $key) {
     *  if (* condition *) {
     *      return false;
     *  }
     * });
     * ```
     *
     * @param callable(TValue $value, TKey $key):(false|void) $callback <p>
     * Function to call on each item in collection.
     * </p>
     */
    public function each (callable $callback):bool {

        foreach ($this->storage as $key => $value) if ($callback($value, $key) === false) return false;

        return true;

    }

    /**
     * ### Combines the values of the collection, as keys, with the values of another collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collectable As parameter.
     * @uses \FireHub\Core\Support\Collections\Collectable::all() To get a collection as an array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::combine() To create an array by using one array for keys and another
     * for its values.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative As return.
     *
     * @template TNewValue
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $keys = Collection::create()->associative(fn():array => [1 => 'firstname', 2 => 'lastname', 3 => 'age']);
     * $values = Collection::create()->associative(fn():array => [1 => 'John', 2 => 'Doe', 3 => 25]);
     *
     * $combined = $keys->combine($values);
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25]
     * ```
     *
     * @param \FireHub\Core\Support\Collections\Collectable<TNewValue> $collection <p>
     * Collection to be used as values.
     * </p>
     *
     * @throws ValueError If arguments $keys and $values don't have the same number of elements.
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Associative<TValue, TNewValue> The combined collection.
     *
     * @phpstan-ignore-next-line
     */
    public function combine (Collectable $collection):Associative {

        /** @phpstan-ignore-next-line */
        return new Associative(fn():array => Arr::combine($this->storage, $collection->all()) ?: []);

    }

    /**
     * ### Computes the difference of collections
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::callable() To verity that the contents of a variable can be called
     * as a function.
     * @uses \FireHub\Core\Support\LowLevel\Arr::differenceAssoc() To compute the difference of arrays with
     * additional index check.
     * @uses \FireHub\Core\Support\LowLevel\Arr::differenceAssocFuncKey() To compute the difference of arrays with
     * additional index check by using a callback function for key comparison.
     * @uses \FireHub\Core\Support\LowLevel\Arr::differenceAssocFuncValue() To compute the difference of arrays with
     * additional index check by using a callback function for value comparison.
     * @uses \FireHub\Core\Support\LowLevel\Arr::differenceAssocFuncKeyValue() To compute the difference of arrays with
     * additional index check by using a callback function for key and value comparison.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::all() To get a collection as an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'
     * ]);
     *
     * $collection2 = Collection::create()->associative(fn():array => [
     *  'firstname' => 'Jane', 'lastname' => 'Doe', 'height' => '160cm'
     * ]);
     *
     * $diff = $collection1->difference($collection2);
     *
     * // ['firstname' => 'John', 'age' => 25, 'height' => '190cm']
     * ```
     * @example With callback function for vales.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'
     * ]);
     *
     * $collection2 = Collection::create()->associative(fn():array => [
     *  'firstname' => 'Jane', 'lastname' => 'Doe', 'height' => '160cm'
     * ]);
     *
     * $diff = $collection->difference($collection2, function ($value_a, $value_b):int {
     *  if ($value_a === $value_b && $value_a <> 'Doe') return 0;
     *  return ($value_a > $value_b) ? 1 : -1;
     * });
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm']
     * ```
     * @example With callback function for keys.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'
     * ]);
     *
     * $collection2 = Collection::create()->associative(fn():array => [
     *  'firstname' => 'Jane', 'lastname' => 'Doe', 'height' => '160cm'
     * ]);
     *
     * $diff = $collection->difference($collection2, callback_key: function ($key_a, $key_b):int {
     *  if ($key_a === $key_b && $key_a <> 'lastname') return 0;
     *  return ($key_a > $key_b) ? 1 : -1;
     * });
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm']
     * ```
     * @example With callback function for keys and values.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'
     * ]);
     *
     * $collection2 = Collection::create()->associative(fn():array => [
     *  'firstname' => 'Jane', 'lastname' => 'Doe', 'height' => '160cm'
     * ]);
     *
     * $diff = $collection->difference($collection2, function ($value_a, $value_b):int {
     *  if ($value_a === $value_b && $value_a <> 'Doe') return 0;
     *  return ($value_a > $value_b) ? 1 : -1;
     * }, function ($key_a, $key_b):int {
     *  if ($key_a === $key_b && $key_a <> 'lastname') return 0;
     *  return ($key_a > $key_b) ? 1 : -1;
     * });
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm']
     * ```
     *
     * @param \FireHub\Core\Support\Collections\Type\Arr\Indexed<mixed>|\FireHub\Core\Support\Collections\Type\Arr\Associative<array-key, mixed> $collection <p>
     * Collection to compare against.
     * </p>
     * @param null|callable(TValue $value_a, TValue $value_b):int<-1, 1> $callback_value [optional] <p>
     * The value comparison function.
     * </p>
     * @param null|callable(TKey $value_a, TKey $value_b):int<-1, 1> $callback_key [optional] <p>
     * The key comparison function.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Associative<TKey, TValue> Returns collection containing
     * all items that are not present other collection.
     */
    public function difference (Indexed|Associative $collection, callable $callback_value = null, callable $callback_key = null):self {

        return new self(fn() => match(true) {
            DataIs::callable($callback_value) && DataIs::callable($callback_key) =>
                Arr::differenceAssocFuncKeyValue(
                    $this->storage,
                    $collection->all(),
                    $callback_value,
                    $callback_key // @phpstan-ignore-line
                ),
            DataIs::callable($callback_value) =>
                Arr::differenceAssocFuncValue(
                    $this->storage,
                    $collection->all(),
                    $callback_value
                ),
            DataIs::callable($callback_key) =>
                Arr::differenceAssocFuncKey(
                    $this->storage,
                    $collection->all(),
                    $callback_key // @phpstan-ignore-line
                ),
            default =>
                Arr::differenceAssoc(
                    $this->storage,
                    $collection->all()
                )
        });

    }

    /**
     * ### Computes the difference in keys of collections
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::differenceKey() To compute the difference of arrays using keys for
     * comparison.
     * @uses \FireHub\Core\Support\LowLevel\Arr::differenceKeyFunc() To compute the difference of arrays using keys for
     * comparison by using a callback function for data comparison.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::all() To get a collection as an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'
     * ]);
     *
     * $collection2 = Collection::create()->associative(fn():array => [
     *  'firstname' => 'Jane', 'lastname' => 'Doe', 'height' => '160cm'
     * ]);
     *
     * $diff = $collection1->differenceKeys($collection2);
     *
     * // ['age' => 25]
     * ```
     * @example With callback function.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'
     * ]);
     *
     * $collection2 = Collection::create()->associative(fn():array => [
     *  'firstname' => 'Jane', 'lastname' => 'Doe', 'height' => '160cm'
     * ]);
     *
     * $diff = $collection1->difference($collection2, function ($key_a, $key_b):int {
     *  if ($key_a === $key_b && $key_a <> 'lastname') return 0;
     *  return ($key_a > $key_b) ? 1 : -1;
     * });
     *
     * // ['lastname' => 'Doe', 'age' => 25]
     * ```
     *
     * @param \FireHub\Core\Support\Collections\Type\Arr\Indexed<mixed>|\FireHub\Core\Support\Collections\Type\Arr\Associative<array-key, mixed> $collection <p>
     * Collection to compare against.
     * </p>
     * @param null|callable(TKey $key_a, TKey $key_b):int<-1, 1> $callback [optional] <p>
     * The comparison function.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Associative<TKey, TValue> Returns collection containing all
     * items that are not present other collection.
     */
    public function differenceKeys (Indexed|Associative $collection, callable $callback = null):self {

        return new self(fn():array => $callback
            ? Arr::differenceKeyFunc($this->storage, $collection->all(), $callback)
            : Arr::differenceKey($this->storage, $collection->all()));

    }

    /**
     * ### New collection with items except for those with the specified keys
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Helpers\Array\except() To get all values from array except for those with
     * the specified keys.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'
     * ]);
     *
     * $filtered = $collection->except('firstname', 'lastname');
     *
     * // ['age' => 25, 'height' => '190cm', 'gender' => 'male']
     * ```
     *
     * @param list<array-key> $keys <p>
     * List of keys to filter out.
     * </p>
     *
     * @error\exeption E_WARNING if values on $array argument are neither int nor string.
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Associative<TKey, TValue> New filtered collection.
     */
    public function except (array $keys):self {

        return new self(fn():array => except($this->storage, $keys));

    }

    /**
     * ### Exchanges all keys with their associated values in collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::flip() To exchange all keys with their associated values in an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'
     * ]);
     *
     * $flipped = $collection->flip();
     *
     * // ['John' => 'firstname', 'Doe' => 'lastname', 25 => 'age', '190cm' => 'height', 'male' => 'gender']
     * ```
     *
     * @error\exeption E_WARNING if values on $array argument are neither int nor string.
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Associative<TValue, TKey> New flipped collection.
     *
     * @phpstan-ignore-next-line
     */
    public function flip ():self {

        /** @phpstan-ignore-next-line */
        return new self(fn():array => Arr::flip($this->storage));

    }

    /**
     * ### Removes any values from the collection that are not present in the given collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::callable() To verity that the contents of a variable can be called
     * as a function.
     * @uses \FireHub\Core\Support\LowLevel\Arr::intersectAssoc() To compute the intersection of arrays with additional
     * index check.
     * @uses \FireHub\Core\Support\LowLevel\Arr::intersectAssocFuncKey() To compute the intersection of arrays with
     * additional index check by using a callback function for key comparison.
     * @uses \FireHub\Core\Support\LowLevel\Arr::intersectAssocFuncValue() To compute the intersection of arrays with
     * additional index check by using a callback function for value comparison.
     * @uses \FireHub\Core\Support\LowLevel\Arr::intersectAssocFuncKeyValue() To compute the intersection of arrays with
     * additional index check by using a callback function for key and value comparison.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative::all() To get a collection as an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'
     * ]);
     *
     * $collection2 = Collection::create()->associative(fn():array => [
     *  'firstname' => 'Jane', 'lastname' => 'Doe', 'height' => '160cm'
     * ]);
     *
     * $intersect = $collection1->intersect($collection2);
     *
     * // ['lastname' => 'Doe']
     * ```
     * @example With callback function for vales.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'
     * ]);
     *
     * $collection2 = Collection::create()->associative(fn():array => [
     *  'firstname' => 'Jane', 'lastname' => 'Doe', 'height' => '160cm'
     * ]);
     *
     * $intersect = $collection->intersect($collection2, function ($value_a, $value_b):int {
     *  if ($value_a === $value_b && $value_a <> 'Doe') return 0;
     *  return ($value_a > $value_b) ? 1 : -1;
     * });
     *
     * // []
     * ```
     * @example With callback function for keys.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'
     * ]);
     *
     * $collection2 = Collection::create()->associative(fn():array => [
     *  'firstname' => 'Jane', 'lastname' => 'Doe', 'height' => '160cm'
     * ]);
     *
     * $intersect = $collection->intersect($collection2, callback_key: function ($key_a, $key_b):int {
     *  if ($key_a === $key_b && $key_a <> 'lastname') return 0;
     *  return ($key_a > $key_b) ? 1 : -1;
     * });
     *
     * // []
     * ```
     * @example With callback function for keys and values.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'
     * ]);
     *
     * $collection2 = Collection::create()->associative(fn():array => [
     *  'firstname' => 'Jane', 'lastname' => 'Doe', 'height' => '160cm'
     * ]);
     *
     * $intersect = $collection->intersect($collection2, function ($value_a, $value_b):int {
     *  if ($value_a === $value_b && $value_a <> 'Doe') return 0;
     *  return ($value_a > $value_b) ? 1 : -1;
     * }, function ($key_a, $key_b):int {
     *  if ($key_a === $key_b && $key_a <> 'lastname') return 0;
     *  return ($key_a > $key_b) ? 1 : -1;
     * });
     *
     * // []
     * ```
     *
     * @param \FireHub\Core\Support\Collections\Type\Arr\Indexed<TValue>|\FireHub\Core\Support\Collections\Type\Arr\Associative<TKey, TValue> $collection <p>
     * Collection to compare against.
     * </p>
     * @param null|callable(TValue $a, TValue $b):int<-1, 1> $callback_value [optional] <p>
     * The value comparison function.
     * </p>
     * @param null|callable(TKey $a, TKey $b):int<-1, 1> $callback_key [optional] <p>
     * The key comparison function.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Associative<TKey, TValue> Returns collection containing all
     * items that are not present in the given collection.
     */
    public function intersect (Indexed|Associative $collection, callable $callback_value = null, callable $callback_key = null):self {

        return new self(fn() => match(true) {
            DataIs::callable($callback_value) && DataIs::callable($callback_key) =>
                Arr::intersectAssocFuncKeyValue(
                    $callback_value,
                    $callback_key, // @phpstan-ignore-line
                    $this->storage,
                    $collection->all()
                ),
            DataIs::callable($callback_value) =>
                Arr::intersectAssocFuncValue(
                    $callback_value,
                    $this->storage,
                    $collection->all()
                ),
            DataIs::callable($callback_key) =>
                Arr::intersectAssocFuncKey(
                    $callback_key, // @phpstan-ignore-line
                    $this->storage,
                    $collection->all()
                ),
            default =>
                Arr::intersectAssoc(
                    $this->storage,
                    $collection->all()
                )
        });

    }

    /**
     * ### Computes the intersecting in keys of collections
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::intersectKey() To compute the intersection of arrays using keys for
     * comparison.
     * @uses \FireHub\Core\Support\LowLevel\Arr::intersectKeyFunc() To compute the intersection of arrays using keys for
     * comparison by using a callback function for data comparison.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::all() To get a collection as an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'
     * ]);
     *
     * $collection2 = Collection::create()->associative(fn():array => [
     *  'firstname' => 'Jane', 'lastname' => 'Doe', 'height' => '160cm'
     * ]);
     *
     * $intersect = $collection1->intersectKeys($collection2);
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'height' => '190cm']
     * ```
     * @example With callback function.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'
     * ]);
     *
     * $collection2 = Collection::create()->associative(fn():array => [
     *  'firstname' => 'Jane', 'lastname' => 'Doe', 'height' => '160cm'
     * ]);
     *
     * $intersect = $collection1->intersectKeys($collection2, function ($key_a, $key_b):int {
     *  if ($key_a === $key_b && $key_a <> 'lastname') return 0;
     *  return ($key_a > $key_b) ? 1 : -1;
     * });
     *
     * // ['lastname' => 'Doe', 'age' => 25, 'height' => '190cm']
     * ```
     * @param \FireHub\Core\Support\Collections\Type\Arr\Indexed<TValue>|\FireHub\Core\Support\Collections\Type\Arr\Associative<TKey, TValue> $collection <p>
     * Collection to compare against.
     * </p>
     * @param null|callable(TKey $a, TKey $b):int<-1, 1> $callback [optional] <p>
     * The comparison function.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Associative<TKey, TValue> Returns collection containing all
     * items that are not present other collection.
     */
    public function intersectKeys (Indexed|Associative $collection, callable $callback = null):self {

        return new self(fn():array => $callback
            ? Arr::intersectKeyFunc(
                $callback, // @phpstan-ignore-line
                $this->storage,
                $collection->all()
            )
            : Arr::intersectKey(
                $this->storage,
                $collection->all())
        );

    }

    /**
     * ### The join method joins collection items with a string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative::count() To count elements in the iterator.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative::last() To get last item from collection.
     * @uses \FireHub\Core\Support\Helpers\Array\first() To get the first value from an array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::slice() To extract a slice of the array.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative::convert() To convert a collection to the different
     * one.
     * @uses \FireHub\Core\Support\Collections\Helpers\Convert::toArray() To convert a collection to an array type.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed::join() To join collection items with a string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'
     * ]);
     *
     * $join = $collection->join(', ');
     *
     * // John, Doe, 25, 190cm, male
     * ```
     * @example With conjunction.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'
     * ]);
     *
     * $join = $collection->join(', ', ' and ');
     *
     * // John, Doe, 25, 190cm and male
     * ```
     * @example With symbol.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'
     * ]);
     *
     * $join = $collection->join(', ', ' and ', ' = ');
     *
     * // firstname = John, lastname = Doe, age = 25, height = 190cm and gender = male
     * ```
     *
     * @param string $separator <p>
     * String to separate collection items.
     * </p>
     * @param string|false $conjunction [optional] <p>
     * Last item separator.
     * </p>
     * @param string|false $symbol [optional] <p>
     * Symbol between a key and value.
     * </p>
     *
     * @throws Error If collection item could not be converted to string.
     *
     * @return string Items with string separator.
     */
    public function join (string $separator, string|false $conjunction = false, string|false $symbol = false):string {

        if ($symbol) {

            $join = '';

            $last = $this->last();

            $last_before = first(Arr::slice($this->storage, -2, 1));

            foreach ($this->storage as $key => $value) {

                $item = $key.$symbol.$value;

                if ($value === $last && $conjunction && $this->count() > 1)
                    $join .= $conjunction.$item;

                else if ($value === $last) $join .= $item;

                else if ($value === $last_before && $conjunction) $join .= $item;

                else $join .= $item.$separator;

            }

            return $join;

        }

        return $this->convert()->toArray()->join($separator, $conjunction);

    }

    /**
     * ### Get collection's keys
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::keys() To return all the keys or a subset of the keys of an array.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25
     * ]);
     *
     * $keys = $collection->keys();
     *
     * // ['firstname', 'lastname', 'age']
     * ```
     * @example With filter option.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25
     * ]);
     *
     * $keys = $collection->keys('John', 'Doe');
     *
     * // ['firstname', 'lastname']
     * ```
     *
     * @param TValue $filter [optional] <p>
     * If specified, then only keys containing these values are returned.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Indexed<TKey> Collection's keys.
     */
    public function keys (mixed $filter = null):Indexed {

        return new Indexed(fn():array => Arr::keys($this->storage, $filter));

    }

    /**
     * ### Applies the callback to each collection key
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25
     * ]);
     *
     * $map = $collection->mapKeys(function ($value, $key) {
     *  return 'new '.$key;
     * });
     *
     * // ['new firstname', 'new lastname', 'new age']
     * ```
     *
     * @param callable(TValue $value, TKey $key):array-key $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<array-key, TValue> New modified collection.
     */
    public function mapKeys (callable $callback):self {

        $storage = [];

        foreach ($this->storage as $key => $value) $storage[$callback($value, $key)] = $value;

        return new self(fn():array => $storage);

    }

    /**
     * ### Merge recursively new collection into current one
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::mergeRecursive() To merge two or more arrays recursively.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative::all() To get a collection as an array.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Multidimensional As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25
     * ]);
     *
     * $collection2 = Collection::create()->associative(fn():array => [
     *  'firstname' => 'Jane', 'lastname' => 'Doe'
     * ]);
     *
     * $collection3 = Collection::create()->associative(fn():array => [
     *  'one', 'two', 'three'
     * ]);
     *
     * $merged = $collection->merge($collection2, $collection3);
     *
     * // [
     * //   ['firstname' => ['John, Jane']],
     * //   ['lastname' => ['Doe', 'Doe']],
     * //   'age' => 23, 'one', 'two', 'three'
     * // ]
     * ```
     *
     * @param self<TKey, TValue> ...$collections <p>
     * Variable list of collections to recursively merge.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Multidimensional<array-key, array<array-key, mixed>> New recursively merged collection.
     */
    public function mergeRecursive (self ...$collections):Multidimensional {

        $storage = $this->storage;

        foreach ($collections as $collection)
            $storage = Arr::mergeRecursive($storage, $collection->all());

        /** @phpstan-ignore-next-line */
        return new Multidimensional(fn():array => $storage);

    }

    /**
     * ### New collection with items with the specified keys
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Helpers\Array\only() To get all values from array with the specified keys.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'
     * ]);
     *
     * $filtered = $collection->only('firstname', 'lastname');
     *
     * // ['firstname', 'lastname']
     * ```
     *
     * @param list<array-key> $keys <p>
     * List of keys to keep.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Associative<TKey,TValue> New filtered collection.
     */
    public function only (array $keys):self {

        return new self(fn():array => only($this->storage, $keys));

    }

    /**
     * ### Pick one or more random values out of the collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Helpers\Array\random() To pick one or more random values out of the array.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::count() To count elements of an object.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'
     * ]);
     *
     * $random = $collection->random();
     *
     * // 'Doe' - randomly selected
     * ```
     * @example Using more than one random item.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'
     * ]);
     *
     * $random = $collection->random(2);
     *
     * $random->all();
     *
     * // ['lastname' => 'Doe', 'height' => '190cm'] - randomly selected
     * ```
     *
     * @throws Error If a collection has zero items.
     *
     * @param positive-int $number <p>
     * Specifies how many entries you want to pick from a collection.
     * </p>
     *
     * @return mixed If you are picking only one entry, returns a random entry.
     * Otherwise, it returns a collection of random items.
     */
    public function random (int $number = 1):mixed {

        $count = $this->count();

        $number = $number > $count
            ? ($count === 0
                ? throw new Error('Collection must have at least one item to get random item.')
                : $count)
            : $number;

        $random = random($this->storage, $number, true);

        return $number > 1
            ? new self(fn():array => $random) // @phpstan-ignore-line
            : $random;

    }

    /**
     * ### Sort collection keys
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\Order::ASC As parameter.
     * @uses \FireHub\Core\Support\Enums\Sort::SORT_REGULAR As parameter.
     * @uses \FireHub\Core\Support\LowLevel\Arr::sortByKey() To sort array by key.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'
     * ]);
     *
     * $collection->sortKeys();
     *
     * // ['age' => 25, 'firstname' => 'John', 'gender' => 'male', 'height' => '190cm', 'lastname' => 'Doe']
     * ```
     * @example Sorting order.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     * use FireHub\Core\Support\Enums\Order;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'
     * ]);
     *
     * $collection->sortKeys(Order::DESC);
     *
     * // ['lastname' => 'Doe', 'height' => ''190cm', 'gender' => 'male', 'firstname' => John, 'age' => 25]
     * ```
     * @example Sorting order with a flag.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     * use FireHub\Core\Support\Enums\Order;
     * use FireHub\Core\Support\Enums\Sort;
     *
     * $collection = Collection::create()->associative(fn():array => ['one' => '1', 2 => '2']);
     *
     * $collection->sort(flag: Sort::SORT_NUMERIC);
     *
     * // [ 2 => '2', 'one' => '1']
     * ```
     *
     * @param \FireHub\Core\Support\Enums\Order $order [optional] <p>
     * Order type.
     * </p>
     * @param \FireHub\Core\Support\Enums\Sort $flag [optional] <p>
     * Sorting type.
     * </p>
     *
     * @return $this<TKey, TValue> Sorted collection.
     */
    public function sortKeys (Order $order = Order::ASC, Sort $flag = Sort::SORT_REGULAR):self {

        Arr::sortByKey($this->storage, $order, $flag);

        return $this;

    }

    /**
     * ### Sort collection keys using a user-defined comparison function
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::sortKeyBy() To sort array by key using a user-defined comparison function.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'
     * ]);
     *
     * $collection->sortKeysBy(function (mixed $a, mixed $b) {
     *  if ($a === $b) return 0;
     *  return ($a < $b) ? -1 : 1;
     * });
     *
     * // ['age' => 25, 'firstname' => 'John', 'gender' => 'male', 'height' => '190cm', 'lastname' => 'Doe']
     * ```
     *
     * @param callable(TKey $a, TKey $b):int<-1, 1> $callback <p>
     * The callback comparison function. Function cmp_function should accept two parameters which will be filled
     * by pairs of array keys. The comparison function must return an integer less than, equal to, or greater than
     * zero if the first argument is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     *
     * @return $this<TKey, TValue> Sorted collection.
     */
    public function sortKeysBy (callable $callback):self {

        Arr::sortKeyBy($this->storage, $callback);

        return $this;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @param null|TKey $offset <p>
     * Offset to assign the value to.
     * </p>
     * @param TValue $value <p>
     * Value to set.
     * </p>
     *
     * @throws TypeError If illegal offset type.
     */
    public function offsetSet (mixed $offset, mixed $value):void {

        $this->storage[$offset] = $value;

    }

}