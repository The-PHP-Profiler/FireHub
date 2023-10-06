<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package
 *
 * This file contains all system definitions.
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

namespace FireHub\Core\Support\Constants\PHP;

use const PHP_DEBUG;
use const PHP_FD_SETSIZE;
use const PHP_MAJOR_VERSION;
use const PHP_MINOR_VERSION;
use const PHP_OS;
use const PHP_OS_FAMILY;
use const PHP_RELEASE_VERSION;
use const PHP_SAPI;
use const PHP_SHLIB_SUFFIX;
use const PHP_VERSION;
use const PHP_VERSION_ID;
use const PHP_ZTS;

/**
 * ### The current PHP version as a string in "major.minor.release[extra]" notation
 * @since 1.0.0
 *
 * @var string \FireHub\Core\Support\Constants\PHP\VERSION
 *
 * @api
 */
const VERSION = PHP_VERSION;

/**
 * ### The current PHP version as an integer, useful for version comparisons (e.g., int(50207) from version "5.2.7-extra")
 * @since 1.0.0
 *
 * @var int \FireHub\Core\Support\Constants\PHP\VERSION_ID
 *
 * @api
 */
const VERSION_ID = PHP_VERSION_ID;

/**
 * ### TThe current PHP "major" version as an integer (e.g., int(5) from version "5.2.7-extra")
 * @since 1.0.0
 *
 * @var int \FireHub\Core\Support\Constants\PHP\VERSION_MAJOR
 *
 * @api
 */
const VERSION_MAJOR = PHP_MAJOR_VERSION;

/**
 * ### The current PHP "minor" version as an integer (e.g., int(2) from version "5.2.7-extra")
 * @since 1.0.0
 *
 * @var int \FireHub\Core\Support\Constants\PHP\VERSION_MINOR
 *
 * @api
 */
const VERSION_MINOR = PHP_MINOR_VERSION;

/**
 * ### The current PHP "release" version as an integer (e.g., int(7) from version "5.2.7-extra")
 * @since 1.0.0
 *
 * @var int \FireHub\Core\Support\Constants\PHP\VERSION_RELEASE
 *
 * @api
 */
const VERSION_RELEASE = PHP_RELEASE_VERSION;

/**
 * ### Whether Zend Thread Safety (ZTS) is enabled or not
 * @since 1.0.0
 *
 * @var int \FireHub\Core\Support\Constants\PHP\PHP_ZTS
 *
 * @api
 */
const THREAT_SAFE = PHP_ZTS;

/**
 * ### Whether PHP binary was compiled in a debug configuration
 * @since 1.0.0
 *
 * @var int \FireHub\Core\Support\Constants\PHP\PHP_DEBUG
 *
 * @api
 */
const DEBUG = PHP_DEBUG;

/**
 * ### The operating system PHP was built for
 * @since 1.0.0
 *
 * @var string \FireHub\Core\Support\Constants\PHP\PHP_OS
 *
 * @api
 */
const OS = PHP_OS;

/**
 * ### The operating system family PHP was built for. One of 'Windows', 'BSD', 'Darwin', 'Solaris', 'Linux' or 'Unknown'
 * @since 1.0.0
 *
 * @var string \FireHub\Core\Support\Constants\PHP\OS_FAMILY
 *
 * @api
 */
const OS_FAMILY = PHP_OS_FAMILY;

/**
 * ### The Server API for this build of PHP
 * @since 1.0.0
 *
 * @var string \FireHub\Core\Support\Constants\PHP\SAPI
 *
 * @api
 */
const SAPI = PHP_SAPI;

/**
 * ### The build-platform's shared library suffix, such as "so" (most Unixes) or "dll" (Windows)
 * @since 1.0.0
 *
 * @var string \FireHub\Core\Support\Constants\PHP\SHLIB_SUFFIX
 *
 * @api
 */
const SHLIB_SUFFIX = PHP_SHLIB_SUFFIX;

/**
 * ### The maximum number of file descriptors for select system calls
 * @since 1.0.0
 *
 * @var string \FireHub\Core\Support\Constants\PHP\FD_SETSIZE
 *
 * @api
 */
const FD_SETSIZE = PHP_FD_SETSIZE;