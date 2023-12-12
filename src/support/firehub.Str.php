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

namespace FireHub\Core\Support;

use FireHub\Core\Base\ {
    Base, Master
};
use FireHub\Core\Support\Contracts\ {
    Countable, Stringable
};
use FireHub\Core\Support\Collections\ {
    Collection, Type\Arr\Indexed, Type\Gen
};
use FireHub\Core\Support\Enums\ {
    Side, String\Encoding
};
use FireHub\Core\Support\LowLevel\ {
    Arr, DataIs, Num, RegexMB, RegexSB, StrMB
};
use Error, Generator, ValueError;

use const FireHub\Core\Support\Constants\Number\MAX;

/**
 * ### String high-level class
 *
 * Class allows you to manipulate strings in various ways.
 * @since 1.0.0
 *
 * @api
 *
 * @phpstan-consistent-constructor
 */
class Str implements Master, Countable, Stringable {

    /**
     * ### FireHub base class trait
     * @since 1.0.0
     */
    use Base;

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::encoding() To set/get internal character encoding.
     *
     * @param string $string <p>
     * String to use.
     * </p>
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     *
     * @return void
     */
    protected function __construct (
        protected string $string,
        protected ?Encoding $encoding = null
    ) {

        /** @phpstan-ignore-next-line */
        $this->encoding = $encoding ?? StrMB::encoding();

    }

    /**
     * ### Create a new string from raw string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub');
     *
     * // FireHub
     * ```
     *
     * @param string $string <p>
     * String to use.
     * </p>
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     *
     * @return static New string.
     */
    final public static function from (string $string, Encoding $encoding = null):static {

        return new static($string, $encoding);

    }

    /**
     * ### Create a new string from array elements with a string
     *
     * Join array elements with a $separator string.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     * @uses \FireHub\Core\Support\LowLevel\StrMB::implode() To join array elements with a string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::fromArray(['F', 'i', 'r', 'e', 'H', 'u', 'B']);
     *
     * // FireHub
     * ```
     *
     * @param string[] $array <p>
     * The array of strings to implode.
     * </p>
     * @param string $separator [optional] <p>
     * The boundary string.
     * </p>
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     *
     * @throws Error If array item could not be converted to string.
     *
     * @return static New string containing a string representation of all the array elements in the same order,
     * with the separator string between each element.
     */
    final public static function fromArray (array $array, string $separator = '', Encoding $encoding = null):static {

        return new static(StrMB::implode($array, $separator), $encoding);


    }

    /**
     * ### Checks if string is empty
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->isEmpty();
     *
     * // false
     * ```
     *
     * @return bool True if string is empty, false otherwise.
     */
    final public function isEmpty ():bool {

        return empty($this->string);

    }

    /**
     * ### Checks if string is lowercased
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Str::regexMatch() To perform a regular expression match.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->isLower();
     *
     * // false
     *
     * @throws Error If we could not get current regex encoding.
     *
     * @return bool True if string is lowercased, false otherwise.
     */
    final public function isLower ():bool {

        return $this->regexMatch('^[[:lower:]]*$');

    }

    /**
     * ### Checks if string is uppercased
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Str::regexMatch() To perform a regular expression match.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->isUpper();
     *
     * // false
     *
     * @throws Error If we could not get current regex encoding.
     *
     * @return bool True if string is uppercased, false otherwise.
     */
    final public function isUpper ():bool {

        return $this->regexMatch('^[[:upper:]]*$');

    }

    /**
     * ### Checks if string is alphabetic
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Char::regexMatch() To perform a regular expression match.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->isAlphabetic();
     *
     * // true
     * ```
     *
     * @throws Error If we could not get current regex encoding.
     *
     * @return bool True if string is alphabetic, false otherwise.
     */
    final public function isAlphabetic ():bool {

        return $this->regexMatch('^[[:alpha:]]*$');

    }

    /**
     * ### Checks if string is alphanumeric
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Char::regexMatch() To perform a regular expression match.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->isAlphanumeric();
     *
     * // true
     * ```
     *
     * @throws Error If we could not get current regex encoding.
     *
     * @return bool True if string is alphanumeric, false otherwise.
     */
    final public function isAlphanumeric ():bool {

        return $this->regexMatch('^[[:alnum:]]*$');

    }

    /**
     * ### Checks if string contains only whitespace characters
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Char::regexMatch() To perform a regular expression match.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->isBlank();
     *
     * // false
     * ```
     *
     * @throws Error If we could not get current regex encoding.
     *
     * @return bool True if string contains only whitespace characters, false otherwise.
     */
    final public function isBlank ():bool {

        return $this->regexMatch('^[[:space:]]*$');

    }

    /**
     * ### Checks if string is hexadecimal
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Str::regexMatch() To perform a regular expression match.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->isHexadecimal();
     *
     * // true
     * ```
     *
     * @throws Error If we could not get current regex encoding.
     *
     * @return bool True if string is hexadecimal, false otherwise.
     */
    final public function isHexadecimal ():bool {

        return $this->regexMatch('^[[:xdigit:]]*$');

    }

    /**
     * ### Checks if string contains only ASCII characters
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::checkEncoding() To check if strings are valid for the specified
     * encoding.
     * @uses \FireHub\Core\Support\Enums\String\Encoding::ASCII As string encoding.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->isASCII();
     *
     * // true
     * ```
     *
     * @return bool True if string is containing only ASCII characters, false otherwise.
     */
    final public function isASCII ():bool {

        return StrMB::checkEncoding($this->string, Encoding::ASCII);

    }

