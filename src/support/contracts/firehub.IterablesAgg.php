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

namespace FireHub\Core\Support\Contracts;

use IteratorAggregate, Traversable;

/**
 * ### Base iterator aggregate contract
 *
 * Interface contains all methods that every iterator type must have.
 * @since 1.0.0
 *
 * @template TKey
 * @template TValue
 *
 * @extends IteratorAggregate<TKey, TValue>
 */
interface IterablesAgg extends IteratorAggregate {

    /**
     * ### Retrieve an external iterator
     * @since 1.0.0
     *
     * @return Traversable<TKey, TValue> An instance of an object implementing Iterator or Traversable.
     */
    public function getIterator ():Traversable;

}