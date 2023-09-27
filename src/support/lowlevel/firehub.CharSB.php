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

use function chr;
use function ord;

/**
 * ### Single-byte character low level class
 *
 * Class allow you to manipulate characters in various ways.
 * @since 1.0.0
 */
final class CharSB extends CharSafe {

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     *
     * @link https://www.man7.org/linux/man-pages/man7/ascii.7.html List of codepoint values
     */
    public static function chr (int $codepoint):string {

        return chr($codepoint);

    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public static function ord (string $character):int {

        return ord($character);

    }

}