    /**
     * ### Checks if first character of string uppercased
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::toUpper() To make a string uppercase.
     * @uses \FireHub\Core\Support\Str::part() To get part of string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->isCapitalized();
     *
     * // true
     * ```
     *
     * @return bool True if string is capitalized, false otherwise.
     */
    final public function isCapitalized ():bool {

        return $this->part(0, 1)
            === StrMB::toUpper($this->part(0, 1), $this->encoding);

    }

    /**
     * ### Checks if string has lowercase character
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\RegexMB::match() To perform a regular expression match.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->hasLower();
     *
     * // true
     * ```
     *
     * @throws Error If we could not get current regex encoding.
     *
     * @return bool True if string has lowercase character, false otherwise.
     */
    final public function hasLower ():bool {

        return $this->regexMatch('.*[[:lower:]]');

    }

    /**
     * ### Checks if string has uppercase character
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\RegexMB::match() To perform a regular expression match.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->hasUpper();
     *
     * // true
     * ```
     *
     * @throws Error If we could not get current regex encoding.
     *
     * @return bool True if string has uppercase character, false otherwise.
     */
    final public function hasUpper ():bool {

        return $this->regexMatch('.*[[:upper:]]');

    }

    /**
     * ### Checks if string has alphabetic character
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\RegexMB::match() To perform a regular expression match.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->hasAlphabetic();
     *
     * // true
     * ```
     *
     * @throws Error If we could not get current regex encoding.
     *
     * @return bool True if string has alphabetic character, false otherwise.
     */
    final public function hasAlphabetic ():bool {

        return $this->regexMatch('.*[[:alpha:]]');

    }

    /**
     * ### Checks if string has alphanumeric character
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\RegexMB::match() To perform a regular expression match.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->hasAlphanumeric();
     *
     * // true
     * ```
     *
     * @throws Error If we could not get current regex encoding.
     *
     * @return bool True if string has alphanumeric character, false otherwise.
     */
    final public function hasAlphanumeric ():bool {

        return $this->regexMatch('.*[[:alnum:]]');

    }

    /**
     * ### Checks if string has whitespace character
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\RegexMB::match() To perform a regular expression match.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->hasBlank();
     *
     * // false
     * ```
     *
     * @throws Error If we could not get current regex encoding.
     *
     * @return bool True if string has whitespace character, false otherwise.
     */
    final public function hasBlank ():bool {

        return $this->regexMatch('.*[[:space:]]');

    }

    /**
     * ### Checks if string has hexadecimal character
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\RegexMB::match() To perform a regular expression match.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->hasHexadecimal();
     *
     * // true
     * ```
     *
     * @throws Error If we could not get current regex encoding.
     *
     * @return bool True if string has hexadecimal character, false otherwise.
     */
    final public function hasHexadecimal ():bool {

        return $this->regexMatch('.*[[:xdigit:]]');

    }

    /**
     * ### Checks if a string starts with a given value
     *
     * Performs a case-sensitive check indicating if $string begins with $value.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::startsWith() To check if a string starts with a given value.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::toLower() To make a string lowercase.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->startsWith('Fire');
     *
     * // true
     * ```
     *
     * @param non-empty-string $value <p>
     * The value to search for.
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Comparison is case-sensitive or not.
     * </p>
     *
     * @return bool True if string starts with value, false otherwise.
     */
    final public function startsWith (string $value, bool $case_sensitive = true):bool {

        return $case_sensitive
            ? StrMB::startsWith($value, $this->string)
            : StrMB::startsWith(
                StrMB::toLower($value, $this->encoding), // @phpstan-ignore-line
                StrMB::toLower($this->string, $this->encoding)
            );

    }

    /**
     * ### Checks if a string ends with a given value
     *
     * Performs a case-sensitive check indicating if $string ends with $value.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::endsWith() To check if a string ends with a given value.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::toLower() To make a string lowercase.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->endsWith('Hub');
     *
     * // true
     * ```
     *
     * @param non-empty-string $value <p>
     * The value to search for.
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Comparison is case-sensitive or not.
     * </p>
     *
     * @return bool True if string ends with value, false otherwise.
     */
    final public function endsWith (string $value, bool $case_sensitive = true):bool {

        return $case_sensitive
            ? StrMB::endsWith($value, $this->string)
            : StrMB::endsWith(
                StrMB::toLower($value, $this->encoding), // @phpstan-ignore-line
                StrMB::toLower($this->string, $this->encoding)
            );

    }

    /**
     * ### Checks if string contains value
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::contains() To check if a string contains value.
     * @uses \FireHub\Core\Support\LowLevel\StrMB::toLower() To make a string lowercase.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->contains('ire');
     *
     * // true
     * ```
     *
     * @param string $value <p>
     * The value to search for.
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Comparison is case-sensitive or not.
     * </p>
     *
     * @return bool True if a string contains value, false otherwise.
     */
    final public function contains (string $value, bool $case_sensitive = true):bool {

        return $case_sensitive
            ? StrMB::contains($value, $this->string)
            : StrMB::contains(
                StrMB::toLower($value, $this->encoding),
                StrMB::toLower($this->string, $this->encoding)
            );

    }

    /**
     * ### Checks if string contains all values
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::contains() To check if a string contains value.
     * @uses \FireHub\Core\Support\LowLevel\StrMB::toLower() To make a string lowercase.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->containsAll('ire', 'Fi');
     *
     * // true
     * ```
     *
     * @param string[] $values <p>
     * The list of values to search for.
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Comparison is case-sensitive or not.
     * </p>
     *
     * @return bool True if a string contains all values, false otherwise.
     */
    final public function containsAll (array $values, bool $case_sensitive = true):bool {

        foreach ($values as $value) {

            if ($case_sensitive && !StrMB::contains($value, $this->string))
                return false;
            else if (!$case_sensitive && !StrMB::contains(
                StrMB::toLower($value, $this->encoding),
                StrMB::toLower($this->string, $this->encoding)
            )) return false;

        }

        return true;

    }

