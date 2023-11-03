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

namespace FireHub\Core\Support\Enums\DateTime;

use FireHub\Core\Base\ {
    BaseEnum, MasterEnum
};

/**
 * ### Epoch timestamp enum
 * @since 1.0.0
 */
enum Epoch:string implements MasterEnum {

    /**
     * ### FireHub base enum class trait
     * @since 1.0.0
     */
    use BaseEnum;

    /**
     * ### Min Epoch
     * @since 1.0.0
     */
    case START = '0001-01-01 00:00:00';

    /**
     * ### Max Epoch
     * @since 1.0.0
     */
    case END = '9999-12-31 23:59:59';

    /**
     * ### Unix Epoch
     *
     * Unix Epoch used in POSIX time, used by Unix and Unix-like systems (Linux, macOS, Android),
     * and programming languages: most C/C++ implementations,[32] Java, JavaScript, Perl, PHP, Python, Ruby, Tcl, ActionScript.
     * Also used by Precision Time Protocol.
     * @since 1.0.0
     */
    case UNIX = '1970-01-01 00:00:00';

}