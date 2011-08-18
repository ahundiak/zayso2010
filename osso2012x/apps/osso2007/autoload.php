<?php

use Symfony\Component\ClassLoader\UniversalClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;


$ws = '/home/ahundiak/zayso2012/';
$ws = __DIR__ . '/../../../';

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'          => array($ws.'Symfony/vendor/symfony/src', $ws.'Symfony/vendor/bundles'),
    'Doctrine'         => $ws.'doctrine-orm',

    'Sensio'           => $ws.'Symfony/vendor/bundles',
    'JMS'              => $ws.'Symfony/vendor/bundles',

    'Monolog'          => $ws.'Symfony/vendor/monolog/src',  // Some sort of logging package
    'Assetic'          => $ws.'Symfony/vendor/assetic/src',  // Asset management (js, css etc)
    'Metadata'         => $ws.'Symfony/vendor/metadata/src', // Not sure
));
$loader->registerPrefixes(array(
    'Twig_Extensions_' => $ws . 'Symfony/vendor/twig-extensions/lib',
    'Twig_'            => $ws . 'Symfony/vendor/twig/lib',
    
    'Osso2007_'        => $ws . 'osso2010/apps',
    'Proj_'            => $ws . 'osso2007/osso2007/library',
    'Cerad_'           => $ws . 'Cerad/library',
    'Zend_'            => $ws . 'ZendFramework-1.0.0/library',
));

// intl
if (!function_exists('intl_get_error_code')) {
    require_once $ws.'Symfony/vendor/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->registerPrefixFallbacks(array($ws.'Symfony/vendor/symfony/src/Symfony/Component/Locale/Resources/stubs'));
}

$loader->registerNamespaceFallbacks(array(
    $ws.'osso2012x/src',
));
$loader->register();

AnnotationRegistry::registerLoader(function($class) use ($loader) {
    $loader->loadClass ($class);
    return class_exists($class, false);
});
AnnotationRegistry::registerFile($ws.'doctrine-orm/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');

// Swiftmailer needs a special autoloader to allow
// the lazy loading of the init file (which is expensive)
require_once $ws.'Symfony/vendor/swiftmailer/lib/classes/Swift.php';
Swift::registerAutoload($ws.'Symfony/vendor/swiftmailer/lib/swift_init.php');

// unset($ws);
/* ------------------------------------------
 * A very simple routines for automatically loading classes
 * Replaces _ with / and tack on .php
 *
 * Mostly needed for s few legacy non-namespaced classes the need to be shared
 * Between the old and the system
 */
class Cerad_Loader
{
    public static function loadClass($className)
    {
        // See if already loaded
        if (class_exists    ($className, false) ||
            interface_exists($className, false))
        {
            return;
        }
        // Simple path calculation
        $path = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        include $path;
    }
    public static function registerAutoload()
    {
        spl_autoload_register(array('Cerad_Loader', 'loadClass'));
    }
}
ini_set('include_path', '.' .
        PATH_SEPARATOR . $ws . 'osso2012x/src/Zayso/Osso2007Bundle/Component' .
        PATH_SEPARATOR . $ws . 'osso2007/osso2007/data'
);
Osso2007_Loader::registerAutoload();
Cerad_Loader::registerAutoLoad();