<?php

// scoper-autoload.php @generated by PhpScoper

$loader = require_once __DIR__.'/autoload.php';

// Aliases for the whitelisted classes. For more information see:
// https://github.com/humbug/php-scoper/blob/master/README.md#class-whitelisting
if (!class_exists('ComposerAutoloaderInit01b3ee01228c3a90a4e7d07b481e3903', false) && !interface_exists('ComposerAutoloaderInit01b3ee01228c3a90a4e7d07b481e3903', false) && !trait_exists('ComposerAutoloaderInit01b3ee01228c3a90a4e7d07b481e3903', false)) {
    spl_autoload_call('WPSentry\ScopedVendor\ComposerAutoloaderInit01b3ee01228c3a90a4e7d07b481e3903');
}

// Functions whitelisting. For more information see:
// https://github.com/humbug/php-scoper/blob/master/README.md#functions-whitelisting
if (!function_exists('includeIfExists')) {
    function includeIfExists() {
        return \WPSentry\ScopedVendor\includeIfExists(...func_get_args());
    }
}
if (!function_exists('composerRequire01b3ee01228c3a90a4e7d07b481e3903')) {
    function composerRequire01b3ee01228c3a90a4e7d07b481e3903() {
        return \WPSentry\ScopedVendor\composerRequire01b3ee01228c3a90a4e7d07b481e3903(...func_get_args());
    }
}

return $loader;
