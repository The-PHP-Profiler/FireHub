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

use FireHub\Core\Support\Zwick\ {
    DateTime, Interval
};
use FireHub\Core\Support\Enums\ {
    Data\Category, Data\Type, Operator\Comparison, Order, Sort
};
use FireHub\Core\Support\LowLevel\ {
    Arr, DataIs
};
use Closure, Error, TypeError, ValueError;

use function FireHub\Core\Support\Helpers\Array\ {
    except, filter_recursive, filter_recursive_type, sortByMany, only, random
};

/**
 * ### Multidimensional array collection type
 *
 * Collections containing one or more sub-arrays.
 * @since 1.0.0
 *
 * @template TKey of array-key
 * @template TValue of array<array-key, mixed>
 *
 * @extends \FireHub\Core\Support\Collections\Type\Arr\aArr<TKey, TValue>
 *
 * @api
 */
final class Multidimensional extends aArr {

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
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::count() To count all elements in the array.
     *
     * @example Count collection.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  [1, 2, 3],
     *  [4, 5, 6],
     *  [7, 8, 9]
     * ]);
     *
     * $collection->count();
     *
     * // 3
     * ```
     * @example Count a multidimensional collection.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  [1, 2, 3],
     *  [4, 5, 6],
     *  [7, 8, 9]
     * ]);
     *
     * $collection->count(true);
     *
     * // 12
     * ```
     *
     * @param bool $multi_dimensional [optional] <p>
     * Count multidimensional items.
     * </p>
     */
    public function count (bool $multi_dimensional = false):int {

        return Arr::count($this->storage, $multi_dimensional);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::countValues() To count all the values of an array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::column() To return the values from a single column in the input array.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::callable() To verify that the contents of a variable can be called
     * as a function.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::null To find whether a variable is null.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::countBy() To trigger parent method.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative As return.
     *
     * @example Using countBy method.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2],
     *  ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21, 10 => 1],
     *  ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
     * ]);
     *
     * $count = $collection->countBy('lastname');
     *
     * // ['Doe' => 2, 'Roe' => 1]
     * ```
     * @example Using countBy method with callable.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2],
     *  ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21, 10 => 1],
     *  ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
     * ]);
     *
     * $collection->countBy(function ($value, $key) {
     *  return $value['lastname'];
     * });
     *
     * // ['Doe' => 2, 'Roe' => 1]
     * ```
     *
     * @param int|string|callable(TValue $value, TKey $key):array-key $key_or_callable [optional] <p>
     * Count all items by a custom key or value.
     * </p>
     *
     * @throws Error If counted values are neither string nor int.
     * @error\exeption E_WARNING for every element that is not string nor int.
     */
    public function countBy (int|string|callable $key_or_callable = null):Associative {

        if (DataIs::callable($key_or_callable) || DataIs::null($key_or_callable))
            return parent::countBy($key_or_callable);

        /** @phpstan-ignore-next-line */
        return new Associative(
            /** @phpstan-ignore-next-line */
            fn():array => Arr::countValues(Arr::column($this->storage, $key_or_callable))
        );

    }

    /**
     * ### Update item from collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Multidimensional::replace() To replace item from a collection.
     * @uses \FireHub\Core\Support\LowLevel\Arr::replace() To replace the elements of one or more arrays together.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  'first' => ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
     *  'second' => ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21],
     *  'third' => ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
     * ]);
     *
     * $collection->update([
     *  'firstname' => 'Joe'
     * ], 'first');
     *
     * // [
     * //   'first' => ['firstname' => 'Joe', 'lastname' => 'Doe', 'age' => 25],
     * //   'second' => ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21],
     * //   'third' => ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
     * //]
     * ```
     *
     * @param array<array-key, mixed> $value <p>
     * Collection value.
     * </p>
     * @param TKey $key <p>
     * Collection key.
     * </p>
     *
     * @throws Error If key doesn't exist in a collection.
     *
     * @return void
     */
    public function update (array $value, mixed $key):void {

        $this->replace(Arr::replace($this->storage[$key], $value), $key);

    }

    /**
     * ### Update item from a collection recursively
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Multidimensional::replace() To replace item from a collection.
     * @uses \FireHub\Core\Support\LowLevel\Arr::replaceRecursive() To replace the elements of one or more arrays
     * together recursively.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  'first' => ['name' => ['firstname' => 'Joe'], 'age' => 25],
     *  'second' => ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     *  'third' => ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * ]);
     *
     * $collection->update([
     *  'name' => ['firstname' => 'Joe']
     * ], 'first');
     *
     * // [
     * //   'first' => ['name' => ['firstname' => 'Joe'], 'age' => 25],
     * //   'second' => ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     * //   'third' => ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * //]
     *
     * $collection->updateRecursive([
     *  'name' => ['firstname' => 'Joe']
     * ], 'first');
     *
     * // [
     * //   'first' => ['name' => ['firstname' => 'Joe', 'lastname' => 'Doe'], 'age' => 25],
     * //   'second' => ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     * //   'third' => ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * //]
     * ```
     *
     * @param array<array-key, mixed> $value <p>
     * Collection value.
     * </p>
     * @param TKey $key <p>
     * Collection key.
     * </p>
     *
     * @throws Error If key doesn't exist in a collection.
     *
     * @return void
     */
    public function updateRecursive (array $value, mixed $key):void {

        $this->replace(Arr::replaceRecursive($this->storage[$key], $value), $key);

    }

    /**
     * @inheritDoc
     *
     * @uses \FireHub\Core\Support\Zwick\Interval As parameter.
     * @uses \FireHub\Core\Support\Zwick\Interval::minutes() To create an interval specifying a number of minutes.
     * @uses \FireHub\Core\Support\Zwick\DateTime::now() To create datetime with current date and time.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  'first' => ['name' => ['firstname' => 'Joe'], 'age' => 25],
     *  'second' => ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     *  'third' => ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
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
     * @param null|\FireHub\Core\Support\Zwick\Interval $timeout [optional] <p>
     * Maximum execution time for a script.
     * Default is 30 minutes.
     * </p>
     * @param positive-int $limit [optional] <p>
     * Maximum number of elements that is allowed to be iterated.
     */
    public function each (callable $callback, Interval $timeout = null, int $limit = 1_000_000):bool {

        $counter = 0;

        $now = DateTime::now();

        $timeout = $timeout
            ? DateTime::now()->add($timeout)
            : DateTime::now()->add(Interval::minutes(30));

        if ($timeout <= $now) return false;

        foreach ($this->storage as $key => $value)
            if (
                $callback($value, $key) === false
                || $timeout <= DateTime::now()
                || $counter++ > $limit
            ) return false;

        return true;

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
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  'first' => ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
     *  'second' => ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21],
     *  'third' => ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
     * ]);
     *
     * $filtered = $collection->except('first', 'second');
     *
     * // ['third' => ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]]
     * ```
     *
     * @param list<array-key> $keys <p>
     * List of keys to filter out.
     * </p>
     *
     * @error\exeption E_WARNING if values on $array argument are neither int nor string.
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Multidimensional<TKey, TValue> New filtered collection.
     */
    public function except (array $keys):self {

        return new self(fn():array => except($this->storage, $keys));

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
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  'first' => ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
     *  'second' => ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21],
     *  'third' => ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
     * ]);
     *
     * $keys = $collection->keys();
     *
     * // ['first', 'second', 'third']
     * ```
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Indexed<TKey> Collection's keys.
     */
    public function keys ():Indexed {

        return new Indexed(fn():array => Arr::keys($this->storage));

    }

    /**
     * ### Applies the callback to each collection key
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  'first' => ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
     *  'second' => ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21],
     *  'third' => ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
     * ]);
     *
     * $map = $collection->mapKeys(function ($value, $key) {
     *  return 'new '.$key;
     * });
     *
     * // [
     * //   'new first' => ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
     * //   'new second' => ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21],
     * //   'new third' => ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
     * // ]
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

        /** @phpstan-ignore-next-line */
        return new self(fn():array => $storage);

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
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  'first' => ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
     *  'second' => ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21],
     *  'third' => ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
     * ]);
     *
     * $filtered = $collection->only('first');
     *
     * // ['first' => ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25]]
     * ```
     *
     * @param list<array-key> $keys <p>
     * List of keys to keep.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Multidimensional<TKey,TValue> New filtered collection.
     */
    public function only (array $keys):self {

        return new self(fn():array => only($this->storage, $keys));

    }

    /**
     * ### Get the values from given key
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::column() To return the values from a single column in the input array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::combine() To create an array by using one array for keys and another
     * for its values.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed As return.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     *  ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     *  ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * ]);
     *
     * $pluck = $collection->pluck('firstname');
     *
     * // ['John', 'Jane', 'Richard']
     * ```
     * @example With custom keys
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     *  ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     *  ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * ]);
     *
     * $pluck = $collection->pluck('firstname', 'age');
     *
     * // [25 => 'John', 21 => 'Jane', 27 => 'Richard']
     * ```
     *
     * @param array-key $key <p>
     * Key to pluck.
     * </p>
     * @param null|array-key $pluck_key [optional] <p>
     * Keys for plucked values.
     * </p>
     *
     * @return ($pluck_key is null
     *  ? \FireHub\Core\Support\Collections\Type\Arr\Indexed<mixed>
     *  : \FireHub\Core\Support\Collections\Type\Arr\Associative<array-key, mixed>
     * ) New collection with plucked values.
     */
    public function pluck (int|string $key, int|string $pluck_key = null):Indexed|Associative {

        $values = Arr::column($this->storage, $key);

        if ($pluck_key) {

            $keys = Arr::column($this->storage, $pluck_key);

            /** @phpstan-ignore-next-line */
            return new Associative(fn():array => Arr::combine($keys, $values));

        }

        return new Indexed(fn():array => $values);

    }

    /**
     * ### Pick one or more random values out of the collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::column() To return the values from a single column in the input array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::merge() To merge the elements of one or more arrays together.
     * @uses \FireHub\Core\Support\Helpers\Array\random() To pick one or more random values out of the array.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Multidimensional::count() To count elements of an object.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     *  ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     *  ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * ]);
     *
     * $random = $collection->random();
     *
     * // ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21] - randomly selected
     * ```
     * @example Using more than one random item.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     *  ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     *  ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * ]);
     *
     * $random = $collection->random(2);
     *
     * $random->all();
     *
     * // [
     * //   ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     * //   ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * // ] - randomly selected
     * ```
     * @example Using keys.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     *  ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     *  ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * ]);
     *
     * $random = $collection->random(1, 'name', 'age');
     *
     * // 21 - randomly selected
     * ```
     * @example Using keys with more than one random item.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     *  ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     *  ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * ]);
     *
     * $random = $collection->random(2, 'name', 'age');
     *
     * $random->all();
     *
     * // [21, 27] - randomly selected
     * ```
     *
     * @param positive-int $number <p>
     * Specifies how many entries you want to pick from a collection.
     * </p>
     * @param int|string ...$keys [optional] <p>
     * Keys to pick from.
     * </p>
     *
     * @throws Error If a collection has zero items.
     * @throws ValueError If $number is not between 1 and the number of elements in argument.
     *
     * @return mixed If you are picking only one entry, returns the key for a random entry.
     * Otherwise, it returns a collection of random items.
     */
    public function random (int $number = 1, int|string ...$keys):mixed {

        if ($keys) {

            $storage = [];
            foreach ($keys as $key) $storage[] = Arr::column($this->storage, $key);

            $random = random(Arr::merge(...$storage), $number);

        } else $random = random($this->storage, $number);

        $count = $this->count();

        $number = $number > $count
            ? ($count === 0
                ? throw new Error('Collection must have at least one item to get random item.')
                : $count)
            : $number;

        return $number > 1
            ? new self(fn():array => $random) // @phpstan-ignore-line
            : $random;

    }

    /**
     * @inheritDoc
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::null() To find whether a variable is null.
     * @uses \FireHub\Core\Support\LowLevel\Arr::search() To search the array for a given value and returns the first
     * corresponding key if successful.
     * @uses \FireHub\Core\Support\LowLevel\Arr::column() To return the values from a single column in the input array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  'first' => ['name' => ['firstname' => 'Joe'], 'age' => 25],
     *  'second' => ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     *  'third' => ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * ]);
     *
     * $collection->search('Doe', 'lastname');
     *
     * // second
     * ```
     */
    public function search (mixed $value, int|string $column = null):mixed {

        return DataIs::null($column)
            ? Arr::search($value, $this->storage)
            : Arr::search($value, Arr::column($this->storage, $column));

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
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  'first' => ['name' => ['firstname' => 'Joe'], 'age' => 25],
     *  'second' => ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     *  'third' => ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * ]);
     *
     * $collection->sortKeys(Order::DESC);
     *
     * // [
     * //  'third' => ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27],
     * //  'second' => ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     * //  'first' => ['name' => ['firstname' => 'Joe'], 'age' => 25]
     * // ]
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
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  'first' => ['name' => ['firstname' => 'Joe'], 'age' => 25],
     *  'second' => ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     *  'third' => ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * ]);
     *
     * $collection->sortKeysBy(function (mixed $a, mixed $b) {
     *  if ($a === $b) return 0;
     *  return ($b < $a) ? -1 : 1;
     * });
     *
     * // [
     * //  'third' => ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27],
     * //  'second' => ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     * //  'first' => ['name' => ['firstname' => 'Joe'], 'age' => 25]
     * // ]
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
     * ### Sort collection keys using a user-defined comparison function
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Helpers\Array\sortByMany() To sort multiple or multidimensional arrays.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  ['id' => 1, 'firstname' => 'John', 'lastname' => 'Doe', 'gender' => 'male', 'age' => 25],
     *  ['id' => 2, 'firstname' => 'Jane', 'lastname' => 'Doe', 'gender' => 'female', 'age' => 23],
     *  ['id' => 3, 'firstname' => 'Richard', 'lastname' => 'Roe', 'gender' => 'male', 'age' => 27],
     *  ['id' => 4, 'firstname' => 'Jane', 'lastname' => 'Roe', 'gender' => 'female', 'age' => 22],
     *  ['id' => 5, 'firstname' => 'John', 'lastname' => 'Roe', 'gender' => 'male', 'age' => 26]
     * ]);
     *
     * $collection->sortByMany([
     *  'lastname' => Order::ASC
     *  'age' => Order::DESC
     * ]);
     *
     * // [
     * //   ['id' => 1, 'firstname' => 'John', 'lastname' => 'Doe', 'gender' => 'male', 'age' => 25],
     * //   ['id' => 2, 'firstname' => 'Jane', 'lastname' => 'Doe', 'gender' => 'female', 'age' => 23],
     * //   ['id' => 3, 'firstname' => 'Richard', 'lastname' => 'Roe', 'gender' => 'male', 'age' => 27],
     * //   ['id' => 5, 'firstname' => 'John', 'lastname' => 'Roe', 'gender' => 'male', 'age' => 26],
     * //   ['id' => 4, 'firstname' => 'Jane', 'lastname' => 'Roe', 'gender' => 'female', 'age' => 22]
     * // ]
     * ```
     *
     * @param array<array<TKey, string|\FireHub\Core\Support\Enums\Order>> $fields <p>
     * List of fields to sort by.
     * </p>
     *
     * @throws ValueError If array sizes are inconsistent.
     *
     * @return $this<TKey, TValue> Sorted collection.
     */
    public function sortByMany (array $fields):self {

        sortByMany($this->storage, $fields);

        return $this;

    }

    /**
     * ### Filters the collection by a given key / value pair
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\Operator\Comparison As parameter.
     * @uses \FireHub\Core\Support\Helpers\Array\filter_recursive() To filter elements in an array recursively.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     * use FireHub\Core\Support\Enums\Operator\Comparison;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     *  ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     *  ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * ]);
     *
     * $filter = $collection->where('lastname', Comparison::EQUAL, 'Doe');
     *
     * // [
     * //   ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     * //   ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21]
     * // ]
     * ```
     *
     * @param array-key $key <p>
     * Key to filter on.
     * </p>
     * @param \FireHub\Core\Support\Enums\Operator\Comparison $operator <p>
     * Operator for filtering.
     * </p>
     * @param mixed $value <p>
     * Value to filter.
     * </p>
     *
     * @return self<TKey, TValue> New filtered collection.
     */
    public function where (int|string $key, Comparison $operator, mixed $value):self {

        return new self(
            fn():array => filter_recursive($key, $operator, $value, $this->storage)
        );

    }

    /**
     * ### Filters the collection by removing a given key / value pair
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\Operator\Comparison As parameter.
     * @uses \FireHub\Core\Support\Helpers\Array\filter_recursive() To filter elements in an array recursively.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     * use FireHub\Core\Support\Enums\Operator\Comparison;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     *  ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     *  ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * ]);
     *
     * $filter = $collection->whereNot('lastname', Comparison::EQUAL, 'Doe');
     *
     * // [
     * //   ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * // ]
     * ```
     *
     * @param array-key $key <p>
     * Key to filter on.
     * </p>
     * @param \FireHub\Core\Support\Enums\Operator\Comparison $operator <p>
     * Operator for filtering.
     * </p>
     * @param mixed $value <p>
     * Value to filter.
     * </p>
     *
     * @return self<TKey, TValue> New filtered collection.
     */
    public function whereNot (int|string $key, Comparison $operator, mixed $value):self {

        return new self(
            fn():array => filter_recursive($key, $operator, $value, $this->storage, false)
        );

    }

    /**
     * ### Filters the collection by determining if value is within a given range
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Multidimensional::where() To filter the collection by a given
     * key / value pair.
     * @uses \FireHub\Core\Support\Enums\Operator\Comparison::GREATER_OR_EQUAL To compare values.
     * @uses \FireHub\Core\Support\Enums\Operator\Comparison::LESS_OR_EQUAL To compare values.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     * use FireHub\Core\Support\Enums\Operator\Comparison;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     *  ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     *  ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * ]);
     *
     * $filter = $collection->whereBetween('age', 24, 27);
     *
     * // [
     * //   ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     * //   ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * // ]
     * ```
     *
     * @param array-key $key <p>
     * Key to filter on.
     * </p>
     * @param mixed $start <p>
     * Lowest value to filter.
     * </p>
     * @param mixed $end <p>
     * Highest value to filter.
     * </p>
     *
     * @return self<TKey, TValue> New filtered collection.
     */
    public function whereBetween (int|string $key, mixed $start, mixed $end):self {

        return $this
            ->where($key, Comparison::GREATER_OR_EQUAL, $start)
            ->where($key, Comparison::LESS_OR_EQUAL, $end);

    }

    /**
     * ### Filters the collection by determining if value is not within a given range
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Multidimensional::where() To filter the collection by a given
     * key / value pair.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Multidimensional::all() To get a collection as an array.
     * @uses \FireHub\Core\Support\Enums\Operator\Comparison::LESS To compare values.
     * @uses \FireHub\Core\Support\Enums\Operator\Comparison::GREATER To compare values.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     * use FireHub\Core\Support\Enums\Operator\Comparison;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     *  ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     *  ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * ]);
     *
     * $filter = $collection->whereNotBetween('age', 24, 27);
     *
     * // [
     * //   ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21]
     * // ]
     * ```
     *
     * @param array-key $key <p>
     * Key to filter on.
     * </p>
     * @param mixed $start <p>
     * Lowest value to filter.
     * </p>
     * @param mixed $end <p>
     * Highest value to filter.
     * </p>
     *
     * @return self<TKey, TValue> New filtered collection.
     */
    public function whereNotBetween (int|string $key, mixed $start, mixed $end):self {

        $storage = $this->where($key, Comparison::LESS, $start)->all();
        $storage += $this->where($key, Comparison::GREATER, $end)->all();

        /** @phpstan-ignore-next-line */
        return new self(fn():array => $storage);

    }

    /**
     * ### Filters the collection by determining if value is within a given range
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Multidimensional::where() To filter the collection by a given
     * key / value pair.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Multidimensional::all() To get a collection as an array.
     * @uses \FireHub\Core\Support\Enums\Operator\Comparison::EQUAL To compare values.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     * use FireHub\Core\Support\Enums\Operator\Comparison;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     *  ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     *  ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * ]);
     *
     * $filter = $collection->whereIn('firstname', ['John', 'Richard']);
     *
     * // [
     * //   ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     * //   ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * // ]
     * ```
     *
     * @param array-key $key <p>
     * Key to filter on.
     * </p>
     * @param list<mixed> $values <p>
     * Values to filter.
     * </p>
     *
     * @return self<TKey, TValue> New filtered collection.
     */
    public function whereIn (int|string $key, array $values):self {

        $storage = [];
        foreach ($values as $value)
            $storage += $this->where($key, Comparison::EQUAL, $value)->all();

        /** @phpstan-ignore-next-line */
        return new self(fn():array => $storage);

    }

    /**
     * ### Filters the collection by determining if value is not within a given range
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Multidimensional::where() To filter the collection by a given
     * key / value pair.
     * @uses \FireHub\Core\Support\Enums\Operator\Comparison::NOT_EQUAL To compare values.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     * use FireHub\Core\Support\Enums\Operator\Comparison;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     *  ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21],
     *  ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * ]);
     *
     * $filter = $collection->whereNotIn('firstname', ['John', 'Richard']);
     *
     * // [
     * //   ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 21]
     * // ]
     * ```
     *
     * @param array-key $key <p>
     * Key to filter on.
     * </p>
     * @param list<mixed> $values <p>
     * Values to filter.
     * </p>
     *
     * @return self<TKey, TValue> New filtered collection.
     */
    public function whereNotIn (int|string $key, array $values):self {

        $collection = $this;

        foreach ($values as $value)
            $collection = $collection->where($key, Comparison::NOT_EQUAL, $value);

        return $collection;

    }

    /**
     * ### Filters the collection with value type
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Helpers\Array\filter_recursive_type() To filter elements in an array recursively with
     * a value type.
     * @uses \FireHub\Core\Support\Enums\Data\Category As parameter.
     * @uses \FireHub\Core\Support\Enums\Data\Type As parameter.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     * use FireHub\Core\Support\Enums\Data\Type;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     *  ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 'Twenty-one'],
     *  ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * ]);
     *
     * $filter = $collection->whereDataIs('age', Type::T_INT);
     *
     * // [
     * //   ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     * //   ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * // ]
     * ```
     *
     * @param array-key $key <p>
     * Key to filter on.
     * </p>
     * @param \FireHub\Core\Support\Enums\Data\Category|\FireHub\Core\Support\Enums\Data\Type $type <p>
     * Data to filter.
     * </p>
     *
     * @return self<TKey, TValue> New filtered collection.
     */
    public function whereDataIs (int|string $key, Category|Type $type):self {

        return new self(
            fn():array => filter_recursive_type($key, $type,$this->storage)
        );

    }

    /**
     * ### Filters the collection with a value type removed
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Helpers\Array\filter_recursive_type() To filter elements in an array recursively with
     * a value type.
     * @uses \FireHub\Core\Support\Enums\Data\Category As parameter.
     * @uses \FireHub\Core\Support\Enums\Data\Type As parameter.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     * use FireHub\Core\Support\Enums\Data\Type;
     *
     * $collection = Collection::create()->multidimensional(fn():array => [
     *  ['name' => ['firstname' => 'John', 'lastname' => 'Doe'], 'age' => 25],
     *  ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 'Twenty-one'],
     *  ['name' => ['firstname' => 'Richard', 'lastname' => 'Roe'], 'age' => 27]
     * ]);
     *
     * $filter = $collection->whereDataIsNot('age', Type::T_INT);
     *
     * // [
     * //   ['name' => ['firstname' => 'Jane', 'lastname' => 'Doe'], 'age' => 'Twenty-one']
     * // ]
     * ```
     *
     * @param array-key $key <p>
     * Key to filter on.
     * </p>
     * @param \FireHub\Core\Support\Enums\Data\Category|\FireHub\Core\Support\Enums\Data\Type $type <p>
     * Data to filter.
     * </p>
     *
     * @return self New filtered collection.
     * @phpstan-return self<TKey, TValue>
     */
    public function whereDataIsNot (int|string $key, Category|Type $type):self {

        return new self(
            fn():array => filter_recursive_type($key, $type,$this->storage, false)
        );

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::null() To find whether a variable is null.
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

        DataIs::null($offset) ? $this->storage[] = $value : $this->storage[$offset] = $value;

    }

}