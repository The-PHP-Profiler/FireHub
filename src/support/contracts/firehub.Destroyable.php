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

/**
 * ### Destroyable contract
 * @since 1.0.0
 */
interface Destroyable {

    /**
     * ### Method called as soon as there are no other references to a particular object
     *
     * Like constructors, the engine will not call parent destructors implicitly.
     * To run a parent destructor, one would have to explicitly call parent::__destruct() in the destructor body.
     * Also like constructors, a child class may inherit the parent's destructor if it does not implement one itself.
     * @since 1.0.0
     *
     * @return void
     */
    public function __destruct ();

}