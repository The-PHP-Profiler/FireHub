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

namespace FireHub\Core\Support\Collections\Type;

use FireHub\Core\Base\ {
    Base, Master
};
use FireHub\Core\Support\Collections\Collectable;
use FireHub\Core\Support\Contracts\ArrayAccessible;
use FireHub\Core\Support\Collections\Traits\ {
    Conditionable, Convertable
};
use FireHub\Core\Support\LowLevel\ {
    Arr, DataIs
};
use Closure, RuntimeException, SplFixedArray, Traversable, TypeError;

/**
 * ### Fixed collection type
 *
 * Fixed collection allows only integers as keys, but it is faster
 * and uses less memory than an array collection.
 * This collection type must be resized manually and allows only
 * integers within the range as indexes.
 * @since 1.0.0
 *
 * @template TValue
 *
 * @implements \FireHub\Core\Support\Collections\Collectable<int, TValue>
 * @implements \FireHub\Core\Support\Contracts\ArrayAccessible<int, TValue>
 */
final class Fix implements Master, Collectable, ArrayAccessible {

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
     * ### Storage underlying data
     * @since 1.0.0
     *
     * @var SplFixedArray<TValue>
     */
    private SplFixedArray $storage;

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Fix::invoke() To invoke storage.
     *
     * @param Closure(SplFixedArray<TValue> $storage):void $callable $callable <p>
     * Data from a callable source.
     * </p>
     * @param int $size [optional] <p>
     * Size of a collection.
     * </p>
     */
    public function __construct (
        private Closure $callable,
        private readonly int $size = 0
    ) {

        $this->storage = $this->invoke($this->callable);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @return SplFixedArray<TValue> Storage data.
     */
    public function storage ():SplFixedArray {

        return $this->storage;

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
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     *
     * $collection->count();
     *
     * // 3
     * ```
     */
    public function count ():int {

        return ($size = $this->storage->getSize()) < 0 ? 0 : $size;

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
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     *
     * $collection->all();
     *
     * // ['one', 'two', 'three']
     * ```
     *
     * @return list<TValue> Collection items as an array.
     */
    public function all ():array {

        return $this->storage->jsonSerialize();

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
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     *
     * $collection->first();
     *
     * // 'one'
     * ```
     * @example With $callback parameter.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     *
     * $collection->first(function ($value) {
     *  return $value === 'two';
     * });
     *
     * // 'two'
     * ```
     *
     * @param null|callable(null|TValue $value):TValue $callback [optional] <p>
     * If callback is used, the method will return the first item that passes truth test.
     * </p>
     */
    public function first (callable $callback = null):mixed {

        if ($callback) {

            foreach ($this->storage as $value) if ($callback($value)) return $value;

            return null;

        }

        return $this->storage[0];

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Fix::count() To count elements in the iterator.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     *
     * $collection->last();
     *
     * // 'three'
     * ```
     * @example With $callback parameter.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     *
     * $collection->last(function ($value) {
     *  return $value === 'two';
     * });
     *
     * // 'two'
     * ```
     *
     * @param null|callable(null|TValue $value):TValue $callback [optional] <p>
     * If callback is used, the method will return the last item that passes truth test.
     * </p>
     */
    public function last (callable $callback = null):mixed {

        if ($callback) {

            $found = null;

            foreach ($this->storage as $value) if ($callback($value)) $found = $value;

            return $found;

        }

        return $this->storage[$this->count() - 1];

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
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     *
     * $collection->each(function ($value) {
     *  if (* condition *) {
     *      return false;
     *  }
     * });
     * ```
     *
     * @param callable(null|TValue $value):(false|void) $callback <p>
     * Function to call on each item in collection.
     * </p>
     */
    public function each (callable $callback):bool {

        foreach ($this->storage as $value) if ($callback($value) === false) return false;

        return true;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::callable() To verify that the contents of a variable can be called
     * as a function.
     * @uses \FireHub\Core\Support\Collections\Type\Fix::first() To get the first item from a collection.
     * @uses \FireHub\Core\Support\Collections\Type\Fix::search() To search the collection for a given value and returns
     * the first corresponding key if successful.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     *
     * $collection->contains('two');
     *
     * // true
     * ```
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 1;
     *  $storage[1] = 2;
     *  $storage[2] = 3;
     * }, 3);
     *
     * $collection->contains(function ($value) {
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
     * @uses \FireHub\Core\Support\Collections\Type\Fix::contains() To determine whether a collection contains a given
     * item.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     *
     * $collection->doesntContain('two');
     *
     * // false
     * ```
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 1;
     *  $storage[1] = 2;
     *  $storage[2] = 3;
     * }, 3);
     *
     * $collection->doesntContain(function ($value) {
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
     *  ``php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 1;
     *  $storage[1] = 2;
     *  $storage[2] = 3;
     * }, 3);
     *
     * $collection->every(function ($value) {
     *  return is_int($value);
     * });
     *
     * // true
     * ```
     *
     * @param callable(TValue|null $value):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     */
    public function every (callable $callback):bool {

        foreach ($this->storage as $value) if (!$callback($value)) return false;

        return true;

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
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 1;
     *  $storage[1] = 2;
     *  $storage[2] = 3;
     * }, 3);
     *
     * $filtered = $collection->filter(function ($value) {
     *  return $value > 2;
     * });
     *
     * // [2, 3]
     * ```
     *
     * @param callable(null|TValue $value):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<TValue> New filtered collection.
     */
    public function filter (callable $callback):self {

        /** @phpstan-ignore-next-line */
        return new self(function (SplFixedArray $storage) use ($callback) {

            $counter = 0;

            foreach ($this->storage as $value)
                !$callback($value) ?: $storage[$counter++] = $value;

            $storage->setSize($counter);

        }, $this->storage->getSize());

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Fix::count() To count collection data.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     *
     * $collection->isEmpty();
     *
     * // false
     * ```
     */
    public function isEmpty ():bool {

        return $this->count() === 0;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Fix::isEmpty() To check if a collection is empty.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     *
     * $collection->isNotEmpty();
     *
     * // true
     * ```
     */
    public function isNotEmpty ():bool {

        return !self::isEmpty();

    }

    /**
     * ### The join method joins collection items with a string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Fix::last() To get last item from collection.
     *
     * @param string $separator <p>
     * String to separate collection items.
     * </p>
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     *
     * $join = $collection->join(', ');
     *
     * // // one, two, three
     * ```
     *
     * @return string Items with string separator.
     */
    public function join (string $separator):string {

        $join = '';

        $last = $this->last();

        foreach ($this->storage as $value) {

            if ($value === $last) $join .= $value;

            else $join .= $value.$separator;

        }

        return $join;

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
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     *
     * $map = $collection->map(function ($value) {
     *  return 'new '.$value;
     * });
     *
     * // ['new one', 'new two', 'new three']
     * ```
     *
     * @param callable(TValue|null $value):mixed $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<mixed> New modified collection.
     */
    public function map (callable $callback):self {

        return new self(function (SplFixedArray $storage) use ($callback):void {

            foreach ($this->storage as $key => $value) $storage[$key] = $callback($value);

        }, $this->storage->getSize());

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collectable::count() To count elements of an object.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     *
     * $collection2 = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'new one';
     *  $storage[1] = 'new two';
     * }, 3);
     *
     * $merged = $collection->merge($collection2);
     *
     * // ['one', 'two', 'three', 'new one', 'new two']
     * ```
     *
     * @return self<mixed> New merged collection.
     */
    public function merge (Collectable ...$collections):self {

        $size = 0;

        foreach ($collections as $collection)
            $size += $collection->count();

        return new self(function (SplFixedArray $storage) use ($collections):void {

            $counter = 0;

            foreach ($this->storage as $value) $storage[$counter++] = $value;

            foreach ($collections as $collection)
                foreach ($collection as $value) $storage[$counter++] = $value;

        }, $this->storage->getSize() + $size);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Fix::filter() To filter elements in an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 1;
     *  $storage[1] = 2;
     *  $storage[2] = 3;
     * }, 3);
     *
     * $reject = $collection->reject(function ($value) {
     *  return $value > 2 || $value === 1;
     * });
     *
     * // [2]
     * ```
     *
     * @param callable(TValue|null $value):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<TValue> New rejected collection.
     */
    public function reject (callable $callback):self {

        return $this->filter(fn($value) => $value != $callback($value));

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
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     *
     * $collection->search('two');
     *
     * // 1
     * ```
     */
    public function search (mixed $value):int|false {

        foreach ($this->storage as $storage_key => $storage_value)
            if ($value === $storage_value) return $storage_key;

        return false;

    }

    /**
     * ### Skip first n items from collection
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     *
     * $skipped = $collection->skip(1);
     *
     * // ['two', 'three']
     * ```
     *
     * @param positive-int $count <p>
     * Number of items to skip.
     * </p>
     *
     * @return self<TValue> New collection with skipped items.
     */
    public function skip (int $count):self {

        /** @phpstan-ignore-next-line */
        return new self(function (SplFixedArray $storage) use ($count):void {

            $counter = 0;

            foreach ($this->storage as $key => $value)
                if ($key >= $count) $storage[$counter++] = $value;

        }, ($size = $this->storage->getSize() - $count) < 1 ? 0 : $size);

    }

    /**
     * ### Skip until the given callback returns true
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 1;
     *  $storage[1] = 2;
     *  $storage[2] = 3;
     * }, 3);
     *
     * $skip = $collection->skipUntil(function ($value) {
     *  return $value > 1;
     * });
     *
     * // [2, 3]
     * ```
     *
     * @param callable(null|TValue $value):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<TValue> New collection with skipped items.
     */
    public function skipUntil (callable $callback):self {

        /** @phpstan-ignore-next-line */
        return new self(function (SplFixedArray $storage) use ($callback):void {

            $counter = 0;

            foreach ($this->storage as $value) {

                if (!$callback($value)) continue;

                $storage[$counter++] = $value;

            }

            $storage->setSize($counter);

        }, $this->storage->getSize());

    }

    /**
     * ### Skip while the given callback returns true and then returns the remaining items in the collection as a new collection
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 1;
     *  $storage[1] = 2;
     *  $storage[2] = 3;
     * }, 3);
     *
     * $skip = $collection->skipWhile(function ($value) {
     *  return $value <= 2;
     * });
     *
     * // [2, 3]
     * ```
     *
     * @param callable(TValue|null $value):bool $callback $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<TValue> New collection with skipped items.
     */
    public function skipWhile (callable $callback):self {

        /** @phpstan-ignore-next-line */
        return new self(function (SplFixedArray $storage) use ($callback):void {

            $counter = 0;

            foreach ($this->storage as $value) {

                if ($callback($value)) continue;

                $storage[$counter++] = $value;

            }

            $storage->setSize($counter);

        }, $this->storage->getSize());

    }

    /**
     * ### Take first n items from collection
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     *
     * $take = $collection->take(1);
     *
     * // ['two', 'three']
     * ```
     *
     * @param int $count <p>
     * Number of items to take.
     * </p>
     *
     * @return self<TValue> New collection with skipped items.
     */
    public function take (int $count):self {

        /** @phpstan-ignore-next-line  */
        return new self(function (SplFixedArray $storage) use ($count):void {

            $counter = 0;

            foreach ($this->storage as $key => $value) if ($key < $count) $storage[$counter++] = $value;

        }, $count);

    }

    /**
     * ### Take until the given callback returns true
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 1;
     *  $storage[1] = 2;
     *  $storage[2] = 3;
     * }, 3);
     *
     * $take = $collection->takeUntil(function ($value) {
     *  return $value > 1;
     * });
     *
     * // [1]
     * ```
     *
     * @param callable(TValue|null $value):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<TValue> New collection with items.
     */
    public function takeUntil (callable $callback):self {

        /** @phpstan-ignore-next-line  */
        return new self(function (SplFixedArray $storage) use ($callback):void {

            $counter = 0;

            foreach ($this->storage as $value) {

                if ($callback($value)) break;

                $storage[$counter++] = $value;

            }

            $storage->setSize($counter);

        }, $this->storage->getSize());

    }

    /**
     * ### Take while the given callback returns true and then returns the remaining items in the collection as a new collection
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 1;
     *  $storage[1] = 2;
     *  $storage[2] = 3;
     * }, 3);
     *
     * $take = $collection->takeWhile(function ($value) {
     *  return $value <= 2;
     * });
     *
     * // [1, 2]
     * ```
     *
     * @param callable(TValue|null $value):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<TValue> New collection with items.
     */
    public function takeWhile (callable $callback):self {

        /** @phpstan-ignore-next-line  */
        return new self(function (SplFixedArray $storage) use ($callback):void {

            $counter = 0;

            foreach ($this->storage as $value) {

                if (!$callback($value)) break;

                $storage[$counter++] = $value;

            }

            $storage->setSize($counter);

        }, $this->storage->getSize());

    }

    /**
     * ### Applies the callback to each collection item
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     *
     * $collection->transform(function ($value) {
     *  return 'new '.$value;
     * });
     *
     * // ['new one', 'new two', 'new three']
     * ```
     *
     * @param callable(TValue|null $value):mixed $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return $this<mixed> Modified collection.
     */
    public function transform (callable $callback):self {

        foreach ($this->storage as $key => $value)
            $this->storage[$key] = $callback($value);

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
     * @uses \FireHub\Core\Support\Collections\Type\Fix::offsetExists() To check if key exists.
     *
     * @throws TypeError If illegal offset type.
     * @throws RuntimeException If $offset is out of range.
     *
     * @return null|TValue Offset value.
     */
    public function offsetGet (mixed $offset):mixed {

        return $this->storage[$offset];

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @param null|int $offset <p>
     * Offset to assign the value to.
     * </p>
     * @param TValue $value <p>
     * Value to set.
     * </p>
     *
     * @throws TypeError If illegal offset type.
     * @throws RuntimeException If $offset is out of range.
     */
    public function offsetSet (mixed $offset, mixed $value):void {

        $this->storage[$offset] = $value;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws TypeError If illegal offset type.
     * @throws RuntimeException If $offset is out of range.
     */
    public function offsetUnset (mixed $offset):void {

        unset($this->storage[$offset]);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @return Traversable<null|TValue> An instance of an object implementing Iterator.
     */
    public function getIterator ():Traversable {

        yield from $this->storage;

    }

    /**
     * ### Invoke storage
     * @since 1.0.0
     *
     * @param Closure(SplFixedArray<TValue> $storage):void $callable <p>
     * Data from a callable source.
     * </p>
     *
     * @return SplFixedArray<TValue> Storage data.
     */
    private function invoke (Closure $callable):SplFixedArray {

        $storage = new SplFixedArray($this->size);

        $callable($storage);

        return $storage;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Fix::all() To get a collection as an array.
     *
     * @return array<TValue> An associative array of key/value pairs that represent the serialized form of the
     * object.
     */
    public function __serialize ():array {

        return $this->all();

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::count() To count all elements in the array.
     * @uses \FireHub\Core\Support\Collections\Type\Fix::invoke() To invoke storage.
     *
     * @param array<array-key, TValue> $data <p>
     * Serialized data.
     * </p>
     */
    public function __unserialize (array $data):void {

        $this->callable = function ($storage) use ($data):void {
            foreach ($data as $key => $value) $storage[$key] = $value;
        };

        $this->size = Arr::count($data);

        $this->storage = $this->invoke($this->callable);

    }

}