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

namespace FireHub\Core\Support\String;

use FireHub\Core\Support\Str;
use FireHub\Core\Support\Enums\String\Encoding;

/**
 * ### String class
 * @since 1.0.0
 */
abstract class aString extends Str {

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     *
     * @uses \FireHub\Core\Support\Str::__construct Parent constructor.
     * @uses \FireHub\Core\Support\LowLevel\StrMB::encoding() To set/get internal character encoding.
     * @uses \FireHub\Core\Support\String\aString::normalize() To normalize string.
     */
    final protected function __construct (
        protected string $string,
        protected ?Encoding $encoding = null
    ) {

        parent::__construct($string, $this->encoding);

        $this->normalize();

    }

}