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
use Generator, ValueError, SplFixedArray;

/**
 * ### Creates the collection containing a range of items
 * @since 1.0.0
 */
final class Range implements Master {

    /**
     * ### FireHub base class trait
     * @since 1.0.0
     */
    use Base;

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @param int|float|string $start <p>
     * First value of the sequence.
     * </p>
     * @param int|float|string $end <p>
     * The sequence is ended upon reaching the end value.
     * </p>
     * @param positive-int|float $step [optional] <p>
     * If a step value is given, it will be used as the increment between elements in the sequence.
     * Step should be given as a positive number. If not specified, step will default to 1.
     * </p>
     */
    public function __construct (
        private readonly int|float|string $start,
        private readonly int|float|string $end,
        private readonly int|float $step = 1
    ) {}

    /**
     * ### Range as an array collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::create() To create a new array collection type.
     * @uses \FireHub\Core\Support\LowLevel\Arr::range() To create an array containing a range of elements.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed As return.
     *
     * @throws ValueError If $step exceeds the specified range.
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Indexed<string|int|float> The array collection type.
     */
    public function asArray ():Indexed {

        return Collection::create(
            fn():array => Arr::range($this->start, $this->end, $this->step) ?: []
        );

    }

    /**
     * ### Range as a fixed collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::fixed() To create a new fixed collection type.
     * @uses \FireHub\Core\Support\LowLevel\Arr::range() To create an array containing a range of elements.
     * @uses \FireHub\Core\Support\LowLevel\Arr::count() To count all elements in the array.
     * @uses \FireHub\Core\Support\Collections\Type\Fix As return.
     *
     * @return \FireHub\Core\Support\Collections\Type\Fix<mixed> The fixed collection type.
     */
    public function asFixed ():Fix {

        $range = Arr::range($this->start, $this->end, $this->step) ?: [];

        return Collection::fixed(function (SplFixedArray $storage) use ($range):void {
            $counter = 0;
            foreach ($range as $value) $storage[$counter++] = $value;
        }, Arr::count($range));

    }

    /**
     * ### Range as a lazy collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::lazy() To create a new lazy collection type.
     * @uses \FireHub\Core\Support\LowLevel\Arr::range() To create an array containing a range of elements.
     * @uses \FireHub\Core\Support\Collections\Type\Gen As return.
     *
     * @return \FireHub\Core\Support\Collections\Type\Gen<int, string|int|float> The lazy collection type.
     */
    public function asLazy ():Gen {

        $range = Arr::range($this->start, $this->end, $this->step) ?: [];

        return Collection::lazy(function () use ($range):Generator {
            foreach ($range as $value) yield $value;
        });

    }

}