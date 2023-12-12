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
use FireHub\Core\Support\Collections\ {
    Collection, Type\Arr\Associative, Type\Arr\Multidimensional
};
use Error;

/**
 * ### Register static record list
 * @since 1.0.0
 *
 * @api
 */
final class Register implements Master {

    /**
     * ### FireHub base class trait
     * @since 1.0.0
     */
    use Base;

    /**
     * ### Register instance
     * @since 1.0.0
     *
     * @var self
     */
    private static self $instance;

    /**
     * ### Register lists
     * @since 1.0.0
     *
     * @var \FireHub\Core\Support\Collections\Type\Arr\Associative<non-empty-lowercase-string,
     *     \FireHub\Core\Support\Collections\Type\Arr\Associative<non-empty-lowercase-string,
     * mixed>|\FireHub\Core\Support\Collections\Type\Arr\Multidimensional<non-empty-lowercase-string,
     * array<array-key, mixed>>>
     */
    private Associative $lists;

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Collections\Collection::create() As array collection type.
     *
     * @return void
     */
    private function __construct () {

        $this->lists = Collection::create()->associative(fn():array => []);

    }

    /**
     * ### Gets current register instance
     * @since 1.0.0
     *
     * @return $this Register instance.
     */
    private static function instance ():self {

        if (!isset(self::$instance)) self::$instance = new self;

        return self::$instance;

    }

    /**
     * ### Get or set list from register
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Register::$instance To get current register instance.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative As return.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative::exist() To check if an item exists in a collection.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative::add() To add item to a collection.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative::get() To get item from a collection.
     * @uses \FireHub\Core\Support\Collections\Collection::create() As array collection type.
     * @uses \FireHub\Core\Support\Str::from() To create a new string from raw string.
     * @uses \FireHub\Core\Support\Str::toLowerCase() To make a string lowercase.
     * @uses \FireHub\Core\Support\Str::length() To get string length.
     * @uses \FireHub\Core\Support\Str::isASCII() To check if string contains only ASCII characters.
     * @uses \FireHub\Core\Support\Str::print() To get string.
     *
     * @param non-empty-string $name <p>
     * List name.
     * </p>
     * @param null|'associative'|'multidimensional' $type <p>
     * List name.
     * </p>
     *
     * @throws Error If a list is not at least three characters long, a list is not in ASCII format or list type
     * doesn't exist.
     *
     * @return (
     *  $type is null
     *  ? \FireHub\Core\Support\Collections\Type\Arr\Associative<non-empty-lowercase-string,
     *     mixed>|\FireHub\Core\Support\Collections\Type\Arr\Multidimensional<non-empty-lowercase-string,
     *     array<array-key, mixed>>
     *  : ($type is 'associative'
     *      ? \FireHub\Core\Support\Collections\Type\Arr\Associative<non-empty-lowercase-string, mixed>
     *      : ($type is 'multidimensional'
     *          ? \FireHub\Core\Support\Collections\Type\Arr\Multidimensional<non-empty-lowercase-string, array<array-key, mixed>>
     *          : never-return))
     * ) Register list.
     */
    public static function list (string $name, string $type = null):Associative|Multidimensional {

        $name = Str::from($name)->toLowerCase();

        if ($name->length() < 3)
            throw new Error('List name must be at least three characters long.');

        if (!$name->isASCII())
            throw new Error('List name must in ASCII format.');

        $lists = self::instance()->lists;

        $name = $name->print();

        if (empty($name)) throw new Error('List name cannot be empty.');

        if (!$lists->exist($name)) {

            $lists->add(
                match ($type) {
                    'associative' => Collection::create()->associative(fn():array => []),
                    'multidimensional' => Collection::create()->multidimensional(fn():array => []),
                    default => throw new Error("List type $type doesn't exist.")
                },
                $name
            );

            return self::list($name);

        }

        return $lists->get($name);

    }

    /**
     * ### Delete list from register
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Register::$instance To get current register instance.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative::exist() To check if an item exists in a collection.
     * @uses \FireHub\Core\Support\Collections\Type\Arr\Associative::remove() To get item from a collection.
     * @uses \FireHub\Core\Support\Str::from() To create a new string from raw string.
     * @uses \FireHub\Core\Support\Str::toLowerCase() To make a string lowercase.
     * @uses \FireHub\Core\Support\Str::print() To get string.
     *
     * @param non-empty-string $name <p>
     * List name.
     * </p>
     *
     * @throws Error If a list doesn't exist.
     *
     * @return void
     */
    public static function delete (string $name):void {

        $list = self::instance()->lists;

        $list->exist($name)
            ? $list->remove(Str::from($name)->toLowerCase()->print()) //@phpstan-ignore-line Checked with $name->length() < 3
            : throw new Error("List $name doesn't exist.");

    }

}