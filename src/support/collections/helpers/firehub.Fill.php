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
    Arr\Indexed, Fix, Gen,
};
use FireHub\Core\Support\LowLevel\Arr;
use Generator, SplFixedArray;

/**
 * ### Fill the collection with values
 * @since 1.0.0
 *
 * @template TValue
 */
final class Fill implements Master {

    /**
     * ### FireHub base class trait
     * @since 1.0.0
     */
    use Base;

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @param TValue $value <p>
     * Value to use for filling.
     * </p>
     * @param positive-int $length <p>
     * Number of elements to insert.
     * </p>
     *
     * @return void
     */
    public function __construct (
        private readonly mixed $value,
        private readonly int $length
    ) {}

    /**
     * ### Fill as an array collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::create() To create a new array collection type.
     * @uses \FireHub\Core\Support\LowLevel\Arr::fill() To fill an array with values.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed As return.
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Indexed<TValue> Array a collection type.
     */
    public function asArray ():Indexed {

        return Collection::create(
            fn():array => Arr::fill($this->value, 0, $this->length)
        );

    }

    /**
     * ### Fill as a fixed collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::fixed() To create a new fixed collection type.
     * @uses \FireHub\Core\Support\Collections\Type\Fix As return.
     *
     * @return \FireHub\Core\Support\Collections\Type\Fix<TValue> Fixed collection type.
     */
    public function asFixed ():Fix {

        /** @phpstan-ignore-next-line */
        return Collection::fixed(function (SplFixedArray $storage):void {

            for ($counter = 0; $counter < $this->length; $counter++)
                $storage[$counter] = $this->value;

        }, $this->length);

    }

    /**
     * ### Fill as a lazy collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::lazy() To create a new lazy collection type.
     * @uses \FireHub\Core\Support\Collections\Type\Gen as return.
     *
     * @return \FireHub\Core\Support\Collections\Type\Gen<int, TValue> Lazy collection type.
     */
    public function asLazy ():Gen {

        return Collection::lazy(function ():Generator {

            for ($counter = 0; $counter < $this->length; $counter++)
                yield $this->value;

        });

    }

}