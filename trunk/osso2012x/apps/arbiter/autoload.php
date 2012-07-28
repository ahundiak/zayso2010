<?php

use Symfony\Component\ClassLoader\UniversalClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;


$ws = '/home/ahundiak/zayso2012/';
$ws = __DIR__ . '/../../../';

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'          => array($ws.'Symfony-2.0/vendor/symfony/src', $ws.'Symfony-2.0/vendor/bundles'),

    'Sensio'           => $ws.'Symfony-2.0/vendor/bundles',
    'JMS'              => $ws.'Symfony-2.0/vendor/bundles',
    
    'Doctrine\\Common' => $ws.'Symfony-2.0/vendor/doctrine-common/lib',
    'Doctrine\\DBAL'   => $ws.'Symfony-2.0/vendor/doctrine-dbal/lib',
    'Doctrine'         => $ws.'Symfony-2.0/vendor/doctrine/lib',

    'Monolog'          => $ws.'Symfony-2.0/vendor/monolog/src',  // Some sort of logging package
    'Assetic'          => $ws.'Symfony-2.0/vendor/assetic/src',  // Asset management (js, css etc)
    'Metadata'         => $ws.'Symfony-2.0/vendor/metadata/src', // Not sure
));
$loader->registerPrefixes(array(
    'Twig_Extensions_' => $ws.'Symfony-2.0/vendor/twig-extensions/lib',
    'Twig_'            => $ws.'Symfony-2.0/vendor/twig/lib',
    'Zend_'            => $ws.'ZendFramework-1.11.11/library',
    'PHPExcel'         => $ws.'PHPExcel/Classes'
));

// intl
if (!function_exists('intl_get_error_code')) {
    require_once $ws.'Symfony-2.0/vendor/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->registerPrefixFallbacks(array($ws.'Symfony-2.0/vendor/symfony/src/Symfony/Component/Locale/Resources/stubs'));
}

$loader->registerNamespaceFallbacks(array(
    $ws.'osso2012x/src',
));
$loader->register();

AnnotationRegistry::registerLoader(function($class) use ($loader) {
    $loader->loadClass ($class);
    return class_exists($class, false);
});
AnnotationRegistry::registerFile($ws.'Symfony-2.0/vendor/doctrine/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');

// Swiftmailer needs a special autoloader to allow
// the lazy loading of the init file (which is expensive)
require_once $ws.'Symfony-2.0/vendor/swiftmailer/lib/classes/Swift.php';
Swift::registerAutoload($ws.'Symfony-2.0/vendor/swiftmailer/lib/swift_init.php');

ini_set('include_path','.' .

    // Needed for Zend because it uses includes
    PATH_SEPARATOR . $ws . 'ZendFramework-1.11.11/library'
);

unset($ws);