    /**
     * ### Checks if string contains any of the values
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::contains() To check if a string contains value.
     * @uses \FireHub\Core\Support\LowLevel\StrMB::toLower() To make a string lowercase.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->containsAny('ire', 'Fi');
     *
     * // true
     * ```
     *
     * @param string[] $values <p>
     * The list of values to search for.
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Comparison is case-sensitive or not.
     * </p>
     *
     * @return bool True if a string contains any of the values, false otherwise.
     */
    final public function containsAny (array $values, bool $case_sensitive = true):bool {

        foreach ($values as $value) {

            if ($case_sensitive && StrMB::contains($value, $this->string))
                return true;
            else if (!$case_sensitive && StrMB::contains(
                StrMB::toLower($value, $this->encoding),
                StrMB::toLower($this->string, $this->encoding)
            )) return true;

        }

        return false;

    }

    /**
     * ### Checks if string is equal to any of the values
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::inArray() To check if a value exists in an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->equalToAny('FireHub', 'Web', 'App');
     *
     * // true
     * ```
     *
     * @param string[] $values <p>
     * The list of values to compare to.
     * </p>
     *
     * @return bool True if a string contains any of the values, false otherwise.
     */
    final public function equalToAny (array $values):bool {

        return Arr::inArray($this->string, $values);

    }

    /**
     * ### Make a string lowercase
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::toLower() To make a string lowercase.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->toLowerCase();
     *
     * // firehub
     * ```
     *
     * @return $this This string.
     */
    final public function toLowerCase ():self {

        $this->string = StrMB::toLower($this->string, $this->encoding);

        return $this;

    }

    /**
     * ### Make a string uppercase
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->toUpperCase();
     *
     * // FIREHUB
     * ```
     *
     * @return $this This string.
     */
    final public function toUpperCase ():self {

        $this->string = StrMB::toUpper($this->string, $this->encoding);

        return $this;

    }

    /**
     * ### Make a string title-cased
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::toTitle() To make a string title-cased.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub web app')->toTitleCase();
     *
     * // Firehub Web App
     * ```
     *
     * @return $this This string.
     */
    final public function toTitleCase ():self {

        $this->string = StrMB::toTitle($this->string, $this->encoding);

        return $this;

    }

    /**
     * ### Swap lower and upper cases on string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Str::toChars() To break string into characters.
     * @uses \FireHub\Core\Support\Char::isLower() To check if the character is lowercase.
     * @uses \FireHub\Core\Support\Char::toLower() To make a character lowercase.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->swapCase();
     *
     * // fIREhUB
     * ```
     *
     * @return $this This string.
     */
    final public function swapCase ():self {

        $string = '';
        foreach ($this->toChars() as $char)
            $string .= $char->isLower() ? $char->toUpper() : $char->toLower();

        $this->string = $string;

        return $this;

    }

    /**
     * ### Make a first character of string uppercased
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::toUpper() To make a string uppercase.
     * @uses \FireHub\Core\Support\Str::part() To get part of string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('firehub')->capitalize();
     *
     * // Firehub
     * ```
     *
     * @return $this This string.
     */
    final public function capitalize ():self {

        $this->string = StrMB::toUpper($this->part(0, 1), $this->encoding)
            .$this->part(1);

        return $this;

    }

    /**
     * ### Make a first character of string lowercase
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::toLower() To make a string lowercase.
     * @uses \FireHub\Core\Support\Str::part() To get part of string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('Firehub')->deCapitalize();
     *
     * // firehub
     * ```
     *
     * @return $this This string.
     */
    final public function deCapitalize ():self {

        $this->string = StrMB::toLower($this->part(0, 1), $this->encoding)
            .$this->part(1);

        return $this;

    }

    /**
     * ### Quote string with slashes
     *
     * Backslashes are added before characters that need to be escaped:
     * (single quote, double quote, backslash, NUL).
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::addSlashes() To quote string with slashes.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from("Is your name O'Reilly?")->addSlashes();
     *
     * // Is your name O\'Reilly?
     * ```
     *
     * @return $this This string.
     *
     * @note The addSlashes() is sometimes incorrectly used to try to prevent SQL Injection. Instead, database-specific
     * escaping functions and/or prepared statements should be used.
     */
    final public function addSlashes ():self {

        $this->string = StrMB::addSlashes($this->string);

        return $this;

    }

    /**
     * ### Un-quotes a quoted string
     *
     * Backslashes are removed:
     * (backslashes become single quote, double backslashes are made into a single backslash).
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::stripSlashes() To un-quote a quoted string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('Is your name O\'Reilly?')->stripSlashes();
     *
     * // Is your name O'Reilly?
     * ```
     *
     * @return $this This string.
     *
     * @note stripSlashes() is not recursive.
     * If you want to apply this function to a multidimensional array, you need to use a recursive function.
     *
     * @tip stripSlashes() can be used if you aren't inserting this data into a place (such as a database) that requires
     * escaping. For example, if you're simply outputting data straight from an HTML form.
     */
    final public function stripSlashes ():self {

        $this->string = StrMB::stripSlashes($this->string);

        return $this;

    }

    /**
     * ### Strip HTML and PHP tags from a string
     *
     * This function tries to return a string with all NULL bytes, HTML and PHP tags stripped from a given string. It
     * uses the same tag stripping state machine as the fgetss() function.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::stripTags() To strip HTML and PHP tags from a string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('<p>FireHub</p>')->stripTags();
     *
     * // FireHub
     * ```
     *
     * @param null|string|string[] $allowed_tags <p>
     * You can use the optional second parameter to specify tags which should not be stripped.
     *
     * note: Self-closing XHTML tags are ignored and only non-self-closing tags should be used in allowed_tags.
     * For example, to allow both <br> and <br/>, you should use: <br>
     * </p>
     *
     * @return $this This string.
     */
    final public function stripTags (null|string|array $allowed_tags = null):self {

        $this->string = StrMB::stripTags($this->string, $allowed_tags);

        return $this;

    }

