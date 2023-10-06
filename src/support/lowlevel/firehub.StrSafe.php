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

use const FireHub\Core\Support\Constants\Number\MAX;

use function addslashes;
use function explode;
use function implode;
use function ltrim;
use function rtrim;
use function quotemeta;
use function str_contains;
use function str_ends_with;
use function str_repeat;
use function str_replace;
use function str_ireplace;
use function str_starts_with;
use function strcmp;
use function strip_tags;
use function stripslashes;
use function trim;


/**
 * ### Safe string low level class
 *
 * Class contains methods that are safe to use on normal as well as on multibyte encoding.
 * @since 1.0.0
 */
abstract class StrSafe {

    /**
     * ### Get string length
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string being measured for length.
     * </p>
     *
     * @return non-negative-int String length.
     */
    abstract public static function length (string $string):int;

    /**
     * ### Quote string with slashes
     *
     * Backslashes are added before characters that need to be escaped:
     * (single quote, double quote, backslash, NUL).
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string to be escaped.
     * </p>
     *
     * @return string Escaped string.
     *
     * @note The addSlashes() is sometimes incorrectly used to try to prevent SQL Injection. Instead, database-specific
     * escaping functions and/or prepared statements should be used.
     */
    public static function addSlashes (string $string):string {

        return addslashes($string);

    }

    /**
     * ### Un-quotes a quoted string
     *
     * Backslashes are removed:
     * (backslashes becomes single quote, double backslashes are made into a single backslash).
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string to be un-quoted.
     * </p>
     *
     * @return string String with backslashes stripped off.
     *
     * @note stripSlashes() is not recursive. If you want to apply this function to a multidimensional array,
     * you need to use a recursive function.
     *
     * @tip stripSlashes() can be used if you aren't inserting this data into a place (such as a database) that requires
     * escaping. For example, if you're simply outputting data straight from an HTML form.
     */
    public static function stripSlashes (string $string):string {

        return stripslashes($string);

    }

    /**
     * ### Strip HTML and PHP tags from a string
     *
     * This function tries to return a string with all NULL bytes, HTML and PHP tags stripped from a given string. It
     * uses the same tag stripping state machine as the fgetss() function.
     * @since 1.0.0
     *
     * @param string $string <p>
     * The input string.
     * </p>
     * @param null|string|string[] $allowed_tags <p>
     * You can use the optional second parameter to specify tags which should not be stripped.
     *
     * note: Self-closing XHTML tags are ignored and only non-self-closing tags should be used in allowed_tags.
     * For example, to allow both <br> and <br/>, you should use: <br>
     * </p>
     *
     * @return string the Stripped string.
     */
    public static function stripTags (string $string, null|string|array $allowed_tags = null):string {

        return strip_tags($string, $allowed_tags);

    }

    /**
     * ### Quote meta characters
     *
     * Returns a version of str with a backslash character (\) before every character that is among these:
     * .\+*?[^]($).
     * @since 1.0.0
     *
     * @param non-empty-string $string <p>
     * The input string.
     * </p>
     *
     * @throws Error If empty string is given as string.
     *
     * @return string The string with meta characters quoted.
     */
    final public static function quoteMeta (string $string):string {

        return quotemeta($string)
            ?: throw new Error('Empty string is given as string.');

    }

    /**
     * ### Replace all occurrences of the search string with the replacement string
     *
     * This function returns a string or an array with all occurrences of search
     * in subject replaced with the given replace value.
     * @since 1.0.0
     *
     * @param string|array<int, string> $search <p>
     * The replacement value that replaces found search values.
     * An array may be used to designate multiple replacements.
     * </p>
     * @param string|array<int, string> $replace <p>
     * The string being searched and replaced on.
     * </p>
     * @param string $string <p>
     * The value being searched for.
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Searched values are case-insensitive.
     * </p>
     * @param int|null &$count [optional] <p>
     * If passed, this will hold the number of matched and replaced needles.
     * </p>
     *
     * @return string String with the replaced values.
     *
     * @note Multibyte characters may not work as expected while $case_sensitive is on.
     *
     * @note Because method replaces left to right, it might replace a previously inserted value when doing
     * multiple replacements.
     *
     * @tip To replace text based on a pattern rather than a fixed string, use preg_replace().
     */
    final public static function replace (string|array $search, string|array $replace, string $string, bool $case_sensitive = true, int &$count = null):string {

        if ($case_sensitive) return str_replace($search, $replace, $string, $count);

        return str_ireplace($search, $replace, $string, $count);

    }

