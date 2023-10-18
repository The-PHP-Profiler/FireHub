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
use Error;

use function mb_ereg;
use function mb_ereg_replace;
use function mb_eregi;
use function mb_regex_encoding;

/**
 * @inheritDoc
 *
 * @since 1.0.0
 */
final class RegexMB extends Regex {

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @param string $pattern <p>
     * The regular expression pattern.
     * </p>
     * @param string $string <p>
     * The string being evaluated.
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Case-sensitive match.
     * </p>
     */
    public static function match (string $pattern, string $string, bool $case_sensitive = true):bool {

        return $case_sensitive
            ? mb_ereg($pattern, $string)
            : mb_eregi($pattern, $string);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws Error If string is not valid for the current encoding, or while performing a regular expression search
     * and replace.
     *
     * @warning Never use the e modifier when working on untrusted input. No automatic escaping will happen (as known
     * from preg_replace()). Not taking care of this will most likely create remote code execution vulnerabilities in
     * your application.
     *
     * @note The internal encoding or the character encoding specified by encoding() will be used as character
     * encoding for this function.
     */
    public static function replace (string $pattern, string $replacement, string $string, int $limit = -1):string {

        return mb_ereg_replace($pattern, $replacement, $string)
            ?: throw new Error("Error while perform a regular expression search and replace.");

    }

    /**
     * ### Set/Get character encoding for multibyte regex
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::string() To find whether the type of variable is a string.
     *
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     *
     * @return Encoding|bool If encoding is set, then returns true on success or false on failure. In this case, the
     * internal character encoding is NOT changed.
     * If encoding is omitted, then the current character encoding name for a multibyte regex is returned.
     */
    public static function encoding (Encoding $encoding = null):Encoding|bool {

        return DataIs::string($regex_encoding = mb_regex_encoding($encoding?->value))
            ? (Encoding::tryFrom($regex_encoding)
                ?? throw new Error('x'))
            : $regex_encoding;

    }

}