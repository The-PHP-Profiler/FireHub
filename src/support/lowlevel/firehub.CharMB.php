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

use FireHub\Core\Support\Enums\String\Encoding;

use function mb_chr;
use function mb_ord;

/**
 * ### Multibyte character low level class
 *
 * Class provides multibyte specific character functions that help you deal with multibyte encodings.
 * @since 1.0.0
 */
final class CharMB extends CharSafe {

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     *
     * @param int $codepoint <p>
     * The codepoint value.
     * </p>
     * @param \FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     *
     * @link https://en.wikipedia.org/wiki/List_of_Unicode_characters List of codepoint values
     */
    public static function chr (int $codepoint, Encoding $encoding = null):string|false {

        return mb_chr($codepoint, $encoding?->value);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     *
     * @param string $character <p>
     * A character.
     * </p>
     * @param \FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     */
    public static function ord (string $character, Encoding $encoding = null):int|false {

        return mb_ord($character, $encoding?->value);

    }

}