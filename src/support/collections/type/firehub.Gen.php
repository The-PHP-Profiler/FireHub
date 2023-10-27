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
use FireHub\Core\Support\Collections\Traits\ {
    Conditionable, Convertable
};
use FireHub\Core\Support\LowLevel\ {
    DataIs, Iterator
};
use Closure, Generator, Traversable;

/**
 * ### Lazy collection type
 *
 * Lazy collection allows you to work with huge datasets
 * while keeping memory usage low.
 * @since 1.0.0
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @implements \FireHub\Core\Support\Collections\Collectable<TKey, TValue>
 */
final class Gen implements Master, Collectable {

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
     * @var Generator<TKey, TValue>
     */
    private Generator $storage;

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Gen::invoke() To get data.
     *
     * @param Closure():Generator<TKey, TValue> $callable <p>
     * Data from a callable source.
     * </p>
     */
    public function __construct (
        private Closure $callable
    ) {

        $this->storage = $this->invoke($this->callable);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Gen::invoke() To invoke storage.
     *
     * @return Generator<TKey, TValue> Storage data.
     */
    public function storage ():Generator {

        $this->storage = $this->invoke($this->callable);

        return $this->storage;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Iterator::count() To count all elements in the iterator.
     * @uses \FireHub\Core\Support\Collections\Type\Gen::storage() To get data.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from ['one', 'two', 'three']);
     *
     * $collection->count();
     *
     * // 3
     * ```
     */
    public function count ():int {

        return Iterator::count($this->storage());

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Gen::storage() To get data.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from ['one', 'two', 'three']);
     *
     * $collection->all();
     *
     * // ['one', 'two', 'three']
     * ```
     *
     * @return array<TKey, TValue> Collection items as an array.
     */
    public function all ():array {

        return Iterator::toArray($this->storage());

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Gen::storage() To get storage data.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from ['one', 'two', 'three']);
     *
     * $collection->first();
     *
     * // 'one'
     * ```
     * @example With $callback parameter.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from ['one', 'two', 'three']);
     *
     * $collection->first(function ($value, $key) {
     *  return $value === 'two';
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

            foreach ($this->storage() as $key => $value) if ($callback($value, $key)) return $value;

            return null;

        }

        return $this->storage()->current();

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Gen::storage() To get storage data.
     * @uses \FireHub\Core\Support\Collections\Type\Gen::count() To count elements in the iterator.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from ['one', 'two', 'three']);
     *
     * $collection->last();
     *
     * // 'three'
     * ```
     * @example With $callback parameter.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from ['one', 'two', 'three']);
     *
     * $collection->last(function ($value, $key) {
     *  return $value === 'two';
     * });
     *
     * // 'two'
     * ```
     *
     * @param null|callable(TValue $value, TKey $key):TValue $callback [optional] <p>
     * If callback is used, the method will return the last item that passes truth test.
     * </p>
     */
    public function last (callable $callback = null):mixed {

        if ($callback) {

            $found = null;

            foreach ($this->storage() as $key => $value) if ($callback($value, $key)) $found = $value;

            return $found;

        }

        $counter = 0;

        $count = $this->count();

        foreach ($this->storage() as $value) if (++$counter === $count) return $value;

        return null;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     * @uses \FireHub\Core\Support\Collections\Type\Gen::storage() To get data.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from ['one', 'two', 'three']);
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

        foreach ($this->storage() as $key => $value) if ($callback($value, $key) === false) return false;

        return true;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::callable() To verify that the contents of a variable can be called
     * as a function.
     * @uses \FireHub\Core\Support\Collections\Type\Gen::first() To get the first item from a collection.
     * @uses \FireHub\Core\Support\Collections\Type\Gen::search() To search the collection for a given value and returns
     * the first corresponding key if successful.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from ['one', 'two', 'three']);
     *
     * $collection->contains('two');
     *
     * // true
     * ```
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from [1, 2, 3, 4, 5]);
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
     * @uses \FireHub\Core\Support\Collections\Type\Gen::contains() To determine whether a collection contains a
     * given item.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from ['one', 'two', 'three']);
     *
     * $collection->doesntContain('two');
     *
     * // false
     * ```
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from [1, 2, 3, 4, 5]);
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
     *  ``php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from [1, 2, 3, 4, 5]);
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
     * @uses \FireHub\Core\Support\Collections\Type\Gen::storage() To get data.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from [
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
     * @param callable(TValue $value, TKey $key):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<TKey, TValue> New filtered collection.
     */
    public function filter (callable $callback):self {

        return new self(function () use ($callback):Generator {

            foreach ($this->storage() as $key => $value)
                !$callback($value, $key) ?: yield $key => $value;

            return null;

        });

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Gen::count() To count collection data.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from ['one', 'two', 'three']);
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
     * @uses \FireHub\Core\Support\Collections\Type\Gen::isEmpty() To check if a collection is empty.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from ['one', 'two', 'three']);
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
     * @uses \FireHub\Core\Support\Collections\Type\Fix::last() To count elements in the iterator.
     * @uses \FireHub\Core\Support\Collections\Type\Gen::storage() To get data.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from ['one', 'two', 'three']);
     *
     * $join = $collection->join(', ');
     *
     * // one, two, three
     * ```
     * @example With symbol.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25
     * ]);
     *
     * $join = $collection->join(', ', ' = ');
     *
     * // firstname = John, lastname = Doe, age = 25
     * ```
     *
     * @param string $separator <p>
     * String to separate collection items.
     * </p>
     * @param string|false $symbol [optional] <p>
     * Symbol between a key and value.
     * </p>
     *
     * @return string Items with string separator.
     */
    public function join (string $separator, string|false $symbol = false):string {

        $join = '';

        foreach ($this->storage() as $key => $value) {

            $item = $symbol ? $key.$symbol.$value : $value;

            $join .= $value === $this->last() ? $item : $item.$separator;

        }

        return $join;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Gen::storage() To get data.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from ['one', 'two', 'three']);
     *
     * $map = $collection->map(function ($value, $key) {
     *  return 'new '.$value;
     * });
     *
     * // ['new one', 'new two', 'new three']
     * ```
     *
     * @param callable(TValue $value, TKey $key):mixed $callback $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<TKey, TValue> New modified collection.
     */
    public function map (callable $callback):self {

        return new self(function () use ($callback):Generator {

            foreach ($this->storage() as $key => $value) yield $key => $callback($value, $key);

        });

    }

    /**
     * ### Applies the callback to each collection key
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Gen::storage() To get data.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $map = Collection::lazy(fn():Generator => yield from [
     *  'one' => 1, 'two' => 2, 'three' => 3
     * ]);
     *
     * // ['new one', 'new two', 'new three']
     * ```
     *
     * @param callable(TValue $value, TKey $key):array-key $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<array-key, TValue> New modified collection.
     */
    public function mapKeys (callable $callback):self {

        return new self(function () use ($callback):Generator {

            foreach ($this->storage() as $key => $value)
                yield $callback($value, $key) => $value;

        });

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collectable::storage() To get storage data.
     * @uses \FireHub\Core\Support\Collections\Collectable::all() To get a collection as an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from ['one', 'two', 'three']);
     *
     * $collection2 = Collection::lazy(fn():Generator => yield from ['four', 'five']);
     *
     * $merged = $collection->merge($collection2);
     *
     * // ['one', 'two', 'three', 'four', 'five']
     * ```
     *
     * @return self<array-key, mixed> New merged collection.
     */
    public function merge (Collectable ...$collections):self {

        return new self(function () use ($collections):Generator {

            yield from $this->storage();

            foreach ($collections as $collection) yield from $collection->all();

        });

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Gen::filter() To filter elements in an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from [
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
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25
     * ]);
     *
     * $collection->search('John');
     *
     * // 'firstname'
     * ```
     */
    public function search (mixed $value):mixed {

        foreach ($this->storage as $storage_key => $storage_value)
            if ($value === $storage_value) return $storage_key;

        return false;

    }

    /**
     * ### Skip first n items from collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type::storage() To get storage data.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from [
     *  'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5
     * ]);
     *
     * $skipped = $collection->skip(2);
     *
     * // ['three' => 3, 'four' => 4, 'five' => 5]
     * ```
     *
     * @param int $count <p>
     * Number of items to skip.
     * </p>
     *
     * @return self<TKey, TValue> New collection with skipped items.
     */
    public function skip (int $count):self {

        return new self(function () use ($count):Generator {

            $counter = 0;

            foreach ($this->storage() as $key => $value) {

                if ($counter++ < $count) continue;

                yield $key => $value;

            }

        });

    }

    /**
     * ### Skip until the given callback returns true
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type::storage() To get storage data.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from [1, 2, 3, 4, 5]);
     *
     * $skip = $collection->skipUntil(function ($value, $key) {
     *  return $value > 1;
     * });
     *
     * // [2, 3, 4, 5]
     * ```
     *
     * @param callable(TValue $value, TKey $key):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<TKey, TValue> New collection with skipped items.
     */
    public function skipUntil (callable $callback):self {

        return new self(function () use ($callback):Generator {

            $storage = $this->storage();

            while ($storage->valid() && !$callback($storage->current(), $storage->key())) $storage->next();

            while ($storage->valid()) {

                yield $storage->key() => $storage->current();

                $storage->next();

            }

        });

    }

    /**
     * ### Skip while the given callback returns true and then returns the remaining items in the collection as a new collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type::storage() To get storage data.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from [1, 2, 3, 4, 5]);
     *
     * $skip = $collection->skipWhile(function ($value, $key) {
     *  return $value <= 1;
     * });
     *
     * // [2, 3, 4, 5]
     * ```
     *
     * @param callable(TValue $value, TKey $key):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<TKey, TValue> New collection with skipped items.
     */
    public function skipWhile (callable $callback):self {

        return new self(function () use ($callback):Generator {

            $storage = $this->storage();

            while ($storage->valid() && $callback($storage->current(), $storage->key())) $storage->next();

            while ($storage->valid()) {

                yield $storage->key() => $storage->current();

                $storage->next();

            }

        });

    }

    /**
     * ### Take first n items from collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type::storage() To get storage data.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from [
     *  'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5
     * ]);
     *
     * $take = $collection->take(2);
     *
     * // ['one' => 1, 'two' => 2]
     * ```
     *
     * @param int $count <p>
     * Number of items to take.
     * </p>
     *
     * @return self<TKey, TValue> New collection with items.
     */
    public function take (int $count):self {

        return new self(function () use ($count):Generator {

            $counter = 0;

            foreach ($this->storage() as $key => $value) {

                if ($counter++ >= $count) continue;

                yield $key => $value;

            }

        });

    }

    /**
     * ### Take until the given callback returns true
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type::storage() To get storage data.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from [1, 2, 3, 4, 5]);
     *
     * $take = $collection->takeUntil(function ($value, $key) {
     *  return $value > 1;
     * });
     *
     * // [1]
     * ```
     *
     * @param callable(TValue $value, TKey $key):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<TKey, TValue> New collection with items.
     */
    public function takeUntil (callable $callback):self {

        return new self(function () use ($callback):Generator {

            $storage = $this->storage();

            while ($storage->valid() && !$callback($storage->current(), $storage->key())) {

                yield $storage->key() => $storage->current();

                $storage->next();

            }

        });

    }

    /**
     * ### Tale while the given callback returns true and then returns the remaining items in the collection as a new collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type::storage() To get storage data.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from [1, 2, 3, 4, 5]);
     *
     * $take = $collection->takeWhile(function ($value, $key) {
     *  return $value <= 1;
     * });
     *
     * // [1]
     * ```
     *
     * @param callable(TValue $value, TKey $key):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<TKey, TValue> New collection with items.
     */
    public function takeWhile (callable $callback):self {

        return new self(function () use ($callback):Generator {

            $storage = $this->storage();

            while ($storage->valid() && $callback($storage->current(), $storage->key())) {

                yield $storage->key() => $storage->current();

                $storage->next();

            }

        });

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Gen::storage() To get data.
     */
    public function getIterator ():Traversable {

        return $this->storage();

    }

    /**
     * ### Invoke storage
     * @since 1.0.0
     *
     * @param Closure():Generator<TKey, TValue> $callable $callable <p>
     * Data from a callable source.
     * </p>
     *
     * @return Generator<TKey, TValue> Storage data.
     */
    private function invoke (Closure $callable):Generator {

        yield from $callable();

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Gen::all() To get a collection as an array.
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
     * @uses \FireHub\Core\Support\Collections\Type\Gen::invoke() To invoke storage.
     *
     * @param array<TKey, TValue> $data <p>
     * Serialized data.
     * </p>
     */
    public function __unserialize (array $data):void {

        $this->callable = fn():Generator => yield from $data;

        $this->storage = $this->invoke($this->callable);

    }

}