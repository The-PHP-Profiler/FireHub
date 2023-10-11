<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2023 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @package FireHub\Core\Base
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Base;

use FireHub\Core\Support\Contracts\ {
    Cloneable, JsonSerializable, Overloadable, Serializable
};

/**
 * ### FireHub master static interface
 * @since 1.0.0
 */
interface MasterStatic extends Prime, Cloneable, JsonSerializable, Overloadable, Serializable {

}