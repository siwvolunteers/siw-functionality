<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbc97e779d1630e91d8d3b4042c956e2d
{
    public static $files = array (
        '21330799d40d10ecb9ec9375884bd45d' => __DIR__ . '/..' . '/donut/array_dig/array_dig.php',
        '6168384990757e5fc380d01d460961a8' => __DIR__ . '/..' . '/kallookoo/wp_parse_args_recursive/src/wp-parse-args-recursive.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Spatie\\Enum\\' => 12,
            'Spatie\\ArrayToXml\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Spatie\\Enum\\' => 
        array (
            0 => __DIR__ . '/..' . '/spatie/enum/src',
        ),
        'Spatie\\ArrayToXml\\' => 
        array (
            0 => __DIR__ . '/..' . '/spatie/array-to-xml/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'M' => 
        array (
            'Mustache' => 
            array (
                0 => __DIR__ . '/..' . '/mustache/mustache/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'WP_Async_Request' => __DIR__ . '/..' . '/deliciousbrains/wp-background-processing/classes/wp-async-request.php',
        'WP_Background_Process' => __DIR__ . '/..' . '/deliciousbrains/wp-background-processing/classes/wp-background-process.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbc97e779d1630e91d8d3b4042c956e2d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbc97e779d1630e91d8d3b4042c956e2d::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitbc97e779d1630e91d8d3b4042c956e2d::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitbc97e779d1630e91d8d3b4042c956e2d::$classMap;

        }, null, ClassLoader::class);
    }
}
