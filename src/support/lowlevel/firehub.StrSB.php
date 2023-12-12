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

use FireHub\Core\Support\Enums\Side;
use Error, ValueError;

use function addcslashes;
use function chunk_split;
use function count_chars;
use function lcfirst;
use function str_pad;
use function str_shuffle;
use function str_split;
use function str_word_count;
use function strcasecmp;
use function strcspn;
use function strncmp;
use function strpos;
use function strrpos;
use function strripos;
use function stripcslashes;
use function stripos;
use function stristr;
use function strlen;
use function strpbrk;
use function strrchr;
use function strrev;
use function strspn;
use function strstr;
use function strtolower;
use function strtoupper;
use function strtr;
use function substr;
use function substr_compare;
use function substr_count;
use function substr_replace;
use function ucfirst;
use function ucwords;
use function wordwrap;

/**
 * ### Single-byte string low-level class
 *
 * Class allows you to manipulate strings in various ways.
 * @since 1.0.0
 */
final class StrSB extends StrSafe {

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @note Returns the number of bytes rather than the number of characters in a string.
     */
    public static function length (string $string):int {

        return strlen($string);

    }

    /**
     * ### Return information about characters used in a string
     *
     * Counts the number of occurrences of every byte-value (0..255) in string and returns it in various ways.
     * @since 1.0.0
     *
     * @param string $string <p>
     * The examined string.
     * </p>
     *
     * @return array<int, int> An array with the byte-value as a key with a frequency greater than zero are listed.
     */
    public static function countByChar (string $string):array {

        /** @phpstan-ignore-next-line return is always array */
        return count_chars($string, 1);

    }

