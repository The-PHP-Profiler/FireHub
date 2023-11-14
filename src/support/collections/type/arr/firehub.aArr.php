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

use FireHub\Core\Base\ {
    Base, Master
};
use FireHub\Core\Support\Collections\Collectable;
use FireHub\Core\Support\Contracts\ArrayAccessible;
use FireHub\Core\Support\Zwick\ {
    DateTime, Interval
};
use FireHub\Core\Support\Collections\Traits\ {
    Conditionable, Convertable, Overloadable
};
use FireHub\Core\Support\Enums\ {
    Order, Sort
};
use FireHub\Core\Support\LowLevel\ {
    Arr, DataIs, NumInt
};
use ArgumentCountError, ArithmeticError, Closure, DivisionByZeroError, Error, Traversable, TypeError;

use function FireHub\Core\Support\Helpers\Array\ {
    is_empty, first, last, shuffle
};

/**
 * ### Array collection type
 *
 * Array Collection type is a collection that has the main focus of performance
 * and doesn't concern itself about memory consumption.
 * This collection can hold any type of data.
 * @since 1.0.0
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @implements \FireHub\Core\Support\Collections\Collectable<TKey, TValue>
 * @implements \FireHub\Core\Support\Contracts\ArrayAccessible<TKey, TValue>
 */
abstract class aArr implements Master, Collectable, ArrayAccessible {

    /**
     * ### FireHub base class trait
     * @since 1.0.0
     */
    use Base;

    /**
     * ### This trait allows usage of conditionable methods for collection
     * @since 1.0.0
     *
     * @use \FireHub\Core\Support\Collections\Traits\Conditionable
     */
    use Conditionable;

    /**
     * ### This trait allows converting a collection to a different one.
     * @since 1.0.0
     *
     * @use \FireHub\Core\Support\Collections\Traits\Convertable
     */
    use Convertable;

    /**
     * ### Allows usage of property overloading for collections
     * @since 1.0.0
     *
     * @use \FireHub\Core\Support\Collections\Traits\Overloadable<TKey, TValue>
     */
    use Overloadable {
        Overloadable::__isset insteadof Base;
        Overloadable::__get insteadof Base;
        Overloadable::__set insteadof Base;
        Overloadable::__unset insteadof Base;
    }

