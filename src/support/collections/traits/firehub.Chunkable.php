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

namespace FireHub\Core\Support\Collections\Traits;

use FireHub\Core\Support\Collections\Type\Arr\Multidimensional;
use FireHub\Core\Support\LowLevel\ {
    Arr, Num
};
use Error, ValueError;

/**
 * ### Chunkable trait
 *
 * This trait allows chunking the collection.
 * @since 1.0.0
 *
 * @template TKey
 * @template TValue
 */
trait Chunkable {

    /**
     * ### Split collection into chunks
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::chunk() To split an array into chunks.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Multidimensional As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three', 'four', 'five']);
     *
     * $chunks = $collection->chunk(2);
     *
     * // [['one', 'two'], ['three', 'four'], ['five']]
     * ```
     *
     * @param positive-int $size <p>
     * The size of each chunk.
     * </p>
     * @param bool $preserve_keys [optional] <p>
     * When set to true, keys will be preserved.
     * </p>
     *
     * @throws ValueError If length is less than .
     *
     * @return ($preserve_keys is true
     *  ? \FireHub\Core\Support\Collections\Type\Arr\Multidimensional<int, array<TKey, TValue>>
     *  : \FireHub\Core\Support\Collections\Type\Arr\Multidimensional<int, non-empty-list<TValue>>
     * ) New multidimensional array collection with each dimension containing size elements.
     */
    public function chunk (int $size, bool $preserve_keys = false):Multidimensional {

        return new Multidimensional(
            fn():array => Arr::chunk($this->storage, $size, $preserve_keys)
        );

    }

    /**
     * ### Split collection into chunks until callback is true
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::chunk() To split an array into chunks.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Multidimensional As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create(fn():array => [
     *  'one', 'two', 'three', 'one', 'two', 'three' ,'one', 'two', 'three'
     * ]);
     *
     * $chunks = $collection->chunkUntil(function ($value, $key) {
     *  return $value === 'three';
     * });
     *
     * // [['one', 'two', 'three'], ['one', 'two', 'three'], ['one', 'two', 'three']]
     * ```
     *
     * @param callable(TValue $value, TKey $key):bool $callback <p>
     * Function to give truth test on each item in collection.
     * </p>
     * @param bool $preserve_keys [optional] <p>
     * When set to true, keys will be preserved.
     * </p>
     *
     * @return ($preserve_keys is true
     *  ? \FireHub\Core\Support\Collections\Type\Arr\Multidimensional<int, non-empty-array<TKey, TValue>>
     *  : \FireHub\Core\Support\Collections\Type\Arr\Multidimensional<int, non-empty-list<TValue>>
     * ) New multidimensional array collection with each dimension containing size elements.
     */
    public function chunkUntil (callable $callback, bool $preserve_keys = false):Multidimensional {

        return new Multidimensional (function () use ($callback, $preserve_keys):array {

            $storage = [];

            $counter = 0;

            foreach ($this->storage as $key => $value) {

                $preserve_keys
                    ? $storage[$counter][$key] = $value
                    : $storage[$counter][] = $value;

                if ($callback($value, $key)) $counter++;

            }

            return $storage;

        });

    }

    /**
     * ### Group collection by number of groups
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Num::ceil() To round fractions up.
     * @uses static::count() To count elements of an object.
     * @uses \FireHub\Core\Support\Collections\Traits\Chunkable::chunk() To split a collection into chunks.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Multidimensional As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn():array => [
     *  'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'
     * ]);
     *
     * $groups = $collection->group(2);
     *
     * // [['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25], ['height' => '190cm', 'gender' => 'male']]
     * ```
     *
     * @param positive-int $number_of_groups <p>
     * Number of groups.
     * </p>
     * @param bool $preserve_keys [optional] <p>
     * When set to true, keys will be preserved.
     * </p>
     *
     * @throws Error If the number of groups is not at least 1.
     *
     * @return ($preserve_keys is true
     *  ? \FireHub\Core\Support\Collections\Type\Arr\Multidimensional<int, array<TKey, TValue>>
     *  : \FireHub\Core\Support\Collections\Type\Arr\Multidimensional<int, non-empty-list<TValue>>
     * ) Grouped collection.
     */
    public function group (int $number_of_groups, bool $preserve_keys = false):Multidimensional {

        if ($number_of_groups < 1) {throw new Error('Number of groups need to be at least 1.');}

        return $this->chunk(
            ($size = Num::ceil($this->count() / $number_of_groups)) >= 1 ? $size : 1,
            $preserve_keys
        );

    }

}