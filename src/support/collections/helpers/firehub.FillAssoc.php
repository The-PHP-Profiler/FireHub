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
use FireHub\Core\Support\Collections\Type\Arr\Associative;
use ValueError;

/**
 * ### Fill the collection with list of keys and values
 * @since 1.0.0
 *
 * @template TKey of array-key
 * @template TValue
 */
final class FillAssoc implements Master {

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
     * @param list<TValue> $values $values <p>
     * Values to use for filling.
     * </p>
     *
     * @return void
     */
    public function __construct (
        private readonly array $keys,
        private readonly array $values,
    ) {}

    /**
     * ### Fill as an array collection
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::create() To create a new array collection type.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed::combine() To combine the values of the collection,
     * as keys, with the values of another collection.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed As return.
     *
     * @throws ValueError If arguments $keys and $values don't have the same number of elements.
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Associative<TKey, TValue> The array collection type.
     */
    public function asArray ():Associative {

        $values = Collection::create(fn():array => $this->values);

        return Collection::create(fn():array => $this->keys)->combine($values);

    }

}