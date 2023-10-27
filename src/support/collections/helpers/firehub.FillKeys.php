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
    Arr\Associative, Gen,
};
use FireHub\Core\Support\LowLevel\Arr;
use Error, Generator;

/**
 * ### Fill the collection with list of keys and value
 * @since 1.0.0
 *
 * @template TKey of array-key
 * @template TValue
 */
final class FillKeys implements Master {

    /**
     * ### FireHub base class trait
     * @since 1.0.0
     */
    use Base;

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @param list<TKey> $keys <p>
     * List of keys to fill the collection.
     * </p>
     * @param TValue $value <p>
     * Value to use for filling.
     * </p>
     */
    public function __construct (
        private readonly array $keys,
        private readonly mixed $value,
    ) {}

    /**
     * ### Fill as an array collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::create() To create a new array collection type.
     * @uses \FireHub\Core\Support\LowLevel\Arr::fillKeys() To fill an array with values, specifying keys.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed As return.
     *
     * @throws Error If key could not be converted to string.
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Associative<TKey, TValue> The array collection type.
     */
    public function asArray ():Associative {

        return Collection::create()->associative(
            fn():array => Arr::fillKeys($this->keys, $this->value) ?: []
        );

    }

    /**
     * ### Fill as a lazy collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::lazy() To create a new lazy collection type.
     * @uses \FireHub\Core\Support\Collections\Type\Gen As return.
     *
     * @return \FireHub\Core\Support\Collections\Type\Gen<TKey, TValue>The lazy collection type.
     */
    public function asLazy ():Gen {

        return Collection::lazy(function ():Generator {
            foreach ($this->keys as $key) yield $key => $this->value;
        });

    }

}