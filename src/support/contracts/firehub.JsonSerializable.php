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

use JsonSerializable as InternalJsonSerializable;

/**
 * ### JsonSerializable contract
 *
 * Serializes the object to a value that can be serialized natively by json_encode().
 * @since 1.0.0
 */
interface JsonSerializable extends InternalJsonSerializable {

    /**
     * ### Specify data which should be serialized to JSON
     * @since 1.0.0
     *
     * @return array<array-key, mixed> Data that can be serialized by json_encode().
     */
    public function jsonSerialize ():array;

}