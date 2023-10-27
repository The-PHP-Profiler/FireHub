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
use FireHub\Core\Support\LowLevel\DataIs;
use Closure, SplObjectStorage, Traversable, TypeError, UnexpectedValueException;

/**
 * ### Object collection type
 *
 * Object collection provides a map from objects to data or, by ignoring data, an object set.
 * This dual purpose can be useful in many cases involving the need to uniquely identify objects.
 * @since 1.0.0
 *
 * @template TKey of object
 * @template TValue
 *
 * @implements \FireHub\Core\Support\Collections\Collectable<int, TKey>
 * @implements \FireHub\Core\Support\Contracts\ArrayAccessible<TKey, TValue>
 */
final class Obj implements Master, Collectable, ArrayAccessible {

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
     * @var SplObjectStorage<TKey, TValue>
     */
    private SplObjectStorage $storage;

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Obj::invoke() To invoke storage.
     *
     * @param Closure(SplObjectStorage<TKey, TValue> $storage):void $callable $callable <p>
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
     * @return SplObjectStorage<TKey, TValue> Storage data.
     */
    public function storage ():SplObjectStorage {

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
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     * $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *  $storage[$cls1] = 'data for object 1';
     *  $storage[$cls2] = [1,2,3];
     * });
     *
     * $collection->count();
     *
     * // 2
     * ```
     */
    public function count ():int {

        return ($size = $this->storage->count()) < 0 ? 0 : $size;

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
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     * $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *  $storage[$cls1] = 'data for object 1';
     *  $storage[$cls2] = [1,2,3];
     * });
     *
     * $collection->all();
     *
     * // [[object(stdClass), 'data for object 1'], [object(stdClass), [1,2,3]]]
     * ```
     *
     * @return array<int, array{TKey, TValue}> Collection items as an array.
     */
    public function all ():array {

        $this->storage->rewind();

        $data = [];

        while ($this->storage->valid()) {

            $object = $this->storage->current();
            $info = $this->storage->getInfo();
            $this->storage->next();

            $data[] = [$object, $info];

        }

        return $data;

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
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     * $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *  $storage[$cls1] = 'data for object 1';
     *  $storage[$cls2] = [1,2,3];
     * });
     *
     * $collection->first();
     *
     * // new stdClass()
     * ```
     * @example With $callback parameter.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     * $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *  $storage[$cls1] = 'data for object 1';
     *  $storage[$cls2] = [1,2,3];
     * });
     *
     * $collection->first(function ($object, $info) use ($cls1) {
     *  return $object === $cls1;
     * });
     *
     * // new stdClass()
     * ```
     *
     * @param null|callable(TKey $object, TValue $info):TValue $callback [optional] <p>
     * If callback is used, the method will return the first item that passes truth test.
     * </p>
     */
    public function first (callable $callback = null):mixed {

        if ($callback) {

            foreach ($this->storage as $object) if ($callback($object, $this->storage[$object])) return $object;

            return null;

        }

        $this->storage->rewind();

        return $this->storage->current();

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Obj::count() To count elements in the iterator.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     * $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *  $storage[$cls1] = 'data for object 1';
     *  $storage[$cls2] = [1,2,3];
     * });
     *
     * $collection->last();
     *
     * // new stdClass()
     * ```
     * @example With $callback parameter.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     * $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *  $storage[$cls1] = 'data for object 1';
     *  $storage[$cls2] = [1,2,3];
     * });
     *
     * $collection->last(function ($object, $info) use ($cls1) {
     *  return $object === $cls1;
     * });
     *
     * // new stdClass()
     * ```
     *
     * @param null|callable(TKey $object, TValue $info):TValue $callback [optional] <p>
     * If callback is used, the method will return the last item that passes truth test.
     * </p>
     */
    public function last (callable $callback = null):mixed {

        if ($callback) {

            $found = null;

            foreach ($this->storage as $object) if ($callback($object, $this->storage[$object])) $found = $object;

            return $found;

        }

        $counter = 0;

        $count = $this->count();

        foreach ($this->storage as $value) if (++$counter === $count) return $value;

        return null;

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
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     * $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *  $storage[$cls1] = 'data for object 1';
     *  $storage[$cls2] = [1,2,3];
     * });
     *
     * $collection->each(function ($object, $info) {
     *  if (* condition *) {
     *      return false;
     *  }
     * });
     * ```
     *
     * @param callable(TKey $object, TValue $info):(false|void) $callback <p>
     * Function to call on each item in collection.
     * </p>
     */
    public function each (callable $callback):bool {

        foreach ($this->storage as $object) if ($callback($object, $this->storage[$object]) === false) return false;

        return true;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::callable() To verify that the contents of a variable can be called
     * as a function.
     * @uses \FireHub\Core\Support\Collections\Type\Obj::first() To get the first item from a collection.
     * @uses \FireHub\Core\Support\Collections\Type\Obj::search() To search the collection for a given value and returns
     * the first corresponding key if successful.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     *  $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *   $storage[$cls1] = 'data for object 1';
     *   $storage[$cls2] = [1,2,3];
     *  });
     *
     * $collection->contains($cls1);
     *
     * // true
     * ```
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     *  $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *   $storage[$cls1] = 'data for object 1';
     *   $storage[$cls2] = [1,2,3];
     *  });
     *
     * $collection->contains(function ($object, $info) {
     *  return $info === 'data for object 1';
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
     * @uses \FireHub\Core\Support\Collections\Type\Obj::contains() To determine whether a collection contains a given
     * item.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     *  $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *   $storage[$cls1] = 'data for object 1';
     *   $storage[$cls2] = [1,2,3];
     *  });
     *
     * $collection->doesntContain($cls1);
     *
     * // false
     * ```
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     * $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *  $storage[$cls1] = 'data for object 1';
     *  $storage[$cls2] = [1,2,3];
     * });
     *
     * $collection->doesntContain(function ($object, $info) {
     *  return $info === 'data for object 1';
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
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     * $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *  $storage[$cls1] = 'data for object 1';
     *  $storage[$cls2] = [1,2,3];
     * });
     *
     * $collection->every(function ($object, $info) {
     *  return is_int($info);
     * });
     *
     * // false
     * ```
     *
     * @param callable(TKey $object, TValue $info):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     */
    public function every (callable $callback):bool {

        foreach ($this->storage as $object) if (!$callback($object, $this->storage[$object])) return false;

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
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     * $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *  $storage[$cls1] = 'data for object 1';
     *  $storage[$cls2] = 'empty';
     * });
     *
     * $filter = $collection->filter(function ($object, $info) use ($cls1) {
     *  return $object === $cls1;
     * });
     *
     * // [[object(stdClass), 'new data for object 1']]
     * ```
     *
     * @param callable(TKey $object, TValue $info):bool $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return self<TKey, TValue> New filtered collection.
     */
    public function filter (callable $callback):self {

        /** @phpstan-ignore-next-line */
        return new self(function (SplObjectStorage $storage) use ($callback) {

            foreach ($this->storage as $object)
                !$callback($object, $this->storage[$object])
                    ?: $storage[$object] = $this->storage[$object];

        });

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Obj::count() To count collection data.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     * $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *  $storage[$cls1] = 'data for object 1';
     *  $storage[$cls2] = [1,2,3];
     * });
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
     * @uses \FireHub\Core\Support\Collections\Type\Obj::isEmpty() To check if a collection is empty.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     * $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *  $storage[$cls1] = 'data for object 1';
     *  $storage[$cls2] = [1,2,3];
     * });
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
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     * $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *  $storage[$cls1] = 'data for object 1';
     *  $storage[$cls2] = 'empty';
     * });
     *
     * $map = $collection->map(function ($info) {
     *  return 'new '.$info;
     * });
     *
     * // [[object(stdClass), 'new data for object 1'], [object(stdClass), 'new empty']]
     * ```
     *
     * @return self<TKey, TValue> New modified collection.
     */
    public function map (callable $callback):self {

        /** @phpstan-ignore-next-line */
        return new self(function (SplObjectStorage $storage) use ($callback):void {

            foreach ($this->storage as $object)
                $storage[$object] = $callback($this->storage[$object]); // @phpstan-ignore-line

        });

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collectable::storage() To get storage data.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *  $storage[$cls1] = 'data for object 1';
     *  $storage[$cls2] = 'empty';
     * });
     *
     * $collection2 = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *  $storage[$cls1] = 'data for object 2';
     *  $storage[$cls2] = 10;
     * });
     *
     * $merged = $collection->merge($collection2);
     *
     * // [[object(stdClass), 'new data for object 1'], [object(stdClass), 'empty'], [object(stdClass), 'new data for object 2'], [object(stdClass), 10]]
     * ```
     *
     * @return self<object, mixed> New modified collection.
     */
    public function merge (Collectable ...$collections):self {

        return new self(function (SplObjectStorage $storage) use ($collections):void {

            foreach ($this->storage as $object) $storage[$object] = $this->storage[$object];

            foreach ($collections as $collection)
                foreach ($collection as $object) $storage[$object] = $collection[$object]; // @phpstan-ignore-line

        });

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Obj::filter() To filter elements in an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     * $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *  $storage[$cls1] = 'data for object 1';
     *  $storage[$cls2] = 'empty';
     * });
     *
     * $reject = $collection->reject(function ($object, $info) use ($cls1) {
     *  return $object === $cls1;
     * });
     *
     * // [[object(stdClass), 'empty']]
     * ```
     *
     * @param callable(TKey $object, TValue $info):bool $callback <p>
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
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     * $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *  $storage[$cls1] = 'data for object 1';
     *  $storage[$cls2] = [1,2,3];
     * });
     *
     * $collection->search($cls1);
     *
     * // 0
     * ```
     */
    public function search (mixed $value):int|false {

        foreach ($this->storage as $object_key => $object)
            if ($value === $object) return $object_key;

        return false;

    }

    /**
     * ### Applies the callback to each collection item
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $cls1 = new stdClass();
     * $cls2 = new stdClass();
     *
     * $collection = Collection::object(function ($storage) use ($cls1, $cls2):void {
     *  $storage[$cls1] = 'data for object 1';
     *  $storage[$cls2] = 'empty';
     * });
     *
     *  $collection->transform(function ($info) {
     *  return 'new '.$info;
     * });
     *
     * // [[object(stdClass), 'new data for object 1'], [object(stdClass), 'new empty']]
     * ```
     *
     * @param callable(TValue $info):mixed $callback <p>
     * Function to call on each item in collection.
     * </p>
     *
     * @return $this<TKey, mixed> Modified collection.
     */
    public function transform (callable $callback):self {

        foreach ($this->storage as $object)
            $this->storage[$object] = $callback($this->storage[$object]);

        return $this;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws TypeError If $offset is not object.
     */
    public function offsetExists (mixed $offset):bool {

        return isset($this->storage[$offset]);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws TypeError If $offset is not object.
     * @throws UnexpectedValueException If key does not exist.
     *
     * @return TValue Offset value.
     */
    public function offsetGet (mixed $offset):mixed {

        return $this->storage[$offset];

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws TypeError If $offset is not object.
     *
     * @phpstan-ignore-next-line
     */
    public function offsetSet (mixed $offset, mixed $value):void {

        $this->storage[$offset] = $value;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws TypeError If $offset is not object.
     */
    public function offsetUnset (mixed $offset):void {

        unset($this->storage[$offset]);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @return Traversable<TKey> An instance of an object implementing Iterator or Traversable.
     */
    public function getIterator ():Traversable {

        yield from $this->storage;

    }

    /**
     * ### Invoke storage
     * @since 1.0.0
     *
     * @param Closure(SplObjectStorage<TKey, TValue> $storage):void $callable <p>
     * Data from a callable source.
     * </p>
     *
     * @return SplObjectStorage<TKey, TValue> Storage data.
     */
    private function invoke (Closure $callable):SplObjectStorage {

        $storage = new SplObjectStorage();

        /** @phpstan-ignore-next-line */
        $callable($storage);

        /** @phpstan-ignore-next-line */
        return $storage;

    }

}