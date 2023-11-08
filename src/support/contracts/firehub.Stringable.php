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

use Stringable as InternalStringable;

/**
 * ### Stringable contract
 * @since 1.0.0
 */
interface Stringable extends InternalStringable {

    /**
     * ### Gets a string representation of the object
     * @since 1.0.0
     *
     * @return string String representation of the object.
     */
    public function __toString ():string;

}