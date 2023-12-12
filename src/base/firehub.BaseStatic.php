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

use FireHub\Core\Base\Traits\ {
    UnCallables, UnCloneable, UnJsonSerializable, UnOverloadable, UnSerializable
};

/**
 * ### FireHub base static class trait
 * @since 1.0.0
 */
trait BaseStatic {

    /**
     * ### UnCallables trait
     * @since 1.0.0
     */
    use UnCallables;

    /**
     * ### UnCloneable trait
     * @since 1.0.0
     */
    use UnCloneable;

    /**
     * ### UnJsonSerializable trait
     * @since 1.0.0
     */
    use UnJsonSerializable;

    /**
     * ### UnOverloadable trait
     * @since 1.0.0
     */
    use UnOverloadable;

    /**
     * ### UnSerializable trait
     * @since 1.0.0
     */
    use UnSerializable;

    /**
     * ### Method called on each newly created object
     *
     * Constructors are ordinary methods that are called during the instantiation of their corresponding object.
     * As such, they may define an arbitrary number of arguments, which may be required, may have a type, and may have a default value.
     * Constructor arguments are called by placing the arguments in parentheses after the class name.
     * @since 1.0.0
     *
     * @return void
     */
    final private function __construct () {}

}