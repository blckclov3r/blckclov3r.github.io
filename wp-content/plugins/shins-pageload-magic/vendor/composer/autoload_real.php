<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit5661cdfed9258ca975c2f103a8a30970
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\AutoloadShinSenterDeferWordpress\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\AutoloadShinSenterDeferWordpress\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit5661cdfed9258ca975c2f103a8a30970', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\AutoloadShinSenterDeferWordpress\ClassLoader(\dirname(\dirname(__FILE__)));
        spl_autoload_unregister(array('ComposerAutoloaderInit5661cdfed9258ca975c2f103a8a30970', 'loadClassLoader'));

        $useStaticLoader = PHP_VERSION_ID >= 50600 && !defined('HHVM_VERSION') && (!function_exists('zend_loader_file_encoded') || !zend_loader_file_encoded());
        if ($useStaticLoader) {
            require __DIR__ . '/autoload_static.php';

            call_user_func(\Composer\Autoload\ComposerStaticInit5661cdfed9258ca975c2f103a8a30970::getInitializer($loader));
        } else {
            $classMap = require __DIR__ . '/autoload_classmap.php';
            if ($classMap) {
                $loader->addClassMap($classMap);
            }
        }

        $loader->setClassMapAuthoritative(true);
        $loader->register(true);

        if ($useStaticLoader) {
            $includeFiles = Composer\Autoload\ComposerStaticInit5661cdfed9258ca975c2f103a8a30970::$files;
        } else {
            $includeFiles = require __DIR__ . '/autoload_files.php';
        }
        foreach ($includeFiles as $fileIdentifier => $file) {
            composerRequire5661cdfed9258ca975c2f103a8a30970($fileIdentifier, $file);
        }

        return $loader;
    }
}

function composerRequire5661cdfed9258ca975c2f103a8a30970($fileIdentifier, $file)
{
    if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
        require $file;

        $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;
    }
}