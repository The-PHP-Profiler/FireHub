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

/**
 * ### Word class
 *
 * Class allows you to manipulate words in various ways.
 * @since 1.0.0
 *
 * @api
 */
final class Word extends aString {

    /**
     * ### Lowercase string separated by dash
     *
     * Dashes are inserted before uppercase characters (except the first character of the string),
     * and in place of spaces, dashes, and underscores. Alpha delimiters are not converted to lowercase.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\String\Word::delimit() To lowercase string separated by the given delimiter.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\String\Word;
     *
     * Word::('FireHub')->dasherize();
     *
     * // fire-hub
     * ```
     *
     * @return $this This string.
     */
    public function dasherize ():self {

        return $this->delimit('-');

    }

    /**
     * ### Lowercase string separated by the given delimiter
     *
     * Delimiters are inserted before uppercase characters (except the first character of the string),
     * and in place of spaces, dashes, and underscores. Alpha delimiters are not converted to lowercase.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\String\Word::regexReplace() To perform a regular expression search and replace.
     * @uses \FireHub\Core\Support\String\Word::toLowerCase() To make a string lowercase.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\String\Word;
     *
     * Word::('FireHub')->delimit('::');
     *
     * // fire::hub
     * ```
     *
     * @return $this This string.
     */
    public function delimit (string $delimiter):self {

        $this->string = $this->regexReplace('\B([A-Z])', '-\1');
        $this->string = $this->regexReplace('[-_\s]+', $delimiter);

        return $this->toLowerCase();

    }

    /**
     * ### Boolean representation of the given logical string value
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\String\Word::toLowerCase() To make a string lowercase.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\String\Word;
     *
     * Word::('off')->toBool();
     *
     * // false
     * ```
     *
     * @return null|bool Boolean representation of the given logical string value,
     * or null if there is no logical string value.
     *
     * @note The case is ignored.
     */
    public function toBool ():?bool {

        return match ($this->toLowerCase()->string) {
            'on', 'true', '1', 'yes' => true,
            'off', 'false', '0', 'no' => false,
            default => null
        };

    }

}