    /**
     * ### Repeat a string
     *
     * Returns string repeated $times times.
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string to be repeated.
     * </p>
     * @param positive-int $times <p>
     * Number of time the input string should be repeated.
     * Multiplier has to be greater than or equal to 0. If the multiplier is set to 0, the function will return an empty string.
     * </p>
     * @param string $separator [optional] <p>
     * Separator in between any repeated string.
     * </p>
     *
     * @return string Repeated string.
     */
    final public static function repeat (string $string, int $times, string $separator = ''):string {

        return $times === 0 ? '' : str_repeat($string.$separator, $times - 1).$string;

    }

    /**
     * ### Split a string by a string
     *
     * Returns an array of strings, each of which is a substring of string formed by splitting it on boundaries
     * formed by the string separator.
     * @since 1.0.0
     *
     * @param string $string <p>
     * The input string.
     * </p>
     * @param non-empty-string $separator <p>
     * The boundary string.
     * </p>
     * @param int<min, max> $limit [optional] <p>
     * If limit is set and positive, the returned array will contain a maximum of limit elements with the last element containing the rest of string.
     * If the limit parameter is negative, all components except the last -limit are returned.
     * If the limit parameter is zero, then this is treated as 1.
     * </p>
     *
     * @throws ValueError If separator is an empty string.
     *
     * @return string[] If delimiter contains a value that is not contained in string and a negative limit is
     * used, then an empty array will be returned. For any other limit, an array containing string will be returned.
     */
    final public static function explode (string $string, string $separator, int $limit = MAX):array {

        return explode($separator, $string, $limit);

    }

    /**
     * ### Join array elements with a string
     *
     * Join array elements with a $separator string.
     * @since 1.0.0
     *
     * @param string[] $array <p>
     * The array of strings to implode.
     * </p>
     * @param string $separator [optional] <p>
     * The boundary string.
     * </p>
     * @return string Returns a string containing a string representation of all the array elements in the same order,
     * with the separator string between each element.
     */
    final public static function implode (array $array, string $separator = ''):string {

        return implode($separator, $array);

    }

    /**
     * ### Convert a string to an array
     * @since 1.0.0
     *
     * @param string $string <p>
     * The input string.
     * </p>
     * @param positive-int $length [optional] <p>
     * Maximum length of the chunk.
     * </p>
     *
     * @return array<int, string> If the optional length parameter is specified,
     * the returned array will be broken down into chunks with each being length in length,
     * except the final chunk which may be shorter if the string does not divide evenly.
     * The default length is 1, meaning every chunk will be one byte in size.
     */
    abstract public static function split (string $string, int $length = 1):array;

    /**
     * ### Strip whitespace (or other characters) from the beginning and end of a string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\Side::BOTH As parameter.
     * @uses \FireHub\Core\Support\Enums\Side::LEFT If $side parameter is set to left.
     * @uses \FireHub\Core\Support\Enums\Side::RIGHT If $side parameter is set to right.
     *
     * @param string $string <p>
     * The string that will be trimmed.
     * </p>
     * @param \FireHub\Core\Support\Enums\Side $side [optional] <p>
     * Side to trim string.
     * </p>
     * @param string $characters [optional] <p>
     * The stripped characters can also be specified using the char-list parameter.
     * Simply list all characters that you want to be stripped.
     * With .. you can specify a range of characters.
     * </p>
     *
     * @return string The trimmed string.
     *
     * @note Because trim() trims characters from the beginning and end of a string, it may be confusing when characters
     * are (or are not) removed from the middle. trim('abc', 'bad') removes both 'a' and 'b' because it trims 'a'
     * thus moving 'b' to the beginning to also be trimmed. So, this is why it "works" whereas trim('abc', 'b')
     * seemingly does not.
     */
    final public static function trim (string $string, Side $side = Side::BOTH, string $characters = " \n\r\t\v\x00"):string {

        return match($side) {
            Side::LEFT => ltrim($string, $characters),
            Side::RIGHT => rtrim($string, $characters),
            Side::BOTH => trim($string, $characters)
        };

    }

    /**
     * ### Make a string lowercase
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string being lowercased.
     * </p>
     *
     * @return string String with all alphabetic characters converted to lowercase.
     */
    abstract public static function toLower (string $string):string;

    /**
     * ### Make a string uppercase
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string being uppercased.
     * </p>
     *
     * @return string String with all alphabetic characters converted to uppercase.
     */
    abstract public static function toUpper (string $string):string;

    /**
     * ### Make a string title-cased
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string being title cased.
     * </p>
     *
     * @return string String with title-cased conversion.
     */
    abstract public static function toTitle (string $string):string;

