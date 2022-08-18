<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit1ce8b561f23a5e9c6e980547df88fec0
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit1ce8b561f23a5e9c6e980547df88fec0', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit1ce8b561f23a5e9c6e980547df88fec0', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        \Composer\Autoload\ComposerStaticInit1ce8b561f23a5e9c6e980547df88fec0::getInitializer($loader)();

        $loader->register(true);

        return $loader;
    }
}
