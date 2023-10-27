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

use FireHub\Core\Support\LowLevel\DataIs;

/**
 * ### Conditionable trait
 *
 * This trait allows usage of conditionable methods for collection.
 * @since 1.0.0
 */
trait Conditionable {

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::null() To find whether a variable is null.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     * use FireHub\Core\Support\Collections\Type\Arr\Indexed;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $collection->when(true, function (Indexed $collection) {
     *  $collection->push('yes');
     * });
     *
     * // ['one', 'two', 'three', 'yes']
     * ```
     * @example With condition doesn't meet parameter.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     * use FireHub\Core\Support\Collections\Type\Arr\Indexed;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $collection->when(false, function (Indexed $collection) {
     *  $collection->push('yes');
     * }, function (Indexed $collection) {
     *  $collection->push('no');
     * });
     *
     * // ['one', 'two', 'three', 'no']
     * ```
     */
    public function when (bool $condition, callable $condition_meet, ?callable $condition_not_meet = null):static {

        $condition
            ? $condition_meet($this)
            : (
                DataIs::null($condition_not_meet)
                ?: $condition_not_meet($this)
            );

        return $this;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses static::when() To execute the given callback when the first argument given to the method evaluates to true.
     * @uses static::isEmpty To check if a collection is empty.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     * use FireHub\Core\Support\Collections\Type\Arr\Indexed;
     *
     * $collection = Collection::create(fn():array => []);
     *
     * $collection->whenEmpty(function (Indexed $collection) {
     *  $collection->push('yes');
     * });
     *
     * // ['yes']
     * ```
     */
    public function whenEmpty (callable $callback):static {

        /** @phpstan-ignore-next-line */
        return $this->when($this->isEmpty(), $callback);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses static::when() To execute the given callback when the first argument given to the method evaluates to true.
     * @uses static::isNotEmpty To check if a collection is not empty.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     * use FireHub\Core\Support\Collections\Type\Arr\Indexed;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $collection->whenNotEmpty(function (Indexed $collection) {
     *  $collection->push('yes');
     * });
     *
     * // ['one', 'two', 'three', 'yes']
     * ```
     */
    public function whenNotEmpty (callable $callback):static {

        /** @phpstan-ignore-next-line */
        return $this->when($this->isNotEmpty(), $callback);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::null() To find whether a variable is null.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     * use FireHub\Core\Support\Collections\Type\Arr\Indexed;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $collection->unless(false, function (Indexed $collection) {
     *  $collection->push('yes');
     * });
     *
     * // ['one', 'two', 'three', 'yes']
     * ```
     * @example With condition doesn't meet parameter.
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     * use FireHub\Core\Support\Collections\Type\Arr\Indexed;
     *
     * $collection = Collection::create(fn():array => ['one', 'two', 'three']);
     *
     * $collection->unless(true, function (Indexed $collection) {
     *  $collection->push('yes');
     * }, function (Indexed $collection) {
     *  $collection->push('no');
     * });
     *
     * // ['one', 'two', 'three', 'no']
     * ```
     */
    public function unless (bool $condition, callable $condition_meet, ?callable $condition_not_meet = null):static {

        !$condition
            ? $condition_meet($this)
            : (
                DataIs::null($condition_not_meet)
                ?: $condition_not_meet($this
                )
            );

        return $this;

    }

}