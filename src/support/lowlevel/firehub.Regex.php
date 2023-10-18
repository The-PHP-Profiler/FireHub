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

use FireHub\Core\Base\ {
    BaseStatic, MasterStatic
};


/**
 * ### Regex low-level class
 *
 * The syntax for patterns used in these functions closely resembles Perl. The expression must be enclosed in the
 * delimiters, a forward slash (/), for example. Delimiters can be any non-alphanumeric, non-whitespace ASCII character
 * except the backslash (\) and the null byte. If the delimiter character has to be used in the expression itself,
 * it needs to be escaped by backslash. Perl-style (), {}, [], and <> matching delimiters may also be used.
 * @since 1.0.0
 */
abstract class Regex implements MasterStatic {

    /**
     * ### FireHub base static class trait
     * @since 1.0.0
     */
    use BaseStatic;

    /**
     * ### Perform a regular expression match
     *
     * Searches subject for a match to the regular expression given in a pattern.
     * @since 1.0.0
     *
     * @param string $pattern <p>
     * The regular expression pattern.
     * </p>
     * @param string $string <p>
     * The string being evaluated.
     * </p>
     *
     * @return bool True if string matches the regular expression pattern, false if not.
     */
    abstract public static function match (string $pattern, string $string):bool;

    /**
     * ### Perform a regular expression search and replace
     *
     * Searches $subject for matches to $pattern and replaces them with $replacement.
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
     *
     * @return string Replaced string.
     */
    abstract public static function replace (string $pattern, string $replacement, string $string):string;

}