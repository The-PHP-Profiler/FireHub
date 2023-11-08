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
 * ### Predefined datetime format enum
 * @since 1.0.0
 */
enum Predefined:string implements Format {

    /**
     * ### FireHub base enum class trait
     * @since 1.0.0
     */
    use BaseEnum;

    /**
     * ### Date only format
     * @since 1.0.0
     *
     * @example
     * ```php
     * 2022-12-09
     * ```
     */
    case DATE = '!Y-m-d';

    /**
     * ### Time only format
     * @since 1.0.0
     *
     * @example
     * ```php
     * 08:53:18
     * ```
     */
    case TIME = 'H:i:s';

    /**
     * ### Time only format with microseconds
     * @since 1.0.0
     *
     * @example
     * ```php
     * 08:53:56.844337
     * ```
     */
    case MICRO_TIME = 'H:i:s.u';

    /**
     * ### Date and time format
     * @since 1.0.0
     *
     * @example
     * ```php
     * 2022-12-09 08:55:00
     * ```
     */
    case DATETIME = 'Y-m-d H:i:s';

    /**
     * ### Date and time format with microseconds
     * @since 1.0.0
     *
     * @example
     * ```php
     * 2022-12-09 08:55:33.641682
     * ```
     */
    case DATE_MICRO_TIME = 'Y-m-d H:i:s.u';

    /**
     * ### ATOM
     * @since 1.0.0
     *
     * @example
     * ```php
     * 2022-12-09T08:58:56+01:00
     * ```
     */
    case ATOM = 'Y-m-d\TH:i:sP';

    /**
     * ### ATOM_EXTENDED
     * @since 1.0.0
     *
     * @example
     * ```php
     * 2022-12-09T08:58:45.038+01:00
     * ```
     */
    case ATOM_EXTENDED = 'Y-m-d\TH:i:s.vP';

    /**
     * ### COOKIE
     * @since 1.0.0
     *
     * @example
     * ```php
     * Friday, 09-Dec-2022 08:58:31 CET
     * ```
     */
    case COOKIE = 'l, d-M-Y H:i:s T';

    /**
     * ### ISO8601
     * @since 1.0.0
     *
     * @example
     * ```php
     * 2022-12-09T08:58:18+0100
     * ```
     */
    case ISO8601 = 'Y-m-d\TH:i:sO';

    /**
     * ### ISO8601_EXPANDED
     * @since 1.0.0
     *
     * @example
     * ```php
     * X-12-09T08:58:03+01:00
     * ```
     */
    case ISO8601_EXPANDED = 'X-m-d\TH:i:sP';

    /**
     * ### RFC822
     * @since 1.0.0
     *
     * @example
     * ```php
     * Fri, 09 Dec 22 08:57:30 +0100
     * ```
     */
    case RFC822 = 'D, d M y H:i:s O';

    /**
     * ### RFC850
     * @since 1.0.0
     *
     * @example
     * ```php
     * Friday, 09-Dec-22 08:57:46 CET
     * ```
     */
    case RFC850 = 'l, d-M-y H:i:s T';

    /**
     * ### RFC7231
     * @since 1.0.0
     *
     * @example
     * ```php
     * Fri, 09 Dec 2022 08:56:35 GMT
     * ```
     */
    case RFC7231 = 'D, d M Y H:i:s \G\M\T';

    /**
     * ### RSS
     * @since 1.0.0
     *
     * @example
     * ```php
     * Fri, 09 Dec 2022 08:56:11 +0100
     * ```
     */
    case RSS = 'D, d M Y H:i:s O';

}