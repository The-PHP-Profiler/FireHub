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

use Error;

use function preg_match;
use function preg_replace;
use function preg_replace_callback;

/**
 * @inheritDoc
 *
 * @since 1.0.0
 */
final class RegexSB extends Regex {

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
     * @param int $offset [optional] <p>
     * Normally, the search starts from the beginning of the subject string. The optional parameter offset can be used
     * to specify the alternate place from which to start the search (in bytes).
     * </p>
     *
     * @error\exeption E_WARNING if the regex pattern passed does not compile to a valid regex.
     */
    public static function match (string $pattern, string $string, int $offset = 0):bool {

        return preg_match($pattern, $string) === 1;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @param string $pattern <p>
     * The regular expression pattern.
     * </p>
     * @param string $replacement <p>
     * The string to replace.
     * </p>
     * @param string $string <p>
     * The string being evaluated.
     * </p>
     * @param int $limit [optional] <p>
     * The maximum possible replacements for each pattern in each subject string. Defaults to -1 (no limit).
     * </p>
     *
     * @throws Error If error while performing a regular expression search and replace.
     * @error\exeption E_WARNING using the "\e" modifier, or If the regex pattern passed does not compile to valid
     * regex.
     */
    public static function replace (string $pattern, string $replacement, string $string, int $limit = -1):string {

        return preg_replace($pattern, $replacement, $string, $limit)
            ?? throw new Error("Error while performing a regular expression search and replace.");

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @param string $pattern <p>
     * The regular expression pattern.
     * </p>
     * @param callable(list<string> $matches):string $callback <p>
     * A callback that will be called and passed an array of matched elements in the subject string.
     * The callback should return the replacement string.
     * This is the callback signature.
     * </p>
     * @param string $string <p>
     * The string being evaluated.
     * </p>
     * @param int $limit [optional] <p>
     * The maximum possible replacements for each pattern in each subject string. Defaults to -1 (no limit).
     * </p>
     *
     * @throws Error If error while performing a regular expression search and replace.
     * @error\exeption E_WARNING using the "\e" modifier, or If the regex pattern passed does not compile to valid
     * regex.
     */
    public static function replaceFunc (string $pattern, callable $callback, string $string, int $limit = -1):string {

        return preg_replace_callback($pattern, $callback, $string, $limit)
            ?? throw new Error("Error while performing a regular expression search and replace.");

    }

}