    /**
     * ### Quote meta characters
     *
     * Returns a version of str with a backslash character (\) before every character that is among these:
     * .\+*?[^]($).
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Str::isEmpty() To check if string is empty.
     * @uses \FireHub\Core\Support\LowLevel\StrMB::quoteMeta() To quote meta characters.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub?')->quoteMeta();
     *
     * // FireHub\?
     * ```
     *
     * @return $this This string.
     */
    final public function quoteMeta ():self {

        /** @phpstan-ignore-next-line */
        if (!$this->isEmpty()) $this->string = StrMB::quoteMeta($this->string);

        return $this;

    }

    /**
     * ### Normalize string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\RegexSB::replace() To perform a regular expression search and replace.
     * @uses \FireHub\Core\Support\Str::trim() To strip whitespace (or other characters) from the beginning and end
     * of a string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from(' Fire
     *  Hub ')->normalize();
     *
     * // FireHub
     * ```
     *
     * @return $this This string.
     */
    final public function normalize ():self {

        $this->string = RegexSB::replace('/\s+/',' ', $this->string);

        return $this->trim();

    }

    /**
     * ### Humanize string
     *
     * Capitalizes the first word of the string, replaces underscores with
     * spaces, and strips '_id'.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Str::replaceAll() To replace all occurrences of the search string with replacement
     * string.
     *
     * @return $this This string.
     */
    final public function humanize ():self {

        return $this->replaceAll(['_id', '_'], ['', ' ']);

    }

    /**
     * ### Replace first occurrence of the search string with the replacement string
     *
     * This function returns a string or an array with all occurrences of search
     * in a subject replaced with the given replacement value.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::implode() To join array elements with a string.
     * @uses \FireHub\Core\Support\LowLevel\StrMB::explode() To split a string by a string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->replace('H', 'X');
     *
     * // FireXub
     *
     * @param non-empty-string $search <p>
     * The replacement value that replaces found search values.
     * </p>
     * @param string $replace <p>
     * The string being searched and replaced on.
     * </p>
     *
     * @return $this This string.
     */
    final public function replace (string $search, string $replace):self {

        $this->string = StrMB::implode(
            StrMB::explode($this->string, $search, 2),
            $replace);

        return $this;

    }

    /**
     * ### Replace all occurrences of the search string with the replacement string
     *
     * This function returns a string or an array with all occurrences of search
     * in a subject replaced with the given replacement value.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::replace() To replace all occurrences of the search string with
     * replacement string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->replaceAll('H', 'X');
     *
     * // FireXub
     * ```
     * @example With arguments as an array.
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->replaceAll(['F', 'H'], ['W', 'X']);
     *
     * // WireXub
     * ```
     *
     * @param string|list<string> $search <p>
     * The replacement value that replaces found search values.
     * An array may be used to designate multiple replacements.
     * </p>
     * @param string|list<string> $replace <p>
     * The string being searched and replaced on.
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Searched values are case-sensitive or not.
     * </p>
     *
     * @return $this This string.
     *
     * @note Multibyte characters may not work as expected while $case_sensitive is on.
     *
     * @note Because method replaces left to right, it might replace a previously inserted value when doing
     * multiple replacements.
     *
     * @tip To replace text based on a pattern rather than a fixed string, use preg_replace().
     */
    final public function replaceAll (string|array $search, string|array $replace, bool $case_sensitive = true):self {

        $this->string = StrMB::replace($search, $replace, $this->string, $case_sensitive);

        return $this;

    }

    /**
     * ### Inserts the given string at the specified position
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::part() To get part of string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->insert('s', '4');
     *
     * // FiresHub
     * ```
     *
     * @return $this This string.
     */
    final public function insert (string $character, int $position):self {

        $this->string =
            StrMB::part($this->string, 0, $position, $this->encoding)
            .$character
            .StrMB::part($this->string, $position, encoding: $this->encoding);

        return $this;

    }

    /**
     * ### Removes an arbitrary number of chars starting at a given position
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::part() To get part of string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->remove(2, 3);
     *
     * // Fiub
     * ```
     *
     * @return $this This string.
     */
    final public function remove (int $start, int $length):self {

        $this->string =
            StrMB::part($this->string, 0, $start, $this->encoding)
            .StrMB::part($this->string, $start + $length, encoding: $this->encoding);

        return $this;

    }

    /**
     * ### Repeat a string
     *
     * Returns string repeated $times times.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::repeat() To repeat a string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->repeat(3);
     *
     * // FireHubFireHubFireHub
     * ```
     * @example With custom separator.
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->repeat(3, '-');
     *
     * // FireHub-FireHub-FireHub
     * ```
     *
     * @param positive-int $times <p>
     * Number of time the input string should be repeated.
     * Multiplier has to be greater than or equal to 0. If the multiplier is set to 0,
     * the function will return an empty string.
     * </p>
     * @param string $separator [optional] <p>
     * Separator in between any repeated string.
     * </p>
     *
     * @throws Error If $times argument is not 0 or greater.
     *
     * @return $this This string.
     */
    final public function repeat (int $times, string $separator = ''):self {

        $this->string = StrMB::repeat($this->string, $times, $separator);

        return $this;

    }

    /**
     * ### Reverse order of characters
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::part() To get part of string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->reverse();
     *
     * // buHeriF
     * ```
     *
     * @return $this This string.
     */
    final public function reverse ():self {

        $this->string = StrMB::implode(Arr::reverse($this->split()));

        return $this;

    }

