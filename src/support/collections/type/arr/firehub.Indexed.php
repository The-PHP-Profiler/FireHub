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
    Arr, StrMB
};
use Closure, Error, TypeError, ValueError;

use function FireHub\Core\Support\Helpers\Array\ {
    duplicates, random
};

/**
 * ### Indexed array collection type
 *
 * Collections which have numerical indexes in an ordered sequential manner (starting from 0 and ending with n-1).
 * @since 1.0.0
 *
 * @template TValue
 *
 * @extends \FireHub\Core\Support\Collections\Type\Arr\aArr<int, TValue>
 *
 * @api
 */
final class Indexed extends aArr {

    /**
     * ### Allows chunking the collection
     * @since 1.0.0
     *
     * @use \FireHub\Core\Support\Collections\Traits\Chunkable<int, TValue>
     */
    use Chunkable;

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed::invoke() To invoke storage.
     *
     * @param Closure():list<TValue> $callable <p>
     * Data from a callable source.
     * </p>
     */
    public function __construct (
        protected Closure $callable
    ) {

        $this->storage = $this->invoke($this->callable);

    }

    /**
     * ### Removes an item at the beginning of the collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::shift() To remove an item at the beginning of an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $collection->shift();
     *
     * // ['two', 'three']
     * ```
     *
     * @return void
     */
    public function shift ():void {

        Arr::shift($this->storage);

    }

    /**
     * ### Push items at the beginning of the collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::unshift() To prepend elements to the beginning of the array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $collection->unshift('first', 'second');
     *
     * // ['first', 'second', 'one', 'two', 'three']
     * ```
     *
     * @param TValue ...$values [optional] <p>
     * List of values to unshift.
     * </p>
     *
     * @return void
     */
    public function unshift (mixed ...$values):void {

        Arr::unshift($this->storage, ...$values);

    }

    /**
     * ### Prepends items at the beginning of the collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed::unshift() To push items at the beginning of the
     * collection.
     *
     * @param TValue ...$values [optional] <p>
     * List of values to append.
     * </p>
     *
     * @return void
     *
     * @note This method is alias of unshift method.
     *
     * @see \FireHub\Core\Support\Collections\Type\Arr\Indexed::unshift() As alias to this function.
     */
    public function prepend (mixed ...$values):void {

        $this->unshift(...$values);

    }

    /**
     * ### Removes an item at the end of the collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::pop() To pop the element off the end of array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $collection->pop();
     *
     * // ['one', 'two']
     * ```
     *
     * @return void
     */
    public function pop ():void {

        Arr::pop($this->storage);

    }

    /**
     * ### Push items at the end of the collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::push() To push elements onto the end of array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $collection->push('first', 'second');
     *
     * // ['one', 'two', 'three', 'first', 'second']
     * ```
     *
     * @param TValue ...$values [optional] <p>
     * List of values to push.
     * </p>
     *
     * @return void
     */
    public function push (mixed ...$values):void {

        Arr::push($this->storage, ...$values);

    }

