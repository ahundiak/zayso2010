<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = require __DIR__.'/../../../Symfony-2.1/vendor/autoload.php';

$loader->add('Zayso', __DIR__.'/../../src');

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../../../Symfony-2.1/vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->add('', __DIR__.'/../../../Symfony-2.1/vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs');
}

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
