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
use FireHub\Core\Support\Contracts\Stringable;
use FireHub\Core\Support\Enums\String\Encoding;
use FireHub\Core\Support\LowLevel\ {
    CharMB, RegexMB, StrMB
};
use Error;

/**
 * ### Character high-level class
 *
 * Class allows you to manipulate characters in various ways.
 * @since 1.0.0
 */
final class Char implements Master, Stringable {

    /**
     * ### FireHub base class trait
     * @since 1.0.0
     */
    use Base;

    /**
     * ### Character to use
     * @since 1.0.0
     *
     * @var string
     */
    private string $character;

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\CharMB::chr() To generate character from codepoint value.
     *
     * @param int $codepoint <p>
     * Codepoint to use.
     * </p>
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     *
     * @throws Error If codepoint could not be converted to character.
     *
     * @return void
     *
     * @link https://en.wikipedia.org/wiki/List_of_Unicode_characters List of codepoint values
     */
    private function __construct (
        private int $codepoint,
        private ?Encoding $encoding = null
    ) {

        $this->character = CharMB::chr($codepoint, $this->encoding);

        /** @phpstan-ignore-next-line */
        $this->encoding = $encoding ?? StrMB::encoding();

    }

    /**
     * ### Create a new character from raw codepoint
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Char;
     *
     * Char::from(70);
     *
     * // F
     * ```
     *
     * @param int $codepoint <p>
     * Character to use.
     * </p>
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     *
     * @throws Error If codepoint could not be converted to character.
     *
     * @return self New character.
     */
    public static function from (int $codepoint, Encoding $encoding = null):self {

        return new self($codepoint, $encoding);

    }

    /**
     * ### Create a new character from raw string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\CharMB::chr() To generate character from codepoint value.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Char;
     *
     * Char::fromString('F');
     * ```
     *
     * @param string $string <p>
     * String to use.
     * </p>
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     *
     * @throws Error If a character could not be converted to codepoint,
     * or codepoint could not be converted to character.
     *
     * @return self New string from codepoint raw string.
     */
    public static function fromString (string $string, Encoding $encoding = null):self {

        return new self(CharMB::ord($string, $encoding), $encoding);

    }

    /**
     * ### Checks if character is lowercase
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\RegexMB::match() To perform a regular expression match.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Char;
     *
     * Char::from('F')->isLower();
     *
     * // false
     * ```
     *
     * @return bool True if character is lowercase, false otherwise.
     */
    public function isLower ():bool {

        return RegexMB::match('.*[[:lower:]]', $this->character);

    }

    /**
     * ### Checks if character is uppercase
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\RegexMB::match() To perform a regular expression match.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Char;
     *
     * Char::from('F')->isLower();
     *
     * // false
     * ```
     *
     * @return bool True if character is uppercase, false otherwise.
     */
    public function isUpper ():bool {

        return RegexMB::match('.*[[:upper:]]', $this->character);

    }

    /**
     * ### Checks if character is alphabetic
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\RegexMB::match() To perform a regular expression match.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Char;
     *
     * Char::from('F')->isAlpha();
     *
     * // true
     * ```
     *
     * @return bool True if character is alphabetic, false otherwise.
     */
    public function isAlpha ():bool {

        return RegexMB::match('.*[[:alpha:]]', $this->character);

    }

    /**
     * ### Checks if character is whitespace
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\RegexMB::match() To perform a regular expression match.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Char;
     *
     * Char::from('F')->isBlank();
     *
     * // false
     * ```
     *
     * @return bool True if character is whitespace, false otherwise.
     */
    public function isBlank ():bool {

        return RegexMB::match('.*[[:space:]]', $this->character);

    }

    /**
     * ### Checks if character is hexadecimal
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\RegexMB::match() To perform a regular expression match.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Char;
     *
     * Char::from('F')->isHexadecimal();
     *
     * // true
     * ```
     *
     * @return bool True if character is hexadecimal, false otherwise.
     */
    public function isHexadecimal ():bool {

        return RegexMB::match('.*[[:xdigit:]]', $this->character);

    }

    /**
     * ### Checks if character is ASCII
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::checkEncoding() To check if strings are valid for the specified
     * encoding.
     * @uses \FireHub\Core\Support\Enums\String\Encoding::ASCII As string encoding.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Char;
     *
     * Char::from('F')->isASCII();
     *
     * // true
     * ```
     *
     * @return bool True if character is ASCII, false otherwise.
     */
    public function isASCII ():bool {

        return StrMB::checkEncoding($this->character, Encoding::ASCII);

    }

    /**
     * ### Make a character lowercase
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::toLower() To make a string lowercase.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Char;
     *
     * Char::fromString('F')->toLower();
     *
     * // f
     * ```
     *
     * @return $this This character.
     */
    public function toLower ():self {

        $this->character = StrMB::toLower($this->character, $this->encoding);

        $this->codepoint = CharMB::ord($this->character);

        return $this;

    }

    /**
     * ### Make a character uppercase
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\StrMB::toUpper() To make a string uppercase.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Char;
     *
     * Char::fromString('f')->toUpper();
     *
     * // F
     * ```
     *
     * @return $this This character.
     */
    public function toUpper ():self {

        $this->character = StrMB::toUpper($this->character, $this->encoding);

        $this->codepoint = CharMB::ord($this->character);

        return $this;

    }

    /**
     * ### Get character as raw string
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Char;
     *
     * Char::from(70)->asString();
     *
     * // F
     * ```
     *
     * @return string Character as string.
     */
    public function asString ():string {

        return $this->character;

    }

    /**
     * ### Get character as codepoint
     * @since 1.0.0
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Char;
     *
     * Char::fromString('F')->asCodepoint();
     *
     * // 70
     * ```
     *
     * @return int Character as codepoint.
     */
    public function asCodepoint ():int {

        return $this->codepoint;

    }

    /**
     * @inheritDoc
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Char;
     *
     * echo Char::from(70);
     *
     * // F
     * ```
     */
    public function __toString ():string {

        return $this->character;

    }

}