    /**
     * ### Storage underlying data
     * @since 1.0.0
     *
     * @var array<TKey, TValue>
     */
    protected array $storage = [];

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @return array<TKey, TValue> Storage data.
     */
    public function storage ():array {

        return $this->storage;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::count() To count all elements in the array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $collection->count();
     *
     * // 3
     * ```
     */
    public function count ():int {

        return Arr::count($this->storage);

    }

    /**
     * ### Counts the occurrences of values in the collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::countValues() To count all the values of an array.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::int() To find whether the type of variable is an integer.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::string() To find whether the type of variable is a string.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::bool() To find whether the type of variable is a boolean.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative As return.
     *
     * @example Using countBy method.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $count = $collection->countBy();
     *
     * // ['John' => 1, 'Jane' => 3, 'Richard' => 2]
     * ```
     * @example Using countBy method with callable.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $count = $collection->countBy(function ($value, $key) {
     *  return substr($value, 0, 1);
     * });
     *
     * // ['J' => 4, 'R' => 2]
     * ```
     *
     * @param null|callable(TValue $value, TKey $key):array-key $callback $callback [optional] <p>
     * Count all items by custom callable.
     * </p>
     *
     * @throws Error If counted values are neither string nor int.
     * @error\exeption E_WARNING for every element that is not string nor int.
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Associative<TValue, positive-int> New collection with group
     * items.
     *
     * @phpstan-ignore-next-line
     */
    public function countBy (callable $callback = null):Associative {

        /** @phpstan-ignore-next-line */
        if ($callback) return new Associative(function () use ($callback):array {

            $storage = [];

            foreach ($this->storage as $key => $value) {

                $callable = $callback($value, $key);

                if (!DataIs::int($callable) && !DataIs::string($callable) && !DataIs::bool($callable))
                    throw new Error('Cannot count values that are neither string nor int.');

                $storage[$callable] = ($storage[$callable] ?? 0) + 1;

            }

            return $storage;

        });

        /** @phpstan-ignore-next-line */
        return new Associative(fn():array => Arr::countValues($this->storage)
            ?: throw new Error('Could not count collection.'));

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $collection->all();
     *
     * // ['one', 'two', 'three']
     * ```
     *
     * @return array<TKey, TValue> Collection items as an array.
     */
    public function all ():array {

        return $this->storage;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Helpers\Array\first() To get the first value from an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'one', 'one', 'two', 'two', 'three', 'three', 'three']);
     *
     * $collection->first();
     *
     * // 'one'
     * ```
     * @example With $callback parameter.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'one', 'one', 'two', 'two', 'three', 'three', 'three']);
     *
     * $collection->first(function ($value, $key) {
     *  return $key > 2;
     * });
     *
     * // 'two'
     * ```
     *
     * @param null|callable(TValue $value, TKey $key):TValue $callback [optional] <p>
     * If callback is used, the method will return the first item that passes truth test.
     * </p>
     */
    public function first (callable $callback = null):mixed {

        if ($callback) {

            foreach ($this->storage as $key => $value)
                if ($callback($value, $key)) return $value;

            return null;

        }

        return first($this->storage);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::firstKey() To get the first key from an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'one', 'one', 'two', 'two', 'three', 'three', 'three']);
     *
     * $collection->first();
     *
     * // 0
     * ```
     * @example With $callback parameter.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'one', 'one', 'two', 'two', 'three', 'three', 'three']);
     *
     * $collection->first(function ($value, $key) {
     *  return $key > 2;
     * });
     *
     * // 3
     * ```
     *
     * @param null|callable(TValue $value, TKey $key):TKey $callback [optional] <p>
     * If callback is used, the method will return the first key that passes truth test.
     * </p>
     */
    public function firstKey (callable $callback = null):mixed {

        if ($callback) {

            foreach ($this->storage as $key => $value)
                if ($callback($value, $key)) return $key;

            return null;

        }

        return Arr::firstKey($this->storage);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Helpers\Array\last() To get the last value from an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'one', 'one', 'two', 'two', 'three', 'three', 'three']);
     *
     * $collection->last();
     *
     * // 'three'
     * ```
     * @example With $callback parameter.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'one', 'one', 'two', 'two', 'three', 'three', 'three']);
     *
     * $collection->last(function ($value, $key) {
     *  return $key < 2;
     * });
     *
     * // 'one'
     * ```
     *
     * @param null|callable(TValue $value, TKey $key):TValue $callback [optional] <p>
     * If callback is used, the method will return the last item that passes truth test.
     * </p>
     */
    public function last (callable $callback = null):mixed {

        if ($callback) {

            $found = null;

            foreach ($this->storage as $key => $value)
                if ($callback($value, $key)) $found = $value;

            return $found;

        }

        return last($this->storage);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::lastKey() To get the last key from an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'one', 'one', 'two', 'two', 'three', 'three', 'three']);
     *
     * $collection->lastKey();
     *
     * // 7
     * ```
     * @example With $callback parameter.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'one', 'one', 'two', 'two', 'three', 'three', 'three']);
     *
     * $collection->lastKey(function ($value, $key) {
     *  return $key < 2;
     * });
     *
     * // 2
     * ```
     *
     * @param null|callable(TValue $value, TKey $key):TKey $callback [optional] <p>
     * If callback is used, the method will return the last key that passes truth test.
     * </p>
     */
    public function lastKey (callable $callback = null):mixed {

        if ($callback) {

            $found = null;

            foreach ($this->storage as $key => $value)
                if ($callback($value, $key)) $found = $key;

            return $found;

        }

        return Arr::lastKey($this->storage);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Zwick\Interval As parameter.
     * @uses \FireHub\Core\Support\Zwick\Interval::minutes() To create an interval specifying a number of minutes.
     * @uses \FireHub\Core\Support\Zwick\DateTime::now() To create datetime with current date and time.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $collection->each(function ($value) {
     *  if (* condition *) {
     *      return false;
     *  }
     * });
     * ```
     */
    public function each (callable $callback, Interval $timeout = null, int $limit = 1_000_000):bool {

        $counter = 0;

        $now = DateTime::now();

        $timeout = $timeout
            ? DateTime::now()->add($timeout)
            : DateTime::now()->add(Interval::minutes(30));

        if ($timeout <= $now) return false;

        foreach ($this->storage as $value)
            if (
                $callback($value) === false
                || $timeout <= DateTime::now()
                || $counter++ > $limit
            ) return false;

        return true;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::callable() To verify that the contents of a variable can be called
     * as a function.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::first() To get the first item from a collection.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::search() To search the collection for a given value and
     * returns the first corresponding key if successful.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $collection->contains('two');
     *
     * // true
     * ```
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => [1, 2, 3, 4, 5]);
     *
     * $collection->contains(function ($value, $key) {
     *  return $value >= 3;
     * });
     *
     * // true
     * ```
     */
    public function contains (mixed $value):bool {

        return DataIs::callable($value)
            ? !($this->first($value) === false)
            : !($this->search($value) === false);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::contains() To determine whether a collection contains a
     * given item.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $collection->doesntContain('two');
     *
     * // false
     * ```
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => [1, 2, 3, 4, 5]);
     *
     * $collection->doesntContain(function ($value, $key) {
     *  return $value >= 3;
     * });
     *
     * // false
     * ```
     */
    public function doesntContains (mixed $value):bool {

        return !$this->contains($value);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => [1, 2, 3, 4, 5]);
     *
     * $collection->every(function ($value, $key) {
     *  return is_int($value);
     * });
     *
     * // true
     * ```
     *
     * @param callable(TValue $value, TKey $key):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     */
    public function every (callable $callback):bool {

        foreach ($this->storage as $key => $value) if (!$callback($value, $key)) return false;

        return true;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::filter() To filter elements in an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5
     * ]);
     *
     * $filtered = $collection->filter(function ($value, $key) {
     *  return $key !== 'four' && $value > 2;
     * });
     *
     * // ['three' => 3, 'five' => 5]
     * ```
     *
     * @param null|callable(TValue $value, TKey $key):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     */
    public function filter (?callable $callback):static {

        return new static(fn():array => Arr::filter($this->storage, $callback, true));

    }

    /**
     * ### Group collection by user-defined function until a result is true
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::reduce() To iteratively reduce the array to a single value using a
     * callback function.
     * @uses \FireHub\Core\Support\LowLevel\Arr::keys() To return all the keys or a subset of the keys of an array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::count() To count all elements in the array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::end() To set the internal pointer of an array to its last element.
     * @uses \FireHub\Core\Support\LowLevel\Arr::key() To fetch a key from an array.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Multidimensional As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->indexed(fn():array => [1, 2, 3, 4, 13, 22, 27, 28, 29]);
     *
     * $chunks = $collection->groupBy(function ($prev, $curr) {
     *  return ($curr - $prev) > 1;
     * });
     *
     * // [[1, 2, 3, 4], [13], [22], [27, 28, 29]]
     * ```
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->multidimensional(fn ():array => [
     *  ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
     *  ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21],
     *  ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
     * ]);
     *
     * $chunks = $collection->groupBy(function ($prev, $curr) {
     *  return $curr !== 'Doe';
     * });
     *
     * // [
     * //   [
     * //       ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
     * //       ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21]
     * //   ],
     * //   [
     * //       ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
     * //   ]
     * // ]
     * ```
     *
     * @param callable(TValue $prev, TValue $curr):bool $callback <p>
     * User-defined function.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Multidimensional<int, array<TKey, TValue>>
     * The grouped collection.
     *
     * @caution This method will remove keys from a collection.
     */
    public function groupBy (callable $callback):Multidimensional {

        /** @phpstan-ignore-next-line */
        return new Multidimensional(fn():array => Arr::reduce(Arr::keys($this->storage),
            function (array $carry, int|string $key) use ($callback):array { // @phpstan-ignore-line

                $current = $this->storage[$key];
                $length = Arr::count($carry);

                if ($length > 0) {

                    $chunk = &$carry[$length - 1];
                    Arr::end($chunk); // @phpstan-ignore-line
                    $previous = $chunk[Arr::key($chunk)];

                    if ($callback($previous, $current)) $carry[] = [$key => $current];
                    else $chunk[$key] = $current;

                } else $carry[] = [$key => $current];

                return $carry;

            }, []));

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Helpers\Array\is_empty() To check if an array is empty.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $collection->isEmpty();
     *
     * // false
     * ```
     */
    public function isEmpty ():bool {

        return is_empty($this->storage);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::isEmpty() To check if a collection is empty.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $collection->isNotEmpty();
     *
     * // true
     * ```
     */
    public function isNotEmpty ():bool {

        return !static::isEmpty();

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::map() To apply the callback to the elements of the given array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $map = $collection->map(function ($value, $key) {
     *  return 'new '.$value;
     * });
     *
     * // ['new one', 'new two', 'new three']
     * ```
     *
     * @param callable(TValue $value, TKey $key):mixed $callback <p>
     * Function to call on each item in collection.
     * </p>
     */
    public function map (callable $callback):static {

        try {

            /**
             * Try with value only
             * @phpstan-ignore-next-line
             */
            return new static(fn():array => Arr::map($this->storage, $callback));

        } catch (ArgumentCountError) {

            $original_storage = $this->storage;

            return new static(function () use ($original_storage, $callback):array {

                foreach ($original_storage as $key => $value) $storage[$key] = $callback($value, $key);

                return $storage ?? [];

            });

        }

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collectable::all() To get a collection as an array.
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
     * $collection3 = Collection::create(fn():array => [
     *  'one', 'two', 'three'
     * ]);
     *
     * $merged = $collection->merge($collection2, $collection3);
     *
     * // [
     * //   'firstname' => 'firstname: John', 'lastname' => 'Doe', 'age' => 25,
     * //   0 => 'one', 1 => 'two', 2 => 'three'
     * // ]
     * ```
     *
     * @return self<array-key, mixed> New merged collection.
     */
    public function merge (Collectable ...$collections):self {

        $storage = $this->storage;

        foreach ($collections as $collection)
            $storage = $storage + $collection->all();

        return new static(fn():array => $storage);

    }

    /**
     * ### New collection consisting of every n-th element
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::slice() To get a slice from a collection.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::all() To get a collection as an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $nth = $collection->nth(2);
     *
     * // ['one', 'three', 'five']
     * ```
     * @example With offset.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $nth = $collection->nth(2, 1);
     *
     * // ['two', 'four']
     * ```
     *
     * @param int $step <p>
     * N-th step.
     * </p>
     * @param int $offset [optional] <p>
     * Starting offset.
     * </p>
     *
     * @return self<TKey, TValue> New filtered collection.
     */
    public function nth (int $step, int $offset = 0):self {

        $storage = [];

        $counter = 0;

        foreach (
            $offset > 0
                ? $this->slice($offset)->all()
                : $this->storage as $key => $value) {

            if ($counter % $step === 0) $storage[$key] = $value;

            $counter++;

        }

        return new static(fn():array => $storage);

    }

    /**
     * ### Separate collection items that pass a given truth test from those that do not
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * [$passed, $failed] = $collection->partition(function ($value, $key) {
     *  return $key >= 3;
     * });
     *
     * $passed->all();
     *
     * // ['four', 'five']
     *
     * $failed->all();
     *
     * // ['one', 'two', 'three']
     * ```
     *
     * @param callable(TValue $value, TKey $key):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return static{static<TKey, TValue>, static<TKey, TValue>}
     */
    public function partition (callable $callback):static {

        $passed = new static(fn():array => []);
        $failed = new static(fn():array => []);

        foreach ($this->storage as $key => $value)
            $callback($value, $key)
                ? $passed[$key] = $value
                : $failed[$key] = $value;

        return new static(fn():array => [$passed, $failed]);

    }

    /**
     * ### Reduces the collection to a single value, passing the result of each iteration into the subsequent iteration
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * collection = Collection::create()->indexed(fn():array => [1, 2, 3]);
     *
     * $reduce = $collection->reduce(function ($init, $value, $key) {
     *  return $init + $value;
     * });
     *
     * // 6
     * ```
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * collection = Collection::create()->indexed(fn():array => [1, 2, 3]);
     *
     * $reduce = $collection->reduce(function ($init, $value, $key) {
     *  return $init && $value < 5;
     * }, true);
     *
     * // true
     * ```
     *
     * @param callable(mixed $init, TValue $value, TKey $key):mixed $callback <p>
     * The callable function.
     * </p>
     * @param mixed $initial [optional] <p>
     * If the optional initial is available, it will be used at the beginning of the process,
     * or as a final result in case the array is empty.
     * </p>
     *
     * @return mixed Reduced value.
     */
    public function reduce (callable $callback, mixed $initial = null):mixed {

        foreach ($this->storage as $key => $value) $initial = $callback($initial, $value, $key);

        return $initial;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::filter() To filter from a collection.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5
     * ]);
     *
     * $reject = $collection->reject(function ($value, $key) {
     *  return $value > 3 || $key === 'one';
     * });
     *
     * // ['two' => 2, 'three' => 3]
     * ```
     *
     * @param callable(TValue $value, TKey $key):bool $callback $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<TKey, TValue> New rejected collection.
     */
    public function reject (callable $callback):self {

        return $this->filter(fn($value, $key) => $value != $callback($value, $key));

    }

    /**
     * ### Reverse the order of collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::reverse() To reverse the order of array items.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25
     * ]);
     *
     * $reverse = $collection->reverse();
     *
     * // ['age' => 25, 'lastname' => 'Doe', 'firstname' => 'John']
     * ```
     *
     * @return self<TKey, TValue> New collection with reversed order.
     */
    public function reverse ():self {

        return new static(fn():array => Arr::reverse($this->storage, true));

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::search() To search the array for a given value and returns the first
     * corresponding key if successful.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25
     * ]);
     *
     * $collection->search('Doe');
     *
     * // lastname
     * ```
     */
    public function search (mixed $value):mixed {

        return Arr::search($value, $this->storage);

    }

    /**
     * ### Shuffle collection items
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Helpers\Array\shuffle() To shuffle array items.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25
     * ]);
     *
     * $shuffled = $collection->shuffle();
     *
     * // ['lastname' => 'Doe', 'age' => 25, 'firstname' => 'John']
     * ```
     *
     * @return $this<TKey, TValue> Shuffled collection.
     */
    public function shuffle ():self {

        $this->storage = shuffle($this->storage);

        return $this;

    }

    /**
     * ### Skip first n items from collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::slice() To get a slice from a collection.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $skipped = $collection->skip(2);
     *
     * // ['three', 'four', 'five']
     * ```
     *
     * @param positive-int $count <p>
     * Number of items to skip.
     * </p>
     *
     * @return self<TKey, TValue> New collection with skipped items.
     */
    public function skip (int $count):self {

        return $this->slice($count);

    }

    /**
     * ### Skip until the given callback returns true
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $skip = $collection->skipUntil(function ($value, $key) {
     *  return $key > 2;
     * });
     *
     * // ['four', 'five']
     * ```
     *
     * @param callable(TValue $value, TKey $key):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<TKey, TValue> New collection with skipped items.
     */
    public function skipUntil (callable $callback):self {

        $storage = [];

        foreach ($this->storage as $key => $value) {

            if (empty($storage) && !$callback($value, $key)) continue;

            $storage[$key] = $value;

        }

        return new static(fn():array => $storage);

    }

    /**
     * ### Skip while the given callback returns true and then returns the remaining items in the collection as a new collection
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $skip = $collection->skipWhile(function ($value, $key) {
     *  return $key <= 2;
     * });
     *
     * // ['four', 'five']
     * ```
     *
     * @param callable(TValue $value, TKey $key):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<TKey, TValue> New collection with skipped items.
     */
    public function skipWhile (callable $callback):self {

        $storage = [];

        foreach ($this->storage as $key => $value) {

            if (empty($storage) && $callback($value, $key)) continue;

            $storage[$key] = $value;

        }

        return new static(fn():array => $storage);

    }

    /**
     * ### Get slice from collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::slice() To extract a slice of the array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $collection->slice(2);
     *
     * // 'three', 'four', 'five'
     * ```
     * @example Slice with length.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $collection->slice(2, 2);
     *
     * // 'three', 'four'
     * ```
     * @example Slice with negative offset.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $collection->slice(-2, 1);
     *
     * // 'four'
     * ```
     * @example Slice with negative length.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $collection->slice(2, -2);
     *
     * // 'three'
     * ```
     *
     * @param int $offset <p>
     * If the offset is non-negative, the sequence will start at that offset in the array.
     * If the offset is negative, the sequence will start that far from the end of the array.
     * </p>
     * @param null|int $length [optional] <p>
     * If length is given and is positive, then the sequence will have that many elements in it.
     * If length is given and is negative, then the sequence will stop that many elements from the end of the array.
     * If it is omitted, then the sequence will have everything from offset up until the end of the array.
     * </p>
     *
     * @return self<TKey, TValue> New sliced collection.
     */
    public function slice (int $offset, ?int $length = null):self {

        return new static(fn():array => Arr::slice(
            $this->storage, $offset, $length, true)
        );

    }

    /**
     * ### Sort collection items
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\Order::ASC As parameter.
     * @uses \FireHub\Core\Support\Enums\Sort::SORT_REGULAR As parameter.
     * @uses \FireHub\Core\Support\LowLevel\Arr::sort() To sort an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'
     * ]);
     *
     * $collection->sort();
     *
     * // ['height' => '190cm', 'age' => 25, 'lastname' => 'Doe', 'firstname' => 'John', 'gender' => 'male']
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
     * $collection->sort(Order::DESC);
     *
     * // ['gender' => 'male', 'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm']
     * ```
     * @example Sorting order with a flag.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     * use FireHub\Core\Support\Enums\Order;
     * use FireHub\Core\Support\Enums\Sort;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'
     * ]);
     *
     * $collection->sort(Order::DESC, Sort::SORT_NATURAL_FLAG_CASE);
     *
     * // ['gender' => 'male', 'firstname' => 'John', 'lastname' => 'Doe', 'height' => '190cm', 'age' => 25]
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
    public function sort (Order $order = Order::ASC, Sort $flag = Sort::SORT_REGULAR):self {

        Arr::sort($this->storage, $order, true, $flag);

        return $this;

    }

    /**
     * ### Sort collection using a user-defined comparison function
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::sortBy() To sort an array by values using a user-defined comparison
     * function.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->indexed(fn():array => [5, 1, 4, 2, 3]);
     *
     * $collection->sortBy(function (mixed $current, mixed $next):int {
     *  if ($a === $b) return 0;
     *  return ($a < $b) ? -1 : 1;
     * });
     *
     * // [1, 2, 3, 4, 5]
     * ```
     *
     * @param callable(TValue $current, TValue $next):int<-1, 1> $callback <p>
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument
     * is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     *
     * @return $this<TKey, TValue> Sorted collection.
     */
    public function sortBy (callable $callback):self {

        Arr::sortBy($this->storage, $callback, true);

        return $this;

    }

    /**
     * ### Remove a portion of the array and replace it with something else
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::splice() To remove a portion of the array and replace it with something else.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $splice = $collection->splice(2);
     *
     * $collection->all();
     *
     * // 'one', 'two'
     *
     * $splice->all();
     *
     * // 'three', 'four', 'five'
     * ```
     * @example With length parameter.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $splice = $collection->splice(2, 1);
     *
     * $collection->all();
     *
     * // 'one', 'two', 'three', 'four'
     *
     * $splice->all();
     *
     * // 'five'
     * ```
     * @example With replacement parameter.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $splice = $collection->splice(2, 2, ['x', 'y', 'z']);
     *
     * $collection->all();
     *
     * // 'one', 'two', 'x', 'y', 'z', 'five'
     *
     * $splice->all();
     *
     * // 'three', 'four'
     * ```
     *
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
     * @param list<TValue> $replacement [optional] <p>
     * If a replacement array is specified, then the removed elements will be replaced with elements from this array.
     * If offset and length are such that nothing is removed, then the elements from the replacement array or array
     * are inserted in the place specified by the offset.
     * Keys in a replacement array are not preserved.
     * </p>
     *
     * @return self<TKey|int, TValue> New spliced collection.
     *
     * @note Numerical keys in an array are not preserved.
     */
    public function splice (int $offset, ?int $length = null, array $replacement = []):self {

        return new static(
            fn():array => Arr::splice($this->storage, $offset, $length, $replacement)
        );

    }

    /**
     * ### Split collection by number of groups into similar size
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\NumInt::floor() To round fractions down.
     * @uses \FireHub\Core\Support\LowLevel\NumInt::divide() To divide integers.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::count() To count elements of an object.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::slice() To extract a slice of the array.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Multidimensional As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
     *
     * $groups = $collection->split(3);
     *
     * // [[1, 2, 3, 4], [5, 6, 7], [8, 9, 10]]
     * ```
     *
     * @param positive-int $number_of_groups <p>
     * Number of groups.
     * </p>
     *
     * @throws ArithmeticError If the $dividend is PHP_INT_MIN and the $divisor is -1.
     * @throws DivisionByZeroError If $divisor is 0;
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Multidimensional<int, array<TKey, TValue>> Grouped collection.
     */
    public function split (int $number_of_groups):Multidimensional {

        $storage = [];

        $group_size = NumInt::floor(
            NumInt::divide($this->count(), $number_of_groups)
        );

        $remain = $this->count() % $number_of_groups;

        $start = 0;

        for ($counter = 0; $counter < $number_of_groups; $counter++) {

            $size = $group_size;

            if ($counter < $remain) $size++;

            if ($size) $storage[] = Arr::slice($this->storage, $start, $size);

            $start += $size;

        }

        return new Multidimensional(fn():array => $storage);

    }

    /**
     * ### Take first n items from collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::slice() To get a slice from a collection.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $take = $collection->take(2);
     *
     * // ['one', 'two']
     * ```
     *
     * @param int $count <p>
     * Number of items to take.
     * </p>
     *
     * @return self<TKey, TValue> New collection with first n items.
     */
    public function take (int $count):self {

        return $this->slice(0, $count);

    }

    /**
     * ### Take until the given callback returns true
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $take = $collection->takeUntil(function ($value, $key) {
     *  return $key > 2;
     * });
     *
     * // ['one', 'two', 'three']
     * ```
     *
     * @param callable(TValue $value, TKey $key):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<TKey, TValue> New collection with items.
     */
    public function takeUntil (callable $callback):self {

        $storage = [];

        foreach ($this->storage as $key => $value) {

            if ($callback($value, $key)) break;

            $storage[$key] = $value;

        }

        return new static(fn():array => $storage);

    }

    /**
     * ### Take while the given callback returns true and then returns the remaining items in the collection as a new collection
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $take = $collection->takeWhile(function ($value, $key) {
     *  return $key <= 2;
     * });
     *
     * // ['one', 'two', 'three']
     * ```
     *
     * @param callable(TValue $value, TKey $key):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<TKey, TValue> New collection with items.
     */
    public function takeWhile (callable $callback):self {

        $storage = [];

        foreach ($this->storage as $key => $value) {

            if (!$callback($value, $key)) break;

            $storage[$key] = $value;

        }

        return new static(fn():array => $storage);

    }

    /**
     * ### Applies the callback to each collection item
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::map() To apply the callback to the elements of the given array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $collection->transform(function ($value) {
     *  return 'new '.$value;
     * });
     *
     * // ['new one', 'new two', 'new three']
     * ```
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => ['firstname' => 'John', 'lastname' => 'Doe']);
     *
     * $collection->transform(function ($value, $key) {
     *  return $key.': '.$value;
     * });
     *
     * // ['firstname' => 'firstname: John', 'lastname' => 'lastname: Doe']
     * ```
     *
     * @param callable(TValue $value, TKey $key):mixed $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return $this<TKey, mixed> Modified collection.
     */
    public function transform (callable $callback):self {

        try {

            /**
             * Try with value only
             * @phpstan-ignore-next-line
             */
            $this->storage = Arr::map($this->storage, $callback);

        } catch (ArgumentCountError) {

            foreach ($this->storage as $key => $value) $this->storage[$key] = $callback($value, $key);

        }

        return $this;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws TypeError If illegal offset type.
     */
    public function offsetExists (mixed $offset):bool {

        return isset($this->storage[$offset]);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::offsetExists() To check if key exists.
     *
     * @throws TypeError If illegal offset type.
     *
     * @return null|TValue Offset value.
     */
    public function offsetGet (mixed $offset):mixed {

        return $this->offsetExists($offset) ? $this->storage[$offset] : null;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws TypeError If illegal offset type.
     */
    public function offsetUnset (mixed $offset):void {

        unset($this->storage[$offset]);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function getIterator ():Traversable {

        yield from $this->storage;

    }

    /**
     * ### Invoke storage
     * @since 1.0.0
     *
     * @param Closure():array<TKey, TValue> $callable <p>
     * Data from a callable source.
     * </p>
     *
     * @return array<TKey, TValue> Storage data.
     */
    protected function invoke (Closure $callable):array {

        return $callable();

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @return array<TKey, TValue> Data that can be serialized by json_encode().
     */
    public function jsonSerialize ():array {

        return $this->storage;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @return array<TKey, TValue> An associative array of key/value pairs that represent the serialized form of the
     * object.
     */
    public function __serialize ():array {

        return $this->storage;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @param array<TKey, TValue> $data <p>
     * Serialized data.
     * </p>
     */
    public function __unserialize (array $data):void {

        $this->storage = $data;

    }

}