    /**
     * ### Strip whitespace (or other characters) from the beginning and end of a string
     *
     * This function returns a string with whitespace stripped from the beginning and end of string. Without the
     * second parameter, trim() will strip these characters.
     *
     * - " " (ASCII 32 (0x20)), an ordinary space.
     * - "\t" (ASCII 9 (0x09)), a tab.
     * - "\n" (ASCII 10 (0x0A)), a new line (line feed).
     * - "\r" (ASCII 13 (0x0D)), a carriage return.
     * - "\0" (ASCII 0 (0x00)), the NUL-byte.
     * - "\v" (ASCII 11 (0x0B)), a vertical tab.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\Side::BOTH As parameter.
     * @uses \FireHub\Core\Support\LowLevel\StrMB::trim() To strip whitespace (or other characters) from the
     * beginning and end of a string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub\n')->trim();
     *
     * // FireHub
     * ```
     *
     * @param \FireHub\Core\Support\Enums\Side $side [optional] <p>
     * Side to trim string.
     * </p>
     * @param string $characters [optional] <p>
     * The stripped characters can also be specified using the char-list parameter.
     * List all characters that you want to be stripped.
     * With '..', you can specify a range of characters.
     * </p>
     *
     * @return $this This string.
     *
     * @note Because trim() trims characters from the beginning and end of a string, it may be confusing when characters
     * are (or are not) removed from the middle.
     * Trim('abc', 'bad') removes both 'a' and 'b' because it trims 'a'
     * thus moving 'b' to the beginning to also be trimmed. So, this is why it "works" whereas trim('abc', 'b')
     * seemingly does not.
     */
    final public function trim (Side $side = Side::BOTH, string $characters = " \n\r\t\v\x00"):self {

        $this->string = StrMB::trim($this->string, $side, $characters);

        return $this;

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
     * @uses \FireHub\Core\Support\Str::length() To get string length.
     * @uses \FireHub\Core\Support\LowLevel\StrMB::repeat() To repeat a string.
     * @uses \FireHub\Core\Support\LowLevel\Num::floor() To round fractions down.
     * @uses \FireHub\Core\Support\LowLevel\Num::ceil() To round fractions up.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     * use FireHub\Core\Support\Enums\Side;
     *
     * Str::from('FireHub')->pad(10, '_');
     *
     * // ___FireHub
     * ```
     * @example With side argument.
     * ```php
     * use FireHub\Core\Support\Str;
     * use FireHub\Core\Support\Enums\Side;
     *
     * Str::from('FireHub')->pad(10, '-', Side::RIGHT);
     *
     * // FireHub---
     * ```
     *
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
     * @return $this This string.
     */
    final public function pad (int $length, string $pad = " ", Side $side = Side::RIGHT):self {

        $final_length = ($final_length = $length - $this->length()) > 0 ? $final_length : 0;

        $half_length = ($half_length = $final_length / 2) > 0 ? $half_length : 0;

        $this->string = match ($side) {
            Side::LEFT => StrMB::repeat($pad, $final_length)
                .$this->string,
            Side::RIGHT => $this->string.
                StrMB::repeat($pad, $final_length),
            Side::BOTH => StrMB::repeat($pad, Num::floor($half_length)) // @phpstan-ignore-line
                .$this->string
                .StrMB::repeat($pad, Num::ceil($half_length)) // @phpstan-ignore-line
        };

        return $this;

    }

    /**
     * ### Prepends the given string to the current string
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->prepend('Text-');
     *
     * // Text-FireHub
     * ```
     *
     * @param string $string <p>
     * String to append.
     * </p>
     *
     * @return $this This string.
     */
    final public function prepend (string $string):self {

        $this->string = $string.$this->string;

        return $this;

    }

    /**
     * ### Appends the given string to the current string
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->append('-text');
     *
     * // FireHub-text
     * ```
     *
     * @param string $string <p>
     * String to prepend.
     * </p>
     *
     * @return $this This string.
     */
    final public function append (string $string):self {

        $this->string = $this->string.$string;

        return $this;

    }

    /**
     * ### Randomly shuffles a string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::range() To create an array containing a range of elements.
     * @uses \FireHub\Core\Support\LowLevel\Arr::shuffle() To shuffle an array.
     * @uses \FireHub\Core\Support\Str::length() To get string length.
     * @uses \FireHub\Core\Support\Str::part() To get part of string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->shuffle();
     *
     * // rHiuebF
     * ```
     *
     * @return $this This string.
     *
     * @caution This function does not generate cryptographically secure values,
     * and must not be used for cryptographic purposes,
     * or purposes that require returned values to be unguessable.
     */
    final public function shuffle ():self {

        $characters = Arr::range(0, $this->length() - 1);
        Arr::shuffle($characters);

        $string = '';
        foreach ($characters as $character) $string .= $this->part($character, 1);

        $this->string = $string;

        return $this;

    }

    /**
     * ### Makes sure that the current string is prefixed with the given text
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Str::startsWith() To check if a string starts with a given value.
     * @uses \FireHub\Core\Support\Str::prepend() To prepend the given string to the current string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->ensurePrefix('The ');
     *
     * // The FireHub
     * ```
     *
     * @param non-empty-string $prefix <p>
     * The prefix to make sure exists.
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Comparison is case-sensitive or not.
     * </p>
     *
     * @return $this This string.
     */
    final public function ensurePrefix (string $prefix, bool $case_sensitive = true):self {

        return $this->startsWith($prefix, $case_sensitive)
            ? $this
            : $this->prepend($prefix);

    }

    /**
     * ### Makes sure that the current string is suffixed with the given text
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Str::endsWith() To check if a string ends with a given value.
     * @uses \FireHub\Core\Support\Str::append() To append the given string to the current string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->ensureSuffix(' Framework');
     *
     * // FireHub Framework
     * ```
     *
     * @param non-empty-string $suffix <p>
     * The suffix to make sure exists.
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Comparison is case-sensitive or not.
     * </p>
     *
     * @return $this This string.
     */
    final public function ensureSuffix (string $suffix, bool $case_sensitive = true):self {

        return $this->endsWith($suffix, $case_sensitive)
            ? $this
            : $this->append($suffix);

    }

    /**
     * ### String with the prefix $prefix removed, if present
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Str::startsWith() To check if a string starts with a given value.
     * @uses \FireHub\Core\Support\Str::part() To get part of string.
     * @uses \FireHub\Core\Support\LowLevel\StrMB::length() To get string length.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->removePrefix('Fire');
     *
     * // Hub
     * ```
     *
     * @param non-empty-string $prefix <p>
     * The suffix to make sure exists.
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Comparison is case-sensitive or not.
     * </p>
     *
     * @return $this This string.
     */
    final public function removePrefix (string $prefix, bool $case_sensitive = true):self {

        if ($this->startsWith($prefix, $case_sensitive))
            $this->string = $this->part(StrMB::length($prefix, $this->encoding));

        return $this;

    }

    /**
     * ### String with the suffix $suffix removed, if present
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Str::endsWith() To check if a string ends with a given value.
     * @uses \FireHub\Core\Support\Str::part() To get part of string.
     * @uses \FireHub\Core\Support\Str::length() To get string length.
     * @uses \FireHub\Core\Support\LowLevel\StrMB::length() To get string length.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->removeSuffix('Hub');
     *
     * // Fire
     * ```
     *
     * @param non-empty-string $suffix <p>
     * The suffix to make sure exists.
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Comparison is case-sensitive or not.
     * </p>
     *
     * @return $this This string.
     */
    final public function removeSuffix (string $suffix, bool $case_sensitive = true):self {

        if ($this->endsWith($suffix, $case_sensitive))
            $this->string = $this->part(
                0,
                $this->length() - StrMB::length($suffix, $this->encoding)
            );

        return $this;

    }

    /**
     * Surrounds string with the given substring
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::implode() To join array elements with a string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->surround('-');
     *
     * // -FireHub-
     * ```
     *
     * @param string $string <p>
     * The substring to add to both sides.
     * </p>
     *
     * @return $this This string.
     */
    final public function surround (string $string):self {

        $this->string = StrMB::implode([$string, $this->string, $string], '');

        return $this;

    }

    /**
     * ### Tidy string
     *
     * Returns a string with smart quotes, ellipsis characters, and dashes from
     * Windows-1252 (commonly used in Word documents) replaced by their ASCII
     * equivalents.
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('“FireHub”')->length();
     *
     * // "FireHub"
     * ```
     *
     * @throws Error If error while performing a regular expression search and replace.
     * @error\exeption E_WARNING using the "\e" modifier, or If the regex pattern passed does not compile to valid
     * regex.
     *
     * @return $this This string.
     */
    public function tidy ():self {

        $replacements = [
            '...' => '/\x{2026}/u',
            '"' => '/[\x{201C}\x{201D}]/u',
            "'" => '/[\x{2018}\x{2019}]/u',
            '-' => '/[\x{2013}\x{2014}]/u'
        ];

        foreach ($replacements as $replacement => $pattern)
            $this->string = RegexSB::replace($pattern, $replacement, $this->string);

        return $this;

    }

    /**
     * ### Truncate string to a given length
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Str::length() To get string length.
     * @uses \FireHub\Core\Support\LowLevel\StrMB::length() To get string length.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub Web App')->truncate(10, '...');
     *
     * // "FireHub..."
     * ```
     *
     * @param positive-int $length <p>
     * Desired length of the truncated string.
     * </p>
     * @param string $substring [optional] <p>
     * The substring to append if it can fit.
     * </p>
     *
     * @return $this This string.
     */
    public function truncate (int $length, string $substring = ''):self {

        if ($length >= $this->length()) return $this;

        $this->string = $this->part(
            0,
            $length - StrMB::length($substring, $this->encoding)
        ).$substring;

        return $this;

    }

    /**
     * ### Get string length
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->length();
     *
     * // 7
     * ```
     *
     * @return non-negative-int String length.
     */
    final public function length ():int {

        return StrMB::length($this->string, $this->encoding);

    }

    /**
     * ### Get string
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->print();
     *
     * // FireHub
     * ```
     *
     * @return string This string.
     */
    final public function print ():string {

        return $this->string;

    }

    /**
     * ### Get part of string
     *
     * Returns the portion of the string specified by the $start and $length parameters
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::part() To get part of string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->part(3);
     *
     * // eHub
     * ```
     * @example Getting part of string with passed length.
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->part(3, 2);
     *
     * // eH
     * ```
     *
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
     *
     * @return string The portion of string specified by the start and length parameters.
     */
    final public function part (int $start, ?int $length = null):string {

        return StrMB::part($this->string, $start, $length, $this->encoding);

    }

    /**
     * ### Get part of string between provided limits
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Str::part() To get part of string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->between(1, 3);
     *
     * // ir
     * ```
     *
     * @param non-negative-int $start <p>
     * Start index.
     * </p>
     * @param non-negative-int $end<p>
     * End index.
     * </p>
     *
     * @throws Error If start is not greater than an end, or start or end and less than 0.
     *
     * @return string The portion of string specified by the start and end parameters.
     */
    final public function between (int $start, int $end):string {

        if ($start < 0 || $end < 0) throw new Error('Start and end must be greater 0.');

        return $this->part(
            $start,
            ($end = $end - $start) < $start
                ? throw new Error('Start must be greater than end.')
                : $end
        );

    }

    /**
     * ### Get number of times the searched substring occurs in the string
     *
     * Returns the number of times the needle substring occurs in the haystack string.
     * Please note that needle is case-sensitive.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::array() To check if value is an array.
     * @uses \FireHub\Core\Support\LowLevel\StrMB::partCount() To get the number of times the searched substring occurs
     * in the string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub Web App')->partTimes('p');
     *
     * // 2
     * ```
     * @example Using $search argument as an array.
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub Web App')->partTimes(['p', 'W']);
     *
     * // 3
     * ```
     *
     * @param string|list<string> $search <p>
     * The string being found.
     * </p>
     *
     * @return non-negative-int Number of times the searched substring occurs in the string.
     */
    final public function partTimes (string|array $search):int {

        if (DataIs::array($search)) {

            $times = 0;
            foreach ($search as $search_item)
                $times += StrMB::partCount($this->string, $search_item, $this->encoding);

            return $times;

        }

        return StrMB::partCount($this->string, $search, $this->encoding);

    }

    /**
     * ### Find first part of a string
     *
     * Returns part of $string starting from and including the first occurrence of $find to the end of string.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::firstPart() To find the first part of a string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub Web App')->firstPart('Web');
     *
     * // Web App
     * ```
     * @example Using $before_needle parameter.
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub Web App')->firstPart('Web', true);
     *
     * // FireHub
     * ```
     *
     * @param string $find <p>
     * String to find.
     * </p>
     * @param bool $before_needle [optional] <p>
     * If true, return the part of the string before the first occurrence (excluding the find string).
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Comparison is case-sensitive or not.
     * </p>
     *
     * @return string|false The portion of string or false if needle is not found.
     */
    final public function firstPart (string $find, bool $before_needle = false, bool $case_sensitive = true):string|false {

        return StrMB::firstPart(
            $find,
            $this->string,
            $before_needle,
            $case_sensitive,
            $this->encoding
        );

    }

    /**
     * ### Find last part of a string
     *
     * This function returns the portion of $string which starts at the last occurrence of $find and goes until the
     * end of string.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::lastPart() To find the last part of a string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub Web App')->lastPart('Web');
     *
     * // Web App
     * ```
     * @example Using $before_needle parameter.
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub Web App')->lastPart('Web', true);
     *
     * // Firehub
     * ```
     *
     * @param string $find <p>
     * String to find.
     * </p>
     * @param bool $before_needle [optional] <p>
     * If true, return the part of the string before the last occurrence (excluding the find string).
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Comparison is case-sensitive or not.
     * </p>
     *
     * @return string|false The portion of string, or false if needle is not found.
     */
    final public function lastPart (string $find, bool $before_needle = false, bool $case_sensitive = true):string|false {

        return StrMB::lastPart(
            $find,
            $this->string,
            $before_needle,
            $case_sensitive,
            $this->encoding
        );

    }

    /**
     * ### Get first n characters from the string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::part() To get part of string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->first(4);
     *
     * // Fire
     * ```
     *
     * @param positive-int $characters <p>
     * Number of characters.
     *
     * @return string First n characters from the string.
     */
    final public function first (int $characters):string {

        return $this->part(0, $characters);

    }

    /**
     * ### Get last n characters from the string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::part() To get part of string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->last(3);
     *
     * // Hub
     * ```
     *
     * @param positive-int $characters <p>
     * Number of characters.
     *
     * @return string Last n characters from the string.
     */
    final public function last (int $characters):string {

        return $this->part(-$characters);

    }

    /**
     * ### Convert a string to an array
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::split() To convert a string to an array.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->split();
     *
     * // ['F', 'i', 'r', 'e', 'H', 'u', 'b']
     * ```
     * @example Splitting string by custom length.
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->split(3);
     *
     * // ['Fir', 'eHu', 'b']
     * ```
     *
     * @param positive-int $length [optional] <p>
     * Maximum length of the chunk.
     * </p>
     *
     * @throws Error If length is less than.
     *
     * @return list<string> If the optional length parameter is specified,
     * the returned array will be broken down into chunks with each being length in length,
     * except the final chunk which may be shorter if the string does not divide evenly.
     */
    final public function split (int $length = 1):array {

        return StrMB::split($this->string, $length,$this->encoding);

    }

    /**
     * ### Break a string by a string
     *
     * Returns an array of strings, each of which is a substring of string formed by splitting it on boundaries
     * formed by the string separator.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Constants\Number\MAX As the largest integer supported in this build of PHP.
     * @uses \FireHub\Core\Support\LowLevel\StrMB::explode() To split a string by a string.
     *
     * @param non-empty-string $separator <p>
     * The boundary string.
     * </p>
     * @param int<min, max> $limit [optional] <p>
     * If the limit is set and positive, the returned array will contain a maximum of limit elements with the
     * last element containing the rest of the string.
     * If the limit parameter is negative, all components except the last - limit are returned.
     * If the limit parameter is zero, then this is treated as 1.
     * </p>
     *
     * @throws ValueError If separator is an empty string.
     *
     * @return string[] If delimiter contains a value that is not contained in string and a negative limit is
     * used, then an empty array will be returned. For any other limit, an array containing string will be returned.
     */
    final public function break (string $separator, int $limit = MAX):array {

        return StrMB::explode($this->string, $separator, $limit);

    }

    /**
     * ### Group a string into chunks
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Str::split() To convert a string to an array.
     * @uses \FireHub\Core\Support\Str::length() To get string length.
     * @uses \FireHub\Core\Support\LowLevel\Num::ceil() To round fractions up.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->group(3);
     *
     * // ['Fir', 'eHu', 'b']
     * ```
     *
     * @param positive-int $number_of_groups <p>
     * The size of each chunk.
     * </p>
     *
     * @return list<string> Grouped string into chunks.
     */
    final public function group (int $number_of_groups):array {

        return $this->split((
            $size = Num::ceil($this->length() / $number_of_groups)) >= 1 ? $size : 1
        );

    }

    /**
     * ### Get the character at index
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Str::length() To get string length.
     * @uses \FireHub\Core\Support\Char::fromString() To create a new character from raw string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->index(1);
     *
     * // i
     * ```
     *
     * @param non-negative-int $at <p>
     * Character position.
     * </p>
     *
     * @throws Error If a character could not be converted to codepoint,
     * or codepoint could not be converted to character.
     *
     * @return null|\FireHub\Core\Support\Char Character at position or null if position is invalid.
     */
    final public function index (int $at):?Char {

        return $at <= $this->length() - 1 ? Char::fromString($this->string[$at]) : null;

    }

    /**
     * ### Find the position of the first occurrence of a substring in a string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::firstPosition() To find the position of the first occurrence of a
     * substring in a string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->indexOf('i');
     *
     * // 1
     * ```
     *
     * @param string $search <p>
     * A string to find position.
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Search case-sensitive position.
     * </p>
     *
     * @return int|false Numeric position of the first occurrence or false if none exist.
     */
    final public function indexOf (string $search, int $offset = 0, bool $case_sensitive = true):int|false {

        return StrMB::firstPosition($search, $this->string, $case_sensitive, $offset);

    }

    /**
     * ### Find the position of the last occurrence of a substring in a string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::lastPosition() To find the position of the last occurrence of a
     * substring in a string.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('FireHub')->lastIndexOf('i');
     *
     * // 1
     * ```
     *
     * @param string $search <p>
     * A string to find position.
     * </p>
     * @param bool $case_sensitive [optional] <p>
     * Search case-sensitive position.
     * </p>
     *
     * @return int|false Numeric position of the last occurrence or false if none exist.
     */
    final public function lastIndexOf (string $search, int $offset = 0, bool $case_sensitive = true):int|false {

        return StrMB::lastPosition($search, $this->string, $case_sensitive, $offset);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Str::length() To get string length.
     */
    final public function count ():int {

        return $this->length();

    }

    /**
     * ### Break string into characters
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed As return.
     * @uses \FireHub\Core\Support\Collections\Collection::create() To create an array collection type
     * @uses \FireHub\Core\Support\Str::split() To convert a string to an array.
     * @uses \FireHub\Core\Support\Char::fromString() To create a new character from raw string.
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Indexed<\FireHub\Core\Support\Char> Indexed array
     * collection type.
     */
    public function toChars ():Indexed {

        return Collection::create(function ():array {

            foreach ($this->split() as $char)
                $chars[] = Char::fromString($char);

            return $chars ?? [];

        });

    }

    /**
     * ### Break string into lazy characters
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Gen As return.
     * @uses \FireHub\Core\Support\Collections\Collection::lazy() To create a lazy collection type.
     * @uses \FireHub\Core\Support\Str::split() To convert a string to an array.
     * @uses \FireHub\Core\Support\Char::fromString() To create a new character from raw string.
     *
     * @return \FireHub\Core\Support\Collections\Type\Gen<int, \FireHub\Core\Support\Char> Lazy collection
     * type.
     */
    public function toCharsLazy ():Gen {

        return Collection::lazy(function ():Generator {

            foreach ($this->split() as $char)
                yield Char::fromString($char);

        });

    }

    /**
     * ### Perform a regular expression match
     *
     * Searches subject for a match to the regular expression given in a pattern.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\RegexMB::encoding() To set/get character encoding for multibyte regex.
     * @uses \FireHub\Core\Support\LowLevel\RegexMB::match() To perform a regular expression match.
     *
     * @param string $pattern <p>
     * The regular expression pattern.
     * </p>
     *
     * @throws Error If we could not get current regex encoding.
     *
     * @return bool True if string matches the regular expression pattern, false if not.
     */
    final protected function regexMatch (string $pattern):bool {

        $regex_encoding = ($regex_encoding = RegexMB::encoding()) instanceof Encoding
            ? $regex_encoding
            : throw new Error('Could not get current regex encoding.');

        RegexMB::encoding($this->encoding);

        $match = RegexMB::match($pattern, $this->string);

        RegexMB::encoding($regex_encoding);

        return $match;

    }

    /**
     * ### Perform a regular expression search and replace
     *
     * Searches $subject for matches to $pattern and replaces them with $replacement.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\RegexMB::encoding() To set/get character encoding for multibyte regex.
     * @uses \FireHub\Core\Support\LowLevel\RegexMB::replace() To perform a regular expression search and replace.
     *
     * @param string $pattern <p>
     * The regular expression pattern.
     * </p>
     * @param string $replacement <p>
     * The string to replace.
     * </p>
     *
     * @throws Error If string is not valid for the current encoding, or while performing a regular expression search
     * and replace.
     *
     * @return string Replaced string.
     *
     * @warning Never use the e modifier when working on untrusted input. No automatic escaping will happen (as known
     * from preg_replace()). Not taking care of this will most likely create remote code execution vulnerabilities in
     * your application.
     */
    final protected function regexReplace (string $pattern, string $replacement):string {

        $regex_encoding = ($regex_encoding = RegexMB::encoding()) instanceof Encoding
            ? $regex_encoding
            : throw new Error('Could not get current regex encoding.');

        RegexMB::encoding($this->encoding);

        $replace = RegexMB::replace($pattern, $replacement, $this->string);

        RegexMB::encoding($regex_encoding);

        return $replace;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * echo Str::from('FireHub');
     *
     * // FireHub
     * ```
     */
    final public function __toString ():string {

        return $this->string;

    }

}