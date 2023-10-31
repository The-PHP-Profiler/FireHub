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

use FireHub\Core\Support\Collections\ {
    Collectable, Collection, Type\Arr\Indexed, Type\Gen
};
use FireHub\Core\Support\Enums\ {
    Side, String\Encoding
};
use FireHub\Core\Support\LowLevel\ {
    Arr, RegexSB, StrMB
};
use Error, Generator;

/**
 * ### Sentence class
 *
 * Class allows you to manipulate sentence in various ways.
 * @since 1.0.0
 *
 * @api
 */
final class Sentence extends aString {

    /**
     * ### Create sentence from words
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collectable As parameter.
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     *
     * @param \FireHub\Core\Support\Collections\Collectable<array-key, \FireHub\Core\Support\String\Word> $words <p>
     * Collection of sentences.
     * </p>
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     *
     * @throws Error If collection parameter is not list of words.
     *
     * @return self
     */
    public static function fromWords (Collectable $words, Encoding $encoding = null):self {

        $string = '';
        foreach ($words as $word) {

            $word instanceof Word
                ? $string .= $word->string.' '
                : throw new Error('Collection parameter must be list of words.');

        }

        return new self($string, $encoding);

    }

    /**
     * ### Break sentences into words
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed As return.
     * @uses \FireHub\Core\Support\Collections\Collection::create() To create an array collection type
     * @uses \FireHub\Core\Support\String\Sentence::removeDot() To make sure that the sentence string doesn't have a
     * dot at the end.
     * @uses \FireHub\Core\Support\String\Sentence::break() To break a string by a string.
     * @uses \FireHub\Core\Support\String\Word::from() To create a new string from raw string.
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Indexed<\FireHub\Core\Support\String\Word> Indexed array
     * collection type.
     */
    public function toWords ():Indexed {

        $this->removeDot();

        return Collection::create(function ():array {

            foreach ($this->break(' ') as $word)
                $words[] = Word::from($word);

            return $words ?? [];

        });

    }

    /**
     * ### Break sentences into lazy words
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Gen As return.
     * @uses \FireHub\Core\Support\Collections\Collection::lazy() To create a lazy collection type.
     * @uses \FireHub\Core\Support\String\Sentence::break() To break a string by a string.
     * @uses \FireHub\Core\Support\String\Word::from() To create a new string from raw string.
     *
     * @return \FireHub\Core\Support\Collections\Type\Gen<int, \FireHub\Core\Support\String\Word> Lazy collection
     * type.
     */
    public function toWordsLazy ():Gen {

        return Collection::lazy(function ():Generator {

            foreach ($this->break(' ') as $word)
                yield Word::from($word);

        });

    }

    /**
     * ### Makes a PascalCase version of the string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\RegexSB::replaceFunc() To perform a regular expression search and replace.
     * @uses \FireHub\Core\Support\LowLevel\RegexSB::replaceFunc() To perform a regular expression search and replace
     * using a callback.
     * @uses \FireHub\Core\Support\LowLevel\StrMB::toUpper() To make a string uppercase.
     * @uses \FireHub\Core\Support\String\Word::from() To create a new string from raw string.
     * @uses \FireHub\Core\Support\String\Word::capitalize() To make a first character of string lowercased.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\String\Sentence;
     *
     * Sentence::('Firehub web app')->pascalize();
     *
     * // FirehubWebApp
     * ```
     *
     * @return \FireHub\Core\Support\String\Word Word with this string.
     */
    public function pascalize ():Word {

        $this->string = RegexSB::replace('/^[-_]+/','', $this->string);

        $this->string = RegexSB::replaceFunc(
            '/[-_\s]+(.)?/u',
            fn($match):string => isset($match[1])
                ? StrMB::toUpper($match[1], $this->encoding)
                : '',
            $this->string
        );

        $this->string = RegexSB::replaceFunc(
            '/\d+(.)?/u',
            fn($match):string => StrMB::toUpper($match[0], $this->encoding),
            $this->string
        );

        return Word::from($this->string, $this->encoding)->capitalize();

    }

    /**
     * ### Makes a camelCase version of the string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\String\Sentence::pascalize() To make a PascalCase version of the string.
     * @uses \FireHub\Core\Support\String\Word::deCapitalize() To make a first character of string lowercased.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\String\Sentence;
     *
     * Sentence::('Firehub web app')->camelize();
     *
     * // firehubWebApp
     * ```
     *
     * @return \FireHub\Core\Support\String\Word Word with this string.
     */
    public function camelize ():Word {

        return $this->pascalize()->deCapitalize();

    }

    /**
     * ### Makes a snake_case version of the string
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\String\Sentence::pascalize() To make a PascalCase version of the string.
     * @uses \FireHub\Core\Support\String\Word::delimit() To lowercase string separated by the given delimiter.
     * @uses \FireHub\Core\Support\String\Word As return.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\String\Sentence;
     *
     * Sentence::('Firehub web app')->snakify();
     *
     * // firehub_web_app
     * ```
     *
     * @return \FireHub\Core\Support\String\Word Word with this string.
     */
    public function snakify ():Word {

        return $this->pascalize()->delimit('_');

    }

    /**
     * ### Makes string with the first letter of each word capitalized
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\String\Sentence::toLowerCase() To make a string lowercased.
     * @uses \FireHub\Core\Support\String\Sentence::toWords() To break sentences into words.
     * @uses \FireHub\Core\Support\String\Word::equalToAny() To check if string is equal to any of the values.
     * @uses \FireHub\Core\Support\String\Word::toTitleCase() To make a string title-cased.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed::map() To apply the callback to each collection item.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed::join() To join join method joins collection items
     * with a string
     *
     * @example
     * ```php
     * use FireHub\Core\Support\String\Sentence;
     *
     * Sentence::('Firehub the web app')->titleize();
     *
     * // Firehub the Web App
     * ```
     *
     * @param non-empty-string[] $ignore [optional] <p>
     * List of words not to be capitalized.
     * </p>
     *
     * $this This string.
     */
    public function titleize (array $ignore = ['at', 'by', 'for', 'in', 'of', 'on', 'out', 'to', 'the']):self {

        $this->string = $this->toLowerCase()->toWords()
            ->map(fn(Word $value) =>
            !$value->equalToAny($ignore)
                ? $value->toTitleCase()
                : $value->string
            )->join(' ');

        return $this;

    }

    /**
     * ### Makes sure that the sentence string has dot at the end
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\String\Sentence::ensureSuffix() To make sure that the current string is suffixed
     * with the given text.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('The FireHub')->ensureDot();
     *
     * // The FireHub.
     * ```
     *
     * @return $this This string.
     */
    public function ensureDot ():self {

        return $this->ensureSuffix('.');

    }

    /**
     * ### Makes sure that the sentence string doesn't have a dot at the end
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\String\Sentence::trim() To strip whitespace (or other characters) from the beginning
     * and the end of a string.
     * @uses \FireHub\Core\Support\Enums\Side::RIGHT As side enum.
     *
     * @example
     * ```php
     * use FireHub\Core\Support\Str;
     *
     * Str::from('The FireHub.')->ensureDot();
     *
     * // The FireHub
     * ```
     *
     * @return $this This string.
     */
    public function removeDot ():self {

        return $this->trim(Side::RIGHT, '.');

    }

}