    /**
     * @inheritDoc
     *
     * ### Quote string with slashes
     *
     * If $characters are not set then backslashes are added before characters that need to be escaped:
     * (single quote, double quote, backslash, NUL).
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrSafe::addSlashes() To quote string with slashes.
     *
     * @param string $string <p>
     * The string to be escaped.
     * </p>
     * @param string $characters [optional] <p>
     * The list of characters to be escaped.
     * Non-alphanumeric characters with ASCII codes lower than 32 and higher than 126 converted to octal representation.
     * </p>
     */
    public static function addSlashes (string $string, string $characters = null):string {

        return !empty($characters)
            ? addcslashes($string, $characters)
            : parent::addSlashes($string);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrSafe::stripSlashes() To un-quotes a quoted string.
     *
     * @param string $string <p>
     * The string to be unquoted.
     * </p>
     * @param bool $c_representation [optional] <p>
     * If true, octal and hexadecimal representation from addSlashes method are recognized.
     * </p>
     */
    public static function stripSlashes (string $string, bool $c_representation = false):string {

        return $c_representation
            ? stripcslashes($string)
            : parent::stripSlashes($string);

    }

    /**
     * ### Replace text within a portion of a string
     *
     * Replaces a copy of string delimited by the $offset and (optionally) $length parameters with the string given in
     * $replace.
     * @since 1.0.0
     *
     * @param string $string <p>
     * The input string.
     * </p>
     * @param string $replace <p>
     * The replacement string.
     * </p>
     * @param int $offset <p>
     * If offset is non-negative, the replacing will begin at the into string.
     * If offset is negative, the replacing will begin at the character from the end of string
     * </p>
     * @param null|int $length [optional] <p>
     * If given and is positive, it represents the length of the portion of string which is to be replaced.
     * If it is negative, it represents the number of characters from the end of string at which to stop replacing.
     * If it is not given, then it will default to strlen(string); i.e., end the replacing at the end of string.
     * Of course, if length is zero, then this function will have the effect of
     * inserting $replace into string at the given offset.
     * </p>
     *
     * @return string String with the replaced values.
     */
    public static function replacePart (string $string, string $replace, int $offset, int $length = null):string {

        return substr_replace($string, $replace, $offset, $length);

    }

    /**
     * ### Split a string into smaller chunks
     *
     * Can be used to split a string into smaller chunks, which is useful for e.g., converting base64_encode() output to
     * match RFC 2045 semantics. It inserts $separator every $length characters.
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string to be chunked.
     * </p>
     * @param positive-int $length [optional] <p>
     * The chunk length.
     * </p>
     * @param string $separator [optional] <p>
     * The line-ending sequence.
     * </p>
     *
     * @throws Error If length is less than 1.
     *
     * @return string The chunked string.
     */
    public static function chunkSplit (string $string, int $length = 76, string $separator = "\r\n"):string {

        return $length < 1
            ? chunk_split($string, $length, $separator)
            : throw new Error('Length is be at least 1.');

    }

    /**
     * ### Pad a string to a certain length with another string
     *
     * This method returns the $string padded on the left, the right, or both sides to the specified padding length.
     * If the optional argument $pad is not supplied, the $string is padded with spaces; otherwise it is padded
     * with characters from $pad up to the limit.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\Side::RIGHT As parameter.
     * @uses \FireHub\Core\Support\Enums\Side::LEFT As parameter.
     * @uses \FireHub\Core\Support\Enums\Side::BOTH As parameter.
     *
     * @param string $string <p>
     * The string being padded.
     * </p>
     * @param int $length <p>
     * If the value of pad_length is negative, less than, or equal to the length of the input string,
     * no padding takes place.
     * </p>
     * @param non-empty-string $pad [optional] <p>
     * The pad may be truncated if the required number of padding characters can't be evenly divided by the pad's
     * length.
     * </p>
     * @param \FireHub\Core\Support\Enums\Side $side [optional] <p>
     * Pad side.
     * </p>
     *
     * @throws Error If the pad is empty.
     *
     * @return string Padded string.
     */
    public static function pad (string $string, int $length, string $pad = " ", Side $side = Side::RIGHT):string {

        return $pad !== '' ? str_pad($string, $length, $pad, match ($side) {
            Side::LEFT => 0,
            Side::RIGHT => 1,
            Side::BOTH => 2
        }) : throw new Error('Pad cannot be empty.');

    }

    /**
     * ### Randomly shuffles a string
     * @since 1.0.0
     *
     * @param string $string <p>
     * The input string.
     * </p>
     *
     * @return string The shuffled string.
     *
     * @caution This function does not generate cryptographically secure values,
     * and must not be used for cryptographic purposes,
     * or purposes that require returned values to be unguessable.
     */
    public static function shuffle (string $string):string {

        return str_shuffle($string);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws Error If length is less than 1.
     */
    public static function split (string $string, int $length = 1):array {

        return !$length < 1
            ? str_split($string, $length)
            : throw new Error('Length must be at least 1.');

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public static function toLower (string $string):string {

        return strtolower($string);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public static function toUpper (string $string):string {

        return strtoupper($string);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public static function toTitle (string $string):string {

        return ucwords($string);

    }

    /**
     * ### Make a first character of string uppercased
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string being converted.
     * </p>
     *
     * @return string String with first character uppercased.
     */
    public static function capitalize (string $string):string {

        return ucfirst($string);

    }

    /**
     * ### Make a first character of string lowercased
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string being converted.
     * </p>
     *
     * @return string String with first character lowercased.
     */
    public static function deCapitalize (string $string):string {

        return lcfirst($string);

    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
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
     */
    public static function firstPosition (string $search, string $string, bool $case_sensitive = true, int $offset = 0):int|false {

        if ($case_sensitive) return strpos($string, $search, $offset);

        return stripos($string, $search, $offset);

    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
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
     */
    public static function lastPosition (string $search, string $string, bool $case_sensitive = true, int $offset = 0):int|false {

        if ($case_sensitive) return strrpos($string, $search, $offset);

        return strripos($string, $search, $offset);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public static function part (string $string, int $start, ?int $length = null):string {

        return substr($string, $start, $length);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string being checked.
     * </p>
     * @param string $search <p>
     * The string being found.
     * </p>
     * @param int $start [optional] <p>
     * The offset where to start counting. If the offset is negative, counting starts from the end of the string.
     * </p>
     * @param null|int $length [optional] <p>
     * The maximum length after the specified offset to search for the substring.
     * It outputs a warning if the offset plus the length is greater than the $string length.
     * A negative length counts from the end of $string.
     * </p>
     *
     * @note This method doesn't count overlapped substring.
     */
    public static function partCount (string $string, string $search, int $start = 0, ?int $length = null):int {

        return substr_count($string, $search, $start, $length);

    }

    /**
     * @inheritDoc
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
     *
     * @since 1.0.0
     */
    public static function firstPart (string $find, string $string, bool $before_needle = false, bool $case_sensitive = true):string|false {

        if ($case_sensitive) return strstr($string, $find, $before_needle);

        return stristr($string, $find, $before_needle);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public static function lastPart (string $find, string $string):string|false {

        return strrchr($string, $find);

    }

    /**
     * ### Find part of a string with characters
     * @since 1.0.0
     *
     * @param string $characters <p>
     * Characters to find.
     * This parameter is case-sensitive.
     * </p>
     * @param string $string <p>
     * The string where characters are looked for.
     * </p>
     *
     * @return string|false String starting from the character found, or false if it is not found.
     */
    public static function partFrom (string $characters, string $string):string|false {

        return strpbrk($string, $characters);

    }

    /**
     * ### Reverse a string
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string to be reversed.
     * </p>
     *
     * @return string The reversed string.
     */
    public static function reverse (string $string):string {

        return strrev($string);

    }

    /**
     * ### Count number of words in string
     *
     * Counts the number of words inside string. If the optional format is not specified, then the return value will
     * be an integer representing the number of words found. In the event the format is specified,
     * the return value will be an array, the content of which is dependent on the format.
     * The possible value for the format and the resultant outputs are listed below.
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string.
     * </p>
     * @param null|string $characters [optional] <p>
     * A list of additional characters which will be considered as 'word'.
     * </p>
     * @param int<0, 2> $format [optional] <p>
     * A string to search words.
     * 0 - returns the number of words found.
     * 1 - returns an array containing all the words found inside the string.
     * 2 - returns an associative array, where the key is the numeric position of the word inside the string and the
     * value is the actual word itself.
     * </p>
     *
     * @return int|array<int, string>|false Number of words found or list of words.
     */
    public static function countWords (string $string, string $characters = null, int $format = 0):int|array|false {

        return str_word_count($string, $format, $characters);

    }

    /**
     * ### Finds the length of the initial segment of a string consisting entirely of characters contained within a
     * given mask
     *
     * Finds the length of the initial segment of $string that contains only characters from $characters.
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string to examine.
     * </p>
     * @param string $characters <p>
     * The list of allowable characters.
     * </p>
     * @param int $offset [optional] <p>
     * The position in subject to start searching.
     * If start is given and is non-negative, then strspn will begin examining the subject at the start position.
     * For instance, in the string 'abcdef', the character at position 0 is 'a',
     * the character at position 2 is 'c', and so forth.
     * If start is given and is negative,
     * then strspn will begin examining subject at the start position from the end of subject.
     * </p>
     * @param int|null $length [optional] <p>
     * The length of the segment from subject to examine.
     * If length is given and is non-negative,
     * then the subject will be examined for length characters after the starting position.
     * If length is given and is negative,
     * then the subject will be examined from the starting position up-to-length characters from the end of the subject.
     * </p>
     *
     * @return int The length of the initial segment of string which consists entirely of characters in characters.
     *
     * @note When offset parameter is set, the returned length is counted starting from this position, not from
     * beginning of the string.
     */
    public static function segmentMatching (string $string, string $characters, int $offset = 0, ?int $length = null):int {

        return strspn($string, $characters, $offset, $length);

    }

    /**
     * ### Find length of initial segment not matching mask
     *
     * Returns the length of the initial segment of $string which does not contain any of the characters in $characters.
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string to examine.
     * </p>
     * @param string $characters <p>
     * The string containing every disallowed character.
     * </p>
     * @param int $offset [optional] <p>
     * The position in subject to start searching.
     * If start is given and is non-negative, then strspn will begin examining the subject at the start position.
     * For instance, in the string 'abcdef', the character at position 0 is 'a',
     * the character at position 2 is 'c', and so forth.
     * If start is given and is negative, then strspn will begin examining subject at the start position
     * from the end of subject.
     * </p>
     * @param int|null $length [optional] <p>
     * The length of the segment from subject to examine.
     * If length is given and is non-negative,
     *  then the subject will be examined for length characters after the starting position.
     * If length is given and is negative, then the subject will be examined from the starting position up-to-length
     * characters from the end of the subject.
     * </p>
     *
     * @return int The length of the initial segment of string which consists entirely of characters not in characters.
     *
     * @note When offset parameter is set, the returned length is counted starting from this position, not from
     * beginning of the string.
     */
    public static function segmentNotMatching (string $string, string $characters, int $offset = 0, ?int $length = null):int {

        return strcspn($string, $characters, $offset, $length);

    }

    /**
     * ### Translate characters or replace substrings
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string being translated to.
     * </p>
     * @param string $from <p>
     * An array of key-value pairs for translation.
     * </p>
     * @param string $to <p>
     * The string replaced from.
     * </p>
     *
     * @return string The translated string.
     */
    public static function translate (string $string, string $from, string $to):string {

        return strtr($string, $from, $to);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @param string $string_1 <p>
     * String to compare against.
     * </p>
     * @param string $string_2 <p>
     * String to compare with.
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Is comparison case-sensitive or not.
     * </p>
     */
    public static function compare (string $string_1, string $string_2, bool $case_sensitive = true):int {

        return $case_sensitive
            ? parent::compare($string_1, $string_2)
            : strcasecmp($string_1, $string_2);

    }

    /**
     * ### String comparison of the first n characters
     * @since 1.0.0
     *
     * @param string $string_1 <p>
     * String to compare against.
     * </p>
     * @param string $string_2 <p>
     * String to compare with.
     * </p>
     * @param int $length <p>
     * Number of characters to use in the comparison.
     * </p>
     *
     * @return int|false -1 if string1 is less than string2; 1 if string1 is greater than string2,
     * and 0 if they are equal, or false if length is less than 0.
     */
    public static function compareFirstN (string $string_1, string $string_2, int $length):int|false {

        return $length > 0 ? strncmp($string_1, $string_2, $length) : false;

    }

    /**
     * ### Comparison of two strings from an offset, up to length characters
     * @since 1.0.0
     *
     * @param string $string_1 <p>
     * String to compare against.
     * </p>
     * @param string $string_2 <p>
     * String to compare with.
     * </p>
     * @param int $offset [optional] <p>
     * The start position for the comparison. If negative, it starts counting from the end of the string.
     * </p>
     * @param null|int $length [optional] <p>
     * The length of the comparison. The default value is the largest of the length of the needle compared to the
     * length of haystack minus the offset.
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * If case_sensitive is true, comparison is case-insensitive.
     * </p>
     *
     * @throws ValueError If $offset is higher than $string_1.
     *
     * @return int<-1, 1> -1 if string1 is less than string2; 1 if string1 is greater than string2, and 0 if they are
     * equal.
     */
    public static function comparePart (string $string_1, string $string_2, int $offset, int $length = null, bool $case_sensitive = true):int {

        /** @phpstan-ignore-next-line */
        return substr_compare(
            $string_1,
            $string_2,
            $offset,
            $length,
            !$case_sensitive);

    }

    /**
     * ### Wraps a string to a given number of characters
     *
     * Wraps a string to a given number of characters using a string break character.
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string to warp.
     * </p>
     * @param int $width [optional] <p>
     * The column width.
     * </p>
     * @param string $break [optional] <p>
     * The line is broken using the optional break parameter.
     * </p>
     * @param bool $cut_long_words [optional] <p>
     * If the cut is set to true, the string is always wrapped at or before the specified width.
     * So if you have a word that is larger than the given width, it is broken apart.
     * </p>
     *
     * @return string The given string wrapped at the specified column.
     */
    public static function wrap (string $string, int $width = 75, string $break = "\n", bool $cut_long_words = false):string {

        return wordwrap($string, $width, $break, $cut_long_words);

    }

}