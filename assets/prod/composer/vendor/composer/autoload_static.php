<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd6cbdcc39ef2865cb5a14e8befbc738b
{
    public static $files = array (
        'a5f882d89ab791a139cd2d37e50cdd80' => __DIR__ . '/..' . '/tgmpa/tgm-plugin-activation/class-tgm-plugin-activation.php',
    );

    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'CumulusTheme\\Vendors\\vena\\AcfJson\\' => 34,
            'CumulusTheme\\Vendors\\WPTRT\\Customize\\Control\\' => 45,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'CumulusTheme\\Vendors\\vena\\AcfJson\\' => 
        array (
            0 => __DIR__ . '/..' . '/vena/acf-json/src',
        ),
        'CumulusTheme\\Vendors\\WPTRT\\Customize\\Control\\' => 
        array (
            0 => __DIR__ . '/..' . '/wptrt/control-color-alpha/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'CumulusTheme\\Vendors\\WPTRT\\Customize\\Control\\ColorAlpha' => __DIR__ . '/..' . '/wptrt/control-color-alpha/src/ColorAlpha.php',
        'CumulusTheme\\Vendors\\vena\\AcfJson\\Loader' => __DIR__ . '/..' . '/vena/acf-json/src/Loader.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd6cbdcc39ef2865cb5a14e8befbc738b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd6cbdcc39ef2865cb5a14e8befbc738b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitd6cbdcc39ef2865cb5a14e8befbc738b::$classMap;

        }, null, ClassLoader::class);
    }
}
