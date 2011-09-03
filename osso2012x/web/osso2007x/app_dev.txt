<?php

// this check prevents access to debug front controllers that are deployed by accident to production servers.
// feel free to remove this, extend it, or make something more sophisticated.
if (!in_array(@$_SERVER['REMOTE_ADDR'], array(
    '127.0.0.1',
    '::1',
))) {
    header('HTTP/1.0 403 Forbidden');
    die('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}
$config = array
(
  'ws'        => '/home/impd/zayso2012/',
  'web_host'  => 'willow',
  'web_tools' => 'tools',  // Not being used but probably should be
);

require_once __DIR__.'/../../apps/osso2007/bootstrap.php.cache';
require_once __DIR__.'/../../apps/osso2007/AppKernel.php';

use Symfony\Component\HttpFoundation\Request;

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$kernel->handle(Request::createFromGlobals())->send();
