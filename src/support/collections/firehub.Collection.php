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

namespace FireHub\Core\Support\Collections;

use FireHub\Core\Base\ {
    Base, Master
};
use FireHub\Core\Support\Collections\Type\ {
    Arr, Fix, Gen, Obj
};
use FireHub\Core\Support\Collections\Type\Arr\Indexed;
use FireHub\Core\Support\Collections\Helpers\ {
    Emp, Fill, FillAssoc, FillKeys, Range
};
use Closure, Generator, SplFixedArray, SplObjectStorage;

/**
 * ### Data collection
 *
 * Collection is a wrapper for working with data structures.
 * @since 1.0.0
 *
 * @api
 */
final class Collection implements Master {

    /**
     * ### FireHub base class trait
     * @since 1.0.0
     */
    use Base;

    /**
     * ### Constructor
     *
     * Prevents instantiation of the main collection class.
     * @since 1.0.0
     */
    private function __construct () {}

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
     * @uses \FireHub\Core\Support\Collections\Type\Arr As return.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed As return.
     *
     * @example Create a new array collection, it will default to an indexed type.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn ():array => ['one', 'two', 'three']);
     * ```
     * @example Create a new array collection with an indexed type.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->indexed(fn ():array => ['one', 'two', 'three']);
     * ```
     *
     * @param Closure():array<TKey, TValue>|null $callable [optional] <p>
     * Data from a callable source.
     * </p>
     *
     * @return ($callable is null
     *  ? \FireHub\Core\Support\Collections\Type\Arr
     *  : \FireHub\Core\Support\Collections\Type\Arr\Indexed<TValue>) Array collection.
     */
    public static function create (?Closure $callable = null):Indexed|Arr {

        return $callable ? new Indexed($callable) : new Arr();

    }

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
     * @uses \FireHub\Core\Support\Collections\Type\Fix As return.
     *
     * @example Create a new fixed collection.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fixed(function ($storage):void {
     *  $storage[0] = 'one';
     *  $storage[1] = 'two';
     *  $storage[2] = 'three';
     * }, 3);
     * ```
     *
     * @param Closure(SplFixedArray<TValue> $storage):void $callable <p>
     * Data from a callable source.
     * </p>
     * @param int $size [optional] <p>
     * Size of a collection.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Fix<TValue> Fixed collection.
     */
    public static function fixed (Closure $callable, int $size = 0):Fix {

        return new Fix($callable, $size);

    }

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
     * @uses \FireHub\Core\Support\Collections\Type\Gen As return.
     *
     * @example Create a new generator collection.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::lazy(fn():Generator => yield from ['one', 'two', 'three']);
     * ```
     *
     * @param Closure():Generator<TKey, TValue> $callable <p>
     * Data from a callable source.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Gen<TKey, TValue> Lazy collection.
     */
    public static function lazy (Closure $callable):Gen {

        return new Gen($callable);

    }

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
     * @uses \FireHub\Core\Support\Collections\Type\Obj As return.
     *
     * @example Create a new object collection.
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
     * ```
     *
     * @param Closure(SplObjectStorage<TKey, TValue>):void $callable <p>
     * Data from a callable source.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Obj<TKey, TValue> Object collection.
     */
    public static function object (Closure $callable):Obj {

        return new Obj($callable);

    }

    /**
     * ### Creates empty collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Helpers\Emp As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::empty()->asArray();
     *
     * // []
     * ```
     *
     * @return \FireHub\Core\Support\Collections\Helpers\Emp Empty collection.
     */
    public static function empty ():Emp {

        return new Emp();

    }

    /**
     * ### Fill the collection with values
     * @since 1.0.0
     *
     * @template TValue
     *
     * @uses \FireHub\Core\Support\Collections\Helpers\Fill As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fill('ok', 10)->asArray();
     *
     * // ['ok', 'ok', 'ok', 'ok', 'ok', 'ok', 'ok', 'ok', 'ok', 'ok']
     * ```
     *
     * @param TValue $value <p>
     * Value to use for filling.
     * </p>
     * @param positive-int $length <p>
     * Number of elements to insert.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Helpers\Fill<TValue> Filled collection.
     */
    public static function fill (mixed $value, int $length):Fill {

        return new Fill($value, $length);

    }

    /**
     * ### Fill the collection with values
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @uses \FireHub\Core\Support\Collections\Helpers\FillKeys As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fillKeys(['one', 'two', 'three'], 10)->asArray();
     *
     * // ['one' => 10, 'two' => 10, 'three' => 10]
     * ```
     *
     * @param list<TKey> $keys <p>
     * List of keys to fill the collection.
     * </p>
     * @param TValue $value <p>
     * Value to use for filling.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Helpers\FillKeys<TKey, TValue> Filled with keys collection.
     */
    public static function fillKeys (array $keys, mixed $value):FillKeys {

        return new FillKeys($keys, $value);

    }

    /**
     * ### Fill the collection with values
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @uses \FireHub\Core\Support\Collections\Helpers\FillKeys As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::fillAssoc(['one', 'two', 'three'], [1, 2, 3])->asArray();
     *
     * // ['one' => 1, 'two' => 2, 'three' => 3]
     * ```
     *
     * @param list<TKey> $keys <p>
     * List of keys to fill the collection.
     * </p>
     * @param list<TValue> $values <p>
     * Values to use for filling.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Helpers\FillAssoc<TKey, TValue>Collection Filled with keys and values.
     */
    public static function fillAssoc (array $keys, mixed $values):FillAssoc {

        return new FillAssoc($keys, $values);

    }

    /**
     * ### Creates the collection containing a range of items
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Helpers\Range As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::range(10, 20, 2)->asArray();
     *
     * // [10, 12, 14, 16, 18, 20]
     * ```
     *
     * @param int|float|string $start <p>
     * First value of the sequence.
     * </p>
     * @param int|float|string $end <p>
     * The sequence is ended upon reaching the end value.
     * </p>
     * @param positive-int|float $step [optional] <p>
     * If a step value is given, it will be used as the increment between elements in the sequence.
     * Step should be given as a positive number.
     * If not specified, a step will default to 1.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Helpers\Range Filled with range collection.
     */
    public static function range (int|float|string $start, int|float|string $end, int|float $step = 1):Range {

        return new Range($start, $end, $step);

    }

}