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

namespace FireHub\Core\Support\Enums\DateTime\Unit;

use FireHub\Core\Base\MasterEnum;
use BackedEnum;

/**
 * ### Interface for unit enums
 * @since 1.0.0
 */
interface Unit extends MasterEnum, BackedEnum {

    /**
     * ### Singular of enum case
     * @since 1.0.0
     *
     * @return non-empty-string Singular.
     */
    public function singular ():string;

    /**
     * ### Plural of enum case
     * @since 1.0.0
     *
     * @return non-empty-string Plural.
     */
    public function plural ():string;

}