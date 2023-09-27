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

namespace FireHub\Core\Support\LowLevel;

/**
 * ### Char string low level class
 *
 * Class contains methods that are safe to use on normal as well as on multibyte encoding.
 * @since 1.0.0
 */
abstract class CharSafe {

    /**
     * ### Generate character from codepoint value
     * @since 1.0.0
     *
     * @param int $codepoint <p>
     * The codepoint value.
     * </p>
     *
     * @return string|false A string containing the requested character, false otherwise.
     */
    abstract public static function chr (int $codepoint):string|false;

    /**
     * ### Get codepoint value of character
     * @since 1.0.0
     *
     * @param string $character <p>
     * A character.
     * </p>
     *
     * @return int|false The codepoint value of character, false otherwise.
     */
    abstract public static function ord (string $character):int|false;

}