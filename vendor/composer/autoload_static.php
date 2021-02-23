<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbf996e3ad9a30e86087a0bcb6e72a564
{
    public static $prefixLengthsPsr4 = array (
        'r' => 
        array (
            'rame0\\API\\Yandex\\Tests\\' => 23,
            'rame0\\API\\Yandex\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'rame0\\API\\Yandex\\Tests\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests/API/Yandex',
        ),
        'rame0\\API\\Yandex\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib/API/Yandex',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbf996e3ad9a30e86087a0bcb6e72a564::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbf996e3ad9a30e86087a0bcb6e72a564::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}