    /**
     * ### Find the position of the first occurrence of a substring in a string
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
     *
     * @return int|false Numeric position of the first occurrence or false if none exist.
     */
    abstract public static function firstPosition (string $search, string $string, bool $case_sensitive = true):int|false;

    /**
     * ### Find the position of the last occurrence of a substring in a string
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
     *
     * @return int|false Numeric position of the last occurrence or false if none exist.
     */
    abstract public static function lastPosition (string $search, string $string, bool $case_sensitive = true):int|false;

    /**
     * ### Checks if a string starts with a given value
     *
     * Performs a case-sensitive check indicating if $string begins with $value.
     * @since 1.0.0
     *
     * @param non-empty-string $value <p>
     * The value to search for.
     * </p>
     * @param string $string <p>
     * The string to search in.
     * </p>
     *
     * @return bool True if string starts with value, false otherwise.
     */
    final public static function startsWith (string $value, string $string):bool {

        return $value && str_starts_with($string, $value);

    }

    /**
     * ### Checks if a string ends with a given value
     *
     * Performs a case-sensitive check indicating if $string ends with $value.
     * @since 1.0.0
     *
     * @param non-empty-string $value <p>
     * The value to search for.
     * </p>
     * @param string $string <p>
     * The string to search in.
     * </p>
     *
     * @return bool True if string ends with value, false otherwise.
     */
    final public static function endsWith (string $value, string $string):bool {

        return $value && str_ends_with($string, $value);

    }

    /**
     * ### Get part of string
     *
     * Returns the portion of string specified by the $start and $length parameters.
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string to extract the substring from.
     * </p>
     * @param int $start <p>
     * If start is non-negative, the returned string will start at the start position in string, counting from zero.
     * For instance, in the string 'abcdef', the character at position 0 is 'a',
     * the character at position 2 is 'c', and so forth.
     * If start is negative, the returned string will start at the start character from the end of string.
     * </p>
     * @param null|int $length [optional] <p>
     * Maximum number of characters to use from string.
     * If omitted or NULL is passed, extract all characters to the end of the string.
     * </p>
     *
     * @return string The portion of string specified by the start and length parameters.
     */
    abstract public static function part (string $string, int $start, ?int $length = null):string;

    /**
     * ### Get part of string
     *
     * Returns the number of times the needle substring occurs in the haystack string.
     * Please note that needle is case-sensitive.
     * @since 1.0.0
     *
     * @param string $string <p>
     * The string being checked.
     * </p>
     * @param string $search <p>
     * The string being found.
     * </p>
     *
     * @return int Number of times the searched substring occurs in the string.
     */
    abstract public static function partCount (string $string, string $search):int;

    /**
     * ### Find first part of a string
     *
     * Returns part of $string string starting from and including the first occurrence of $find to the end of $string.
     * @since 1.0.0
     *
     * @param string $find <p>
     * String to find.
     * </p>
     * @param string $string <p>
     * The string being searched.
     * </p>
     * @param bool $before_needle [optional] <p>
     * If true, returns the part of the string before the first occurrence (excluding the find string).
     * </p>
     *
     * @return string|false The portion of string or false if needle is not found.
     */
    abstract public static function firstPart (string $find, string $string, bool $before_needle = false):string|false;

    /**
     * ### Find last part of a string
     *
     * This function returns the portion of $string which starts at the last occurrence of $find and goes until the
     * end of $string.
     * @since 1.0.0
     *
     * @param string $find <p>
     * String to find.
     * </p>
     * @param string $string <p>
     * The string being searched.
     * </p>
     *
     * @return string|false The portion of string, or false if needle is not found.
     */
    abstract public static function lastPart (string $find, string $string):string|false;

    /**
     * ### Checks if string contains value
     *
     * Performs a case-sensitive check indicating bif $string is contained in $string.
     * @since 1.0.0
     *
     * @param string $value <p>
     * The value to search for.
     * </p>
     * @param string $string <p>
     * The string to search in.
     * </p>
     *
     * @return bool True if string contains value, false otherwise.
     */
    final public static function contains (string $value, string $string):bool {

        return str_contains($string, $value);

    }

    /**
     * ### String comparison
     * @since 1.0.0
     *
     * @param string $string_1 <p>
     * String to compare against.
     * </p>
     * @param string $string_2 <p>
     * String to compare with.
     * </p>
     *
     * @return int<-1, 1> -1 if string1 is less than string2; 1 if string1 is greater than string2, and 0 if they are
     * equal.
     *
     * @note This comparison is case-sensitive.
     */
    public static function compare (string $string_1, string $string_2):int {

        return strcmp($string_1, $string_2);

    }

}