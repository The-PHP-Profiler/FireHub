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
use FireHub\Core\Support\Collections\Collectable;
use FireHub\Core\Support\Collections\Type\ {
    Arr\Indexed, Arr\Associative, Fix, Gen
};
use Generator;

/**
 * ### Convert collection to different one
 * @since 1.0.0
 *
 * @template TKey
 * @template TValue
 */
final class Convert implements Master {

    /**
     * ### FireHub base class trait
     * @since 1.0.0
     */
    use Base;

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collectable As parameter.
     *
     * @param \FireHub\Core\Support\Collections\Collectable<TKey, TValue> $collection <p>
     * Collection to convert.
     * </p>
     */
    public function __construct(
        private readonly Collectable $collection
    ) {}

    /**
     * ### Convert collection to an indexed array type
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::create() To create a new array collection type.
     * @uses \FireHub\Core\Support\Collections\Collectable::all() To get a collection as an array.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed As return.
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Indexed<mixed> Collection as indexed array type.
     */
    public function toArray ():Indexed {

        return Collection::create(fn():array => $this->collection->all());

    }

    /**
     * ### Convert collection to an associative array type
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::create() To create a new array collection type.
     * @uses \FireHub\Core\Support\Collections\Collectable::all() To get a collection as an array.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative As return.
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Associative<array-key, mixed> Collection as associative array
     * type.
     */
    public function toAssociativeArray ():Associative {

        return Collection::create()->associative(fn():array => $this->collection->all());

    }

    /**
     * ### Convert collection to a fixed type
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::fixed() To create a new fixed collection type.
     * @uses \FireHub\Core\Support\Collections\Collectable::all() To get a collection as an array.
     * @uses \FireHub\Core\Support\Collections\Collectable::count() To count elements in the iterator.
     * @uses \FireHub\Core\Support\Collections\Type\Fix As return.
     *
     * @return \FireHub\Core\Support\Collections\Type\Fix<mixed> Collection as fixed type.
     */
    public function toFixed ():Fix {

        /** @phpstan-ignore-next-line */
        return Collection::fixed(function ($storage):void {

            $counter = 0;

            foreach ($this->collection->all() as $value) $storage[$counter++] = $value;

        }, $this->collection->count());

    }

    /**
     * ### Convert collection to a lazy type
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::lazy() To create a new lazy collection type.
     * @uses \FireHub\Core\Support\Collections\Collectable::all() To get a collection as an array.
     * @uses \FireHub\Core\Support\Collections\Type\Gen As return.
     *
     * @return \FireHub\Core\Support\Collections\Type\Gen<array-key, mixed> Collection as lazy type.
     */
    public function toLazy ():Gen {

        return Collection::lazy(fn ():Generator => yield from $this->collection->all());

    }

}