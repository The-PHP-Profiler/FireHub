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
use FireHub\Core\Support\Enums\String\Encoding;
use Error, Generator;

/**
 * ### Text class
 *
 * Class allows you to manipulate text in various ways.
 * @since 1.0.0
 *
 * @api
 */
final class Text extends aString {

    /**
     * ### Create text from sentences
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collectable As parameter.
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     * @uses \FireHub\Core\Support\String\Sentence::ensureDot() To make sure that the sentence string has dot at an end.
     *
     * @param \FireHub\Core\Support\Collections\Collectable<array-key, \FireHub\Core\Support\String\Sentence> $sentences <p>
     * Collection of sentences.
     * </p>
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding. If it is null, the internal character encoding value will be used.
     * </p>
     *
     * @throws Error If collection parameter is not list of sentences.
     *
     * @return self
     */
    public static function fromSentences (Collectable $sentences, Encoding $encoding = null):self {

        $string = '';
        foreach ($sentences as $sentence) {

            $sentence instanceof Sentence
                ? $string .= $sentence->ensureDot()->string.' '
                : throw new Error('Collection parameter must be list of sentences.');

        }

        return new self($string, $encoding);

    }

    /**
     * ### Break text into sentences
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed As return.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Indexed::filter() To filter items from a collection.
     * @uses \FireHub\Core\Support\Collections\Collection::create() To create an array collection type
     * @uses \FireHub\Core\Support\String\Text::break() To break a string by a string.
     * @uses \FireHub\Core\Support\String\Sentence::from() To create a new string from raw string.
     *
     * @return \FireHub\Core\Support\Collections\Type\Arr\Indexed<\FireHub\Core\Support\String\Sentence> Indexed array
     * collection type.
     */
    public function toSentences ():Indexed {

        return Collection::create(function ():array {

            foreach ($this->break('.') as $sentence)
                $sentences[] = Sentence::from($sentence);

            return $sentences ?? [];

        })
            ->filter(fn(Sentence $value) => !empty($value->string));

    }

    /**
     * ### Break text into lazy sentences
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Type\Gen As return.
     * @uses \FireHub\Core\Support\Collections\Type\Gen::filter() To filter items from a collection.
     * @uses \FireHub\Core\Support\Collections\Collection::lazy() To create a lazy collection type.
     * @uses \FireHub\Core\Support\String\Text::break() To break a string by a string.
     * @uses \FireHub\Core\Support\String\Sentence::from() To create a new string from raw string.
     *
     * @return \FireHub\Core\Support\Collections\Type\Gen<int, \FireHub\Core\Support\String\Sentence> Lazy collection
     * type.
     */
    public function toSentencesLazy ():Gen {

        return Collection::lazy(function ():Generator {

            foreach ($this->break('.') as $sentence)
                yield Sentence::from($sentence);

        })
            ->filter(fn(Sentence $value) => !empty($value->string));

    }

}