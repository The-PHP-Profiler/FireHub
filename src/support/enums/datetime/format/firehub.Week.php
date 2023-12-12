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

namespace FireHub\Core\Support\Enums\DateTime\Format;

use FireHub\Core\Base\BaseEnum;

/**
 * ### Week format enum
 * @since 1.0.0
 */
enum Week:string implements Format {

    /**
     * ### FireHub base enum class trait
     * @since 1.0.0
     */
    use BaseEnum;

    /**
     * ### ISO 8601 week number of year, weeks starting on Monday
     * @since 1.0.0
     *
     * @example
     * ```php
     * 42 (the 42nd week in the year)
     * ```
     */
    case NUMBER = 'W';

}