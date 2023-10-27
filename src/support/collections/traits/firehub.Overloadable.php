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

use Error;

/**
 * ### Overloadable trait
 *
 * This trait allows usage of property overloading for collections.
 * @since 1.0.0
 *
 * @template TKey
 * @template TValue
 */
trait Overloadable {

    /**
     * ### Check if item exist in collection
     * @since 1.0.0
     *
     * @uses static::offsetExists() To check whether an offset exists-
     *
     * @param TKey $key <p>
     * Collection key.
     * </p>
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn ():array => [
     *  'firstname' => 'John',
     *  'lastname' => 'Doe',
     *  'age' => 25,
     *  'height' => '190cm',
     *  'gender' => 'male'
     * ]);
     *
     * $collection->exist('firstname');
     *
     * // true
     * ```
     */
    final public function exist (mixed $key):bool {

        return $this->offsetExists($key);

    }

    /**
     * ### Gets item from collection
     *
     * @since 1.0.0
     *
     * @uses static:offsetExists() To check whether an offset exists.
     * @uses static::offsetGet() To retrieve an offset.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn ():array => [
     *  'firstname' => 'John',
     *  'lastname' => 'Doe',
     *  'age' => 25,
     *  'height' => '190cm',
     *  'gender' => 'male'
     * ]);
     *
     * $collection->get('firstname');
     *
     * // John
     * ```
     *
     * @param TKey $key <p>
     * Collection key.
     * </p>
     *
     * @throws Error If key doesn't exist in a collection.
     *
     * @return TValue Item from a collection.
     */
    final public function get (mixed $key):mixed {

        /** @phpstan-ignore-next-line */
        return $this->offsetExists($key)
            ? $this->offsetGet($key)
            : throw new Error("Key $key doesn't exist in collection.");

    }

    /**
     * ### Pull item from collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Trait\Setable::get() To get item from a collection.
     *
     * @param TKey $key <p>
     * Collection key.
     * </p>
     *
     * @throws Error If key doesn't exist in a collection.
     *
     * @return TValue Item from a collection.
     *
     * @note This method is alias of get method.
     *
     * @see \FireHub\Core\Support\Collections\Trait\Setable::get() As alias to this function.
     */
    final public function pull (mixed $key):mixed {

        return $this->get($key);

    }

    /**
     * ### Adds item to collection
     * @since 1.0.0
     *
     * @uses static:offsetExists() To check whether an offset exists.
     * @uses static::offsetSet() To assign a value to the specified offset.
     *
     * @param TValue $value <p>
     * Collection value.
     * </p>
     * @param TKey $key <p>
     * Collection key.
     * </p>
     *
     * @throws Error If key already exists in a collection.
     *
     * @return void
     */
    public function add (mixed $value, mixed $key):void {

        !$this->offsetExists($key)
            ? $this->offsetSet($key, $value)
            : throw new Error("Key $key already exists in collection.");

    }

    /**
     * ### Replaces item from collection
     * @since 1.0.0
     *
     * @uses static::offsetExists() To check whether an offset exists.
     * @uses static::offsetSet() To assign a value to the specified offset.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn ():array => [
     *  'firstname' => 'John',
     *  'lastname' => 'Doe',
     *  'age' => 25,
     *  'height' => '190cm',
     *  'gender' => 'male'
     * ]);
     *
     * $collection->replace('age', 30);
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 30, 'height' => '190cm', 'gender' => 'male']
     * ```
     *
     * @param TValue $value <p>
     * Collection value.
     * </p>
     * @param TKey $key <p>
     * Collection key.
     * </p>
     *
     * @throws Error If key doesn't exist in a collection.
     */
    final public function replace (mixed $value, mixed $key):void {

        $this->offsetExists($key)
            ? $this->offsetSet($key, $value)
            : throw new Error("Key $key doesn't exist in collection.");

    }

    /**
     * ### Adds or replaces item to collection
     * @since 1.0.0
     *
     * @uses static::offsetSet() To assign a value to the specified offset.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn ():array => [
     *  'firstname' => 'John',
     *  'lastname' => 'Doe',
     *  'age' => 25,
     *  'height' => '190cm',
     *  'gender' => 'male'
     * ]);
     *
     * $collection->set('100kg', 'weight');
     * $collection->set('age', 30);
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 30, 'height' => '190cm', 'gender' => 'male', 'weight' => '100kg']
     * ```
     *
     * @param TValue $value <p>
     * Collection value.
     * </p>
     * @param TKey $key <p>
     * Collection key.
     * </p>
     *
     * @return void
     */
    public function set (mixed $value, mixed $key):void {

        $this->offsetSet($key, $value);

    }

    /**
     * ### Put item in to collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Trait\Setable::set() To set item from a collection.
     *
     * @param TValue $value <p>
     * Collection value.
     * </p>
     * @param TKey $key <p>
     * Collection key.
     * </p>
     *
     * @return void
     *
     * @note This method is alias of set method.
     *
     * @see \FireHub\Core\Support\Collections\Trait\Setable::set() As alias to this function.
     */
    final public function put (mixed $value, mixed $key):void {

        $this->set($value, $key);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses static::offsetExists() To check whether an offset exists.
     */
    final public function __isset (string $name):bool {

        return $this->offsetExists($name);

    }

    /**
     * ### Removes item from collection
     * @since 1.0.0
     *
     * @uses static::offsetUnset() To unset an offset.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->associative(fn ():array => [
     *  'firstname' => 'John',
     *  'lastname' => 'Doe',
     *  'age' => 25,
     *  'height' => '190cm',
     *  'gender' => 'male'
     * ]);
     *
     * $collection->remove('age');
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'height' => '190cm', 'gender' => 'male']
     * ```
     *
     * @param TKey $key <p>
     * Collection key.
     * </p>
     *
     * @return void
     */
    final public function remove (mixed $key):void {

        $this->offsetUnset($key);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses static::offsetGet() To retrieve an offset.
     *
     * @return null|TValue Collection value.
     */
    final public function __get (string $name):mixed {

        return $this->offsetGet($name);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses static::offsetSet() To assign a value to the specified offset.
     */
    final public function __set (string $name, mixed $value):void {

        $this->offsetSet($name, $value);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses static::offsetUnset() To unset an offset.
     */
    final public function __unset (string $name):void {

        $this->offsetUnset($name);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    abstract public function offsetExists (mixed $offset):bool;

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @return null|TValue Offset value.
     */
    abstract public function offsetGet (mixed $offset):mixed;

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
     */
    abstract public function offsetSet (mixed $offset, mixed $value):void;

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    abstract public function offsetUnset (mixed $offset):void;

}