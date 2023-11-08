<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2023 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @package FireHub\Support\Collection
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\Collections\Helpers;

use FireHub\Core\Base\ {
    Base, Master
};
use FireHub\Core\Support\Collections\Collection;
use FireHub\Core\Support\Collections\Type\ {
    Arr\Indexed, Arr\Associative, Arr\Multidimensional, Fix, Obj
};
use SplFixedArray, SplObjectStorage;

/**
 * ### Creates an empty collection
 * @since 1.0.0
 */
final class Emp implements Master {

    /**
     * ### FireHub base class trait
     * @since 1.0.0
     */
    use Base;

    /**
     * ### Empty array indexed collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::create() To create a new array collection type.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed As return.
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Indexed<mixed> Empty indexed array collection type.
     */
    public function asArray ():Indexed {

        return Collection::create(fn():array => []);

    }

    /**
     * ### Empty array associative collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::create() To create a new array collection type.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative As return.
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Associative<array-key, mixed> Empty associative array
     * collection type.
     */
    public function asAssociativeArray ():Associative {

        return Collection::create()->associative(fn():array => []);

    }

    /**
     * ### Empty array multidimensional collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::create() To create a new array collection type.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Multidimensional As return.
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Multidimensional<array-key, array<array-key, mixed>> Empty
     * multidimensional array collection type.
     */
    public function asMultidimensionalArray ():Multidimensional {

        return Collection::create()->multidimensional(fn():array => []);

    }

    /**
     * ### Empty fixed collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::fixed() To create a new fixed collection type.
     * @uses \FireHub\Core\Support\Collections\Type\Fix As return.
     *
     * @return \FireHub\Core\Support\Collections\Type\Fix<mixed> Empty fixed collection type.
     */
    public function asFixed ():Fix {

        return Collection::fixed(function (SplFixedArray $storage):void {});

    }

    /**
     * ### Empty object collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::object() To create a new lazy collection type.
     * @uses \FireHub\Core\Support\Collections\Type\Obj as return.
     *
     * @return \FireHub\Core\Support\Collections\Type\Obj<object, mixed> Empty object collection type.
     */
    public function asObject ():Obj {

        return Collection::object(function (SplObjectStorage $storage):void {});

    }

}