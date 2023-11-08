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
    BaseStatic, MasterStatic
};
use FireHub\Core\Support\LowLevel\Folder;
use Phar;

use const FireHub\Core\Support\Constants\Path\DS;

/**
 * ### Path support class
 *
 * Give info about various paths inside the FireHub framework.
 * @since 1.0.0
 *
 * @api
 */
final class Path implements MasterStatic {

    /**
     * ### FireHub base static class trait
     * @since 1.0.0
     */
    use BaseStatic;

    /**
     * ### Get FireHub core path
     * @since 1.0.0
     *
     * @return string FireHub core path.
     */
    public static function core ():string {

        return Phar::running();

    }

    /**
     * ### Get FireHub project path
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Folder::parent() To return a parent folder path.
     * @uses \FireHub\Core\Support\Constants\Path\DS As system definition for separating folders, platform specific.
     *
     * @return string FireHub project path.
     */
    public static function project ():string {

        /** @phpstan-ignore-next-line argument for parent is not empty */
        return Folder::parent(Phar::running(false)).DS.'..'.DS.'..'.DS.'..'.DS.'..';

    }

}