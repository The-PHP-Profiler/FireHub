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

use FireHub\Core\Support\Enums\String\ {
    CaseFolding, Encoding
};
use Error, ValueError;

use function mb_check_encoding;
use function mb_convert_case;
use function mb_convert_encoding;
use function mb_detect_encoding;
use function mb_str_split;
use function mb_strpos;
use function mb_strrchr;
use function mb_strrichr;
use function mb_strrpos;
use function mb_strripos;
use function mb_stripos;
use function mb_stristr;
use function mb_strlen;
use function mb_strstr;
use function mb_substr;
use function mb_substr_count;

/**
 * ### Multibyte string low-level class
 *
 * Class provides multibyte specific string functions that help you deal with multibyte encodings.
 * @since 1.0.0
 */
final class StrMB extends StrSafe {

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     *
     * @param string $string <p>
     * The string being measured for length.
     * </p>
     * @param \FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     *
     * @error\exeption E_WARNING If the encoding is unknown.
     */
    public static function length (string $string, Encoding $encoding = null):int {

        return mb_strlen($string, $encoding?->value);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     *
     * @param string $string <p>
     * The input string.
     * </p>
     * @param positive-int $length [optional] <p>
     * Maximum length of the chunk.
     * </p>
     * @param \FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     *
     * @throws Error If length is less than 1.
     */
    public static function split (string $string, int $length = 1, Encoding $encoding = null):array {

        return !$length < 1
            ? mb_str_split($string, $length, $encoding?->value)
            : throw new Error('Length must be at least 1.');

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::convert() To perform case folding on a string.
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     * @uses \FireHub\Core\Support\LowLevel\CaseFolding::LOWER() As case folding enum.
     *
     * @param string $string <p>
     * The string being lowercased.
     * </p>
     * @param \FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     */
    public static function toLower (string $string, Encoding $encoding = null):string {

        return self::convert($string, CaseFolding::LOWER, $encoding);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::convert() To perform case folding on a string.
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     * @uses \FireHub\Core\Support\LowLevel\CaseFolding::UPPER() As case folding enum.
     *
     * @param string $string <p>
     * The string being uppercased.
     * </p>
     * @param \FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     */
    public static function toUpper (string $string, Encoding $encoding = null):string {

        return self::convert($string, CaseFolding::UPPER, $encoding);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::convert() To perform case folding on a string.
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     * @uses \FireHub\Core\Support\LowLevel\CaseFolding::UPPER() As case folding enum.
     *
     * @param string $string <p>
     * The string being title cased.
     * </p>
     * @param \FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     */
    public static function toTitle (string $string, Encoding $encoding = null):string {

        return self::convert($string, CaseFolding::TITLE, $encoding);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     *
     * @param string $search <p>
     * A string to find position.
     * </p>
     * @param string $string <p>
     * The string to search in.
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Search case-sensitive position.
     * </p>
     * @param int $offset [optional] <p>
     * If specified, search will start this number of characters counted from the beginning of the string.
     * </p>
     * @param \FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     */
    public static function firstPosition (string $search, string $string, bool $case_sensitive = true, int $offset = 0, Encoding $encoding = null):int|false {

        if ($case_sensitive) return mb_strpos($string, $search, $offset, $encoding?->value);

        return mb_stripos($string, $search, $offset, $encoding?->value);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     *
     * @param string $search <p>
     * A string to find position.
     * </p>
     * @param string $string <p>
     * The string to search in.
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Search case-sensitive position.
     * </p>
     * @param int $offset [optional] <p>
     * If specified, search will start this number of characters counted from the beginning of the string.
     * </p>
     * @param \FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     */
    public static function lastPosition (string $search, string $string, bool $case_sensitive = true, int $offset = 0, Encoding $encoding = null):int|false {

        if ($case_sensitive) return mb_strrpos($string, $search, $offset, $encoding?->value);

        return mb_strripos($string, $search, $offset, $encoding?->value);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     *
     * @param string $string <p>
     * The string to extract the substring from.
     * </p>
     * @param int $start <p>
     * If start is non-negative, the returned string will start at the start position in string, counting from zero.
     * For instance, in the string 'abcdef', the character at position 0 is 'a',
     * the character at position 2 is 'c', and so forth.
     * If the start is negative, the returned string will start at the start character from the end of the string.
     * </p>
     * @param null|int $length [optional] <p>
     * Maximum number of characters to use from string.
     * If omitted or NULL is passed, extract all characters to the end of the string.
     * </p>
     * @param \FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     */
    public static function part (string $string, int $start, ?int $length = null, Encoding $encoding = null):string {

        return mb_substr($string, $start, $length, $encoding?->value);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     *
     * @param string $string <p>
     * The string being checked.
     * </p>
     * @param string $search <p>
     * The string being found.
     * </p>
     * @param \FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     */
    public static function partCount (string $string, string $search, Encoding $encoding = null):int {

        return mb_substr_count($string, $search, $encoding?->value);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     *
     * @param string $find <p>
     * String to find.
     * </p>
     * @param string $string <p>
     * The string being searched.
     * </p>
     * @param bool $before_needle [optional] <p>
     * If true, return the part of the string before the first occurrence (excluding the find string).
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Is string to find case-sensitive or not.
     * </p>
     * @param \FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     */
    public static function firstPart (string $find, string $string, bool $before_needle = false, bool $case_sensitive = true, Encoding $encoding = null):string|false {

        if ($case_sensitive) return mb_strstr($string, $find, $before_needle, $encoding?->value);

        return mb_stristr($string, $find, $before_needle, $encoding?->value);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     *
     * @param string $find <p>
     * String to find.
     * </p>
     * @param string $string <p>
     * The string being searched.
     * </p>
     * @param bool $before_needle [optional] <p>
     * If true, return the part of the string before the last occurrence (excluding the find string).
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Is string to find case-sensitive or not.
     * </p>
     * @param \FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     */
    public static function lastPart (string $find, string $string, bool $before_needle = false, bool $case_sensitive = true, Encoding $encoding = null):string|false {

        if ($case_sensitive) return mb_strrchr($string, $find, $before_needle, $encoding?->value);

        return mb_strrichr($string, $find, $before_needle, $encoding?->value);

    }

    /**
     * ### Perform case folding on a string
     *
     * Performs case folding on a string, converted in the way specified by $caseFolding.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\CaseFolding As parameter.
     * @uses \FireHub\Core\Support\Enums\String\CaseFolding::UPPER As case folding enum.
     * @uses \FireHub\Core\Support\Enums\String\CaseFolding::LOWER As case folding enum.
     * @uses \FireHub\Core\Support\Enums\String\CaseFolding::TITLE As case folding enum.
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     *
     * @param $string <p>
     * The string being converted.
     * </p>
     * @param \FireHub\Core\Support\Enums\String\CaseFolding $caseFolding <p>
     * The case folding type.
     * </p>
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     *
     * @return string Converted string.
     */
    private static function convert (string $string, CaseFolding $caseFolding, Encoding $encoding = null):string {

        return mb_convert_case($string, match ($caseFolding) {
            CaseFolding::UPPER => 0,
            CaseFolding::LOWER => 1,
            CaseFolding::TITLE => 2
        }, $encoding?->value);

    }

    /**
     * ### Detect character encoding
     *
     * Detects the most likely character encoding for string from an ordered list of candidates.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\Encoding As return.
     *
     * @param string $string <p>
     * The string to detect encoding.
     * </p>
     *
     * @return null|\FireHub\Core\Support\Enums\String\Encoding The detected character encoding, or null if the string
     * is not valid in any of the listed encodings.
     */
    public static function detectEncoding (string $string):?Encoding {

        $cases = [];
        foreach (Encoding::cases() as $case) $cases[] = $case->value;

        return Encoding::tryFrom(
            mb_detect_encoding($string, $cases, true) ?: ''
        );

    }

    /**
     * ### Convert a string from one character encoding to another
     *
     * Converts string from $from, or the current internal encoding, to $to.
     * If a string is an array, all its $string values will be converted recursively.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     *
     * @param string $string <p>
     * The string to be converted.
     * </p>
     * @param \FireHub\Core\Support\Enums\String\Encoding $to <p>
     * The desired encoding of the result.
     * </p>
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $from [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     *
     * @throws Error If we could not convert string.
     * @throws ValueError If the value of $to or $from is an invalid encoding.
     *
     * @return string Encoded string.
     */
    public static function convertEncoding (string $string, Encoding $to, Encoding $from = null):string {

        return mb_convert_encoding($string, $to->value, $from?->value)
            ?: throw new Error('Could not convert string.');

    }

    /**
     * ### Check if strings are valid for the specified encoding
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string to check encoding on.
     * </p>
     * @param \FireHub\Core\Support\Enums\String\Encoding $encoding <p>
     * The expected encoding.
     * </p>
     *
     * @return True on success or false on failure.
     */
    public static function checkEncoding (string $string, Encoding $encoding):bool {

        /** @phpstan-ignore-next-line */
        return mb_check_encoding($string, $encoding->value);

    }

}