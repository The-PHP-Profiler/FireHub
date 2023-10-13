<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2023 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @package FireHub\Core
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core;

use FireHub\Core\Initializers\Autoload;
use FireHub\Core\Initializers\Kernel as BaseKernel;
use FireHub\Core\Initializers\Enums\Kernel;
use FireHub\Core\Support\Path;
use FireHub\Core\Support\LowLevel\ {
    Arr, StrSB
};
use DirectoryIterator, Error, Throwable;

use const FireHub\Core\Support\Constants\Path\DS;
use const DIRECTORY_SEPARATOR;

use function array_pop;
use function array_shift;
use function explode;
use function implode;
use function strtolower;

/**
 * ### Main FireHub class for bootstrapping
 *
 * This class contains all system definitions, constants and dependant
 * components for FireHub bootstrapping.
 * @since 1.0.0
 */
final class FireHub {

    /**
     * ### List of classes for preloading
     * @var class-string[]
     */
    private array $preloaders = [
        \FireHub\Core\Support\Contracts\Callables::class,
        \FireHub\Core\Support\Contracts\Cloneable::class,
        \FireHub\Core\Support\Contracts\JsonSerializable::class,
        \FireHub\Core\Support\Contracts\Overloadable::class,
        \FireHub\Core\Support\Contracts\Serializable::class,
        \FireHub\Core\Base\Traits\UnCallables::class,
        \FireHub\Core\Base\Traits\UnCloneable::class,
        \FireHub\Core\Base\Traits\UnJsonSerializable::class,
        \FireHub\Core\Base\Traits\UnOverloadable::class,
        \FireHub\Core\Base\Traits\UnSerializable::class,
        \FireHub\Core\Base\Prime::class,
        \FireHub\Core\Base\Master::class,
        \FireHub\Core\Base\MasterStatic::class,
        \FireHub\Core\Base\MasterEnum::class,
        \FireHub\Core\Base\Base::class,
        \FireHub\Core\Base\BaseStatic::class,
        \FireHub\Core\Base\BaseEnum::class,
        \FireHub\Core\Support\LowLevel\Arr::class,
        \FireHub\Core\Support\LowLevel\DataIs::class,
        \FireHub\Core\Support\LowLevel\FileSystem::class,
        \FireHub\Core\Support\LowLevel\File::class,
        \FireHub\Core\Support\LowLevel\SPLAutoload::class,
        \FireHub\Core\Support\LowLevel\StrSafe::class,
        \FireHub\Core\Support\LowLevel\StrSB::class,
    ];

    /**
     * ### Constructor
     *
     * Prevents instantiation of the main class.
     * @since 1.0.0
     *
     * @return void
     */
    private function __construct() {}

    /**
     * ### Light the torch
     *
     * This methode serves for instantiating the FireHub framework.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Initializers\Enums\Kernel As parameter.
     * @uses \FireHub\Core\Initializers\Enums\Kernel::run() To run selected Kernel.
     * @uses \FireHub\Core\Firehub::bootloaders() To initialize bootloaders.
     * @uses \FireHub\Core\Firehub::kernel() To process Kernel.
     *
     * @param \FireHub\Core\Initializers\Enums\Kernel $kernel <p>
     * Pick Kernel from Kernel enum, process your
     * request and return the appropriate response.
     * </p>
     *
     * @throws Error If a system cannot load Autoload file, cannot preload class, or cannot load constant files.
     * @error\exeption E_WARNING if a system cannot load Autoload file, cannot preload class,
     * or cannot load constant files.
     *
     * @return string Response from Kernel.
     */
    public static function boot (Kernel $kernel):string {

        return (new self())
            ->bootloaders()
            ->kernel($kernel->run());

    }

    /**
     * ### Initialize bootloaders
     *
     * Load series of bootloaders required to boot FireHub framework.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\FireHub::preload() ) To load preloader classes.
     * @uses \FireHub\Core\Firehub::autoload() To load autoloader.
     *
     * @throws Error If a system cannot load Autoload file, cannot preload class, or cannot load constant files.
     * @error\exeption E_WARNING if a system cannot load Autoload file, cannot preload class,
     * or cannot load constant files.
     *
     * @return $this This object.
     */
    private function bootloaders ():self {

        return $this
            ->registerConstants()
            ->preload()
            ->autoload();

    }

