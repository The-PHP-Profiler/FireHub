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
use FireHub\Core\Support\Collections\Type\Arr\ {
    Indexed, Associative, Multidimensional
};
use Closure;

/**
 * ### Array collection types
 * @since 1.0.0
 */
final class Arr implements Master {

    /**
     * ### FireHub base class trait
     * @since 1.0.0
     */
    use Base;

    /**
     * ### Indexed array collection type
     *
     * Collections which have numerical indexes in an ordered sequential manner (starting from 0 and ending with n-1).
     * @since 1.0.0
     *
     * @template TValue
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->indexed(fn ():array => ['one', 'two', 'three']);
     * ```
     *
     * @param Closure():array<TValue> $callable <p>
     * Data from a callable source.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Indexed<TValue> Indexed collection.
     */
    public function indexed (Closure $callable):Indexed {

        return new Indexed($callable);

    }

    /**
     * ### Associative array collection type
     *
     * Collections that use named keys that you assign to them.
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative As return.
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
     * ```
     *
     * @param Closure():array<TKey, TValue> $callable <p>
     * Data from a callable source.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Associative<TKey, TValue> Associative collection.
     */
    public function associative (Closure $callable):Associative {

        return new Associative($callable);

    }

    /**
     * ### Multidimensional array collection type
     *
     * Collections containing one or more sub-arrays.
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue of array<array-key, mixed>
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Multidimensional As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Collections\Collection;
     *
     * $collection = Collection::create()->multidimensional(fn ():array => [
     *  [1, 2, 3],
     *  [4, 5, 6],
     *  [7, 8, 9]
     * ]);
     * ```
     *
     * @param Closure():array<TKey, TValue> $callable <p>
     * Data from a callable source.
     * </p>
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Multidimensional<TKey, TValue> Associative collection.
     */
    public function multidimensional (Closure $callable):Multidimensional {

        return new Multidimensional($callable);

    }

}