    /**
     * ### Append items at the end of the collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed::push() To push items at the end of the collection.
     *
     * @param TValue ...$values [optional] <p>
     * List of values to append.
     * </p>
     *
     * @return void
     *
     * @note This method is alias of push method.
     *
     * @see \FireHub\Core\Support\Collections\Type\Arr\Indexed::push() As alias to this function.
     */
    public function append (mixed ...$values):void {

        $this->push(...$values);

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
     * $keys = Collection::create()->indexed(fn():array => ['firstname', 'lastname', 'age']);
     * $values = Collection::create()->indexed(fn():array => ['John', 'Doe', 25]);
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
     * @uses \FireHub\Core\Support\LowLevel\Arr::difference() To compute the difference of arrays using values for
     * comparison.
     * @uses \FireHub\Core\Support\LowLevel\Arr::differenceFunc() To compute the difference of arrays using values for
     * comparison by using a callback function for data comparison.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\aArr::all() To get a collection as an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $collection2 = Collection::create(fn():array => ['two', 'three']);
     *
     * $diff = $collection1->difference($collection2);
     *
     * // ['one', 'four', 'five']
     * ```
     * @example With callback function.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $collection2 = Collection::create(fn():array => ['two', 'three']);
     *
     * $diff = $collection1->difference($collection1x, function ($value_a, $value_b):int {
     *  if ($value_a === $value_b && $value_a <> 'two') return 0;
     *  return ($value_a > $value_b) ? 1 : -1;
     * });
     *
     * // ['one', 'two', 'four', 'five']
     * ```
     *
     * @param \FireHub\Core\Support\Collections\Type\Arr\Indexed<mixed>|\FireHub\Core\Support\Collections\Type\Arr\Associative<array-key, mixed> $collection <p>
     * Collection to compare against.
     * </p>
     * @param null|callable(TValue $value_a, TValue $value_b):int<-1, 1> $callback [optional] <p>
     * The comparison function.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Indexed<TValue> Returns collection containing all items that
     * are not present another collection.
     */
    public function difference (Indexed|Associative $collection, callable $callback = null):self {

        return new self(fn():array => $callback
            ? Arr::differenceFunc($this->storage, $collection->all(), $callback)
            : Arr::difference($this->storage, $collection->all()));

    }

    /**
     * ### Removes unique items from collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Helpers\Array\duplicates() To remove unique values from an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->indexed(fn():array => [1, 1, 1, 1, 2, 3, 3, 3, 4, 4, 5]);
     *
     * $duplicates = $collection->duplicates();
     *
     * // [1, 1, 1, 3, 3, 4]
     * ```
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Indexed<TValue> New collection with duplicate items.
     */
    public function duplicates ():self {

        return new self(fn():array => duplicates($this->storage));

    }

    /**
     * ### Removes any values from the collection that are not present in the given collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::intersect() To compute the intersection of arrays using values for
     * comparison.
     * @uses \FireHub\Core\Support\LowLevel\Arr::intersectFunc() To compute the intersection of arrays using values for
     * comparison by using a callback function for data comparison.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed::all() To get a collection as an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $collection2 = Collection::create(fn():array => ['two', 'three']);
     *
     * $intersect = $collection1->intersect($collection2);
     *
     * // ['two', 'three']
     * ```
     * @example With callback function.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $collection2 = Collection::create(fn():array => ['two', 'three']);
     *
     * $intersect = $collection1->intersect($collection1x, function ($value_a, $value_b):int {
     *  if ($value_a === $value_b && $value_a <> 'two') return 0;
     *  return ($value_a > $value_b) ? 1 : -1;
     * });
     *
     * // ['three']
     * ```
     *
     * @param \FireHub\Core\Support\Collections\Type\Arr\Indexed<TValue>|\FireHub\Core\Support\Collections\Type\Arr\Associative<array-key, TValue> $collection <p>
     * Collection to compare against.
     * </p>
     * @param null|callable(TValue $a, TValue $b):int<-1, 1> $callback [optional] <p>
     * The comparison function.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Indexed<TValue> Returns collection containing all items that
     * are not present in the given collection.
     *
     */
    public function intersect (Indexed|Associative $collection, callable $callback = null):self {

        return new self(fn():array => $callback
            ? Arr::intersectFunc($callback, $this->storage, $collection->all())
            : Arr::intersect($this->storage, $collection->all()));

    }

    /**
     * ### The join method joins collection items with a string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed::count() To count elements of an object.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed::last() To get last item from collection.
     * @uses \FireHub\Core\Support\LowLevel\Arr::slice() To extract a slice of the array.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::implode() To join array elements with a string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $join = $collection->join(', ');
     *
     * // one, two, three, four, five
     * ```
     * @example With conjunction.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $join = $collection->join(', ', ' and ');
     *
     * // one, two, three, four and five
     * ```
     *
     * @param string $separator <p>
     * String to separate collection items.
     * </p>
     * @param string|false $conjunction [optional] <p>
     * Last item separator.
     * </p>
     *
     * @throws Error If collection item could not be converted to string.
     *
     * @return string Items with string separator.
     */
    public function join (string $separator, string|false $conjunction = false):string {

        if ($conjunction && $this->count() > 1) {

            $last = $this->last();

            $sliced = Arr::slice($this->storage, 0, -1);

            /** @phpstan-ignore-next-line */
            return StrMB::implode($sliced, $separator).$conjunction.$last;

        }

        /** @phpstan-ignore-next-line */
        return StrMB::implode($this->storage, $separator);

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
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $collection2 = Collection::create()->associative(fn():array => ['firstname' => 'John', 'lastname' => 'Doe']);
     *
     * $merged = $collection->merge($collection2);
     *
     * // ['one', 'two', 'three', 'first', 'second', 'John', 'Doe']
     * ```
     *
     * @return self<mixed> New merged collection.
     */
    public function merge (Collectable ...$collections):self {

        $storage = $this->storage;

        foreach ($collections as $collection)
            $storage = [...$storage, ...$collection->all()];

        return new self(fn():array => $storage);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @return self<TValue> New filtered collection.
     */
    public function nth (int $step, int $offset = 0):self {

        $storage = [];

        $counter = 0;

        foreach (
            $offset > 0
                ? $this->slice($offset)->all()
                : $this->storage as $value) {

            if ($counter % $step === 0) $storage[] = $value;

            $counter++;

        }

        return new self(fn():array => $storage);

    }

    /**
     * ### Pad collection to the specified length with a value
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::pad() To pad array to the specified length with a value.
     *
     * @template TPadValue
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $pad = $collection->pad(5, 'new value');
     *
     * // ['one', 'two', 'three', 'new value', 'new value']
     * ```
     * @example Pad with negative size.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $pad = $collection->pad(-5, 'new value');
     *
     * // ['new value', 'new value', 'one', 'two', 'three']
     * ```
     *
     * @param int $size <p>
     * New size of the collection.
     * If the length is positive, then the array is padded on the right if it's negative then on the left.
     * If the absolute value of length is less than or equal to the length of the array, then no padding takes place.
     * </p>
     * @param TPadValue $value <p>
     * Value to pad if input is less than pad_size.
     * </p>
     *
     * @return self<TValue|TPadValue> New collection with pad.
     *
     * @caution Keys can be re-numbered.
     */
    public function pad (int $size, mixed $value):self {

        return new self(fn():array => Arr::pad($this->storage, $size, $value));

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
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $random = $collection->random();
     *
     * // 'two' - randomly selected
     * ```
     * @example Using more than one random item.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $random = $collection->random(2);
     *
     * $random->all();
     *
     * // ['two', 'five'] - randomly selected
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

        $random = random($this->storage, $number);

        return $number > 1
            ? new self(fn():array => $random) // @phpstan-ignore-line
            : $random;

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
     * $collection = Collection::create()->associative(fn():array => [1, 2, 3, 4, 5]);
     *
     * $reverse = $collection->reverse();
     *
     * // [5, 4, 3, 2, 1]
     * ```
     *
     * @return self<TValue> New collection with reversed order.
     */
    public function reverse ():self {

        return new self(fn():array => Arr::reverse($this->storage));

    }

    /**
     * ### Shuffle collection items
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::shuffle() To shuffle array items.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [1, 2, 3, 4, 5]);
     *
     * $shuffled = $collection->shuffle();
     *
     * // [2, 3, 5, 1, 4]
     * ```
     *
     * @return $this<TValue> Shuffled collection.
     */
    public function shuffle ():self {

        Arr::shuffle($this->storage);

        return $this;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::slice() To extract a slice of the array.
     *
     * @return self<TValue> New sliced collection.
     */
    public function slice (int $offset, ?int $length = null):self {

        return new self(
            fn():array => Arr::slice($this->storage, $offset, $length)
        );

    }

    /**
     * @inheritDoc
     *
     * @uses \FireHub\Core\Support\Enums\Order::ASC As parameter.
     * @uses \FireHub\Core\Support\Enums\Sort::SORT_REGULAR As parameter.
     * @uses \FireHub\Core\Support\LowLevel\Arr::sort() To sort an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->indexed(fn():array => [5, 1, 4, 2, 3]);
     *
     * $collection->sort();
     *
     * // [1, 2, 3, 4, 5]
     * ```
     * @example Sorting order.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     * use FireHub\Core\Support\Enums\Order;
     *
     * $collection = Collection::create()->indexed(fn():array => [5, 1, 4, 2, 3]);
     *
     * $collection->sort(Order::DESC);
     *
     * // [5, 4, 3, 2, 1]
     * ```
     *
     * @return $this<TValue> Sorted collection.
     */
    public function sort (Order $order = Order::ASC, Sort $flag = Sort::SORT_REGULAR):self {

        Arr::sort($this->storage, $order, false, $flag);

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
     * @return $this<TValue> Sorted collection.
     */
    public function sortBy (callable $callback):self {

        Arr::sortBy($this->storage, $callback);

        return $this;

    }

    /**
     * ### Removes duplicate items from collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::unique() To remove duplicate values from an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->indexed(fn():array => [1, 1, 1, 1, 2, 3, 3, 3, 4, 4, 5]);
     *
     * $unique = $collection->unique();
     *
     * // [1, 2, 3, 4, 5]
     * ```
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Indexed<TValue> New collection with unique items.
     */
    public function unique ():self {

        return new self(fn():array => Arr::unique($this->storage));

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @param null|array-key $offset <p>
     * Offset to assign the value to.
     * </p>
     * @param TValue $value <p>
     * Value to set.
     * </p>
     *
     * @throws TypeError If illegal offset type.
     */
    public function offsetSet (mixed $offset, mixed $value):void {

        $this->storage[] = $value;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::values() To return all the values from array.
     *
     * @return list<TValue> Storage data.
     */
    protected function invoke (Closure $callable):array {

        return Arr::values($callable());

    }

}