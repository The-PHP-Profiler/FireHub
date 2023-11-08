<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2023 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @package FireHub\Core\Kernel
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Kernel\HTTP;

use FireHub\Core\Initializers\Kernel as BaseKernel;

/**
 * ### HTTP Kernel
 *
 * Process HTTP requests that come in through various sources
 * and give a client appropriate response.
 * @since 1.0.0
 */
final class Kernel extends BaseKernel {

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function runtime ():string {

        return 'HTTP Torch';

    }

}