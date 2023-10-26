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

use Iterator;

/**
 * ### Base iterator contract
 *
 * Interface contains all methods that every iterator type must have.
 * @since 1.0.0
 *
 * @template TKey
 * @template TValue
 *
 * @extends Iterator<TKey, TValue>
 */
interface Iterables extends Iterator {

    /**
     * ### Checks if the current position is valid
     *
     * This method is called after rewind() and next() to check if the current position is valid.
     * @since 1.0.0
     *
     * @return bool True on success or false on failure.
     */
    public function valid ():bool;

    /**
     * ### Return the current element
     * @since 1.0.0
     *
     * @return TValue Current element.
     */
    public function current ():mixed;

    /**
     * ### Return the key of the current element
     * @since 1.0.0
     *
     * @error\exeption E_NOTICE on failure.
     *
     * @return null|TKey Key of the current element.
     */
    public function key ():mixed;

    /**
     * ### Move forward to next element
     * @since 1.0.0
     *
     * @return void
     */
    public function next ():void;

    /**
     * ### Rewind the iterator to the first element
     * @since 1.0.0
     *
     * @return void
     *
     * @note This is the first method called when starting a foreach loop.
     * It will not be executed after foreach loops.
     */
    public function rewind ():void;

}