<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7cf3ad60d6b1f1e79d7998dcf857ca93
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'CustomPaymentsModule\\CustomPaymentMethod' => __DIR__ . '/../..' . '/classes/CustomPaymentMethod.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit7cf3ad60d6b1f1e79d7998dcf857ca93::$classMap;

        }, null, ClassLoader::class);
    }
}
