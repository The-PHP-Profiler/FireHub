<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package
 *
 * This file contains all data functions.
 * @since 1.0.0
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2023 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @package FireHub\Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\Helpers\PHP;

use const FireHub\Core\Support\Constants\Number\SIZE;

/**
 * ### Check if using 64bit version of PHP
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\Constants\Number\SIZE To get size of an integer in bytes in this build of PHP.
 *
 * @return bool True if using 64bit version of PHP, otherwise false.
 *
 * @api
 */
function is64bit ():bool {

    return SIZE === 8;

}

/**
 * ### Check if using 32bit version of PHP
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\Helpers\PHP\is64bit() To check if using 64bit version of PHP.
 *
 * @return bool True if using 32bit version of PHP, otherwise false.
 *
 * @api
 */
function is32bit ():bool {

    return !is64bit();

}