    /**
     * ### Register init definitions
     *
     * This method will scan Initializers\Constants folder
     * and automatically include all PHP files.
     * @since 1.0.0
     *
     * @throws Error If system cannot load constant files.
     *
     * @return $this This object.
     */
    private function registerConstants ():self {

        try {

            foreach (new DirectoryIterator(
                __DIR__
                .DIRECTORY_SEPARATOR
                .implode(DIRECTORY_SEPARATOR, ['support', 'constants']))
                     as $file)
                if ($file->isFile() && $file->getExtension() === 'php') include $file->getPathname();

        } catch (Throwable) {

            throw new Error('Cannot load constant files, please contact your administrator');

        }

        return $this;

    }

    /**
     * ### Load preloader classes
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Initializers\Autoload::include() To manually include a list of classes.
     * @uses \FireHub\Core\Support\Constants\Path\DS As system definition for separating folders, platform specific.
     *
     * @throws Error If a system cannot load Autoload file, or cannot preload class.
     * @error\exeption E_WARNING if a system cannot load Autoload file, or cannot preload class.
     *
     * @return $this This object.
     */
    private function preload ():self {

        if (!include(__DIR__.DS.'initializers'.DS.'firehub.Autoload.php'))
            throw new Error('Cannot load Autoload file, please contact your administrator.');

        // register FireHub preloaders
        Autoload::include($this->preloaders, function (string $class):string {
            $class_components = explode(DS, $class);

            array_shift($class_components);
            array_shift($class_components);

            $classname = array_pop($class_components);
            $namespace = strtolower(implode(DS, $class_components));

            return __DIR__.DS.$namespace.DS.'firehub.'.$classname.'.php';
        });

        return $this;

    }

    /**
     * ### Load autoloader
     *
     * This method contains definitions and series of functions needed for calling classes.
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Constants\Path\DS To separate folders.
     * @uses \FireHub\Core\Initializers\Autoload::register() To register new autoload implementation.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::explode() To split a string by a string.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::implode() To join array elements with a string.
     * @uses \FireHub\Core\Support\LowLevel\Arr::firstKey() To get the first key from an array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::shift() To remove an item at the beginning of an array.
     * @uses \FireHub\Core\Support\LowLevel\StrSB::toLower() To make a string lowercase.
     * @uses \FireHub\Core\Support\Path::core() To get FireHub core path.
     *
     * @return $this This object.
     */
    private function autoload ():self {

        // register FireHub main autoloader
        Autoload::register('FireHub', function (string $namespace, string $classname):string|false {
            $namespace = StrSB::explode($namespace, DS);

            if ($namespace[Arr::firstKey($namespace)] !== 'firehub') return false;
            Arr::shift($namespace);

            if ($namespace[Arr::firstKey($namespace)] !== 'core') return false;
            Arr::shift($namespace); // @phpstan-ignore-line

            $namespace = StrSB::implode($namespace, DS);

            $classname_component = StrSB::explode($classname, '_');
            $classname = Arr::shift($classname_component);
            $classname_component = StrSB::implode($classname_component, '_');
            $suffix = !empty($classname_component) ? StrSB::toLower('.'.$classname_component) : '';

            return Path::core().DS.$namespace.DS.'firehub.'.$classname.$suffix.'.php';
        });

        return $this;

    }

    /**
     * ### Process Kernel
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Initializers\Kernel As parameter.
     * @uses \FireHub\Core\Kernel\HTTP\Kernel::runtime() To handle client runtime.
     *
     * @param \FireHub\Core\Initializers\Kernel $kernel <p>
     * Picked Kernel from Kernel enum, process your
     * request and return the appropriate response.
     * </p>
     *
     * @return string Response from Kernel.
     */
    private function kernel (BaseKernel $kernel):string {

        return $kernel->runtime();

    }

}