<?php

namespace Zayso\Osso2007Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Zayso\Osso2007Bundle\Component\Debug;
use Zayso\Osso2007Bundle\FrontEnd\FrontCont;

class WelcomeController extends BaseController
{
    public function welcomeAction()
    {
        return new Response('Welcome Osso2007');
    }
    public function legacyAction()
    {
        $config = $GLOBALS['config'];
        $ws = $config['ws'];

        $configx = require $ws . 'osso2010/model/config/config_' . $config['web_host'] . '.php';
        $config  = array_merge($config,$configx);

        $configx = require $ws . 'osso2010/apps/Osso2007/FrontEnd/config.php';
        $config  = array_merge($config,$configx);

        $fc = new FrontCont($config);
        $fc->execute(); // Actually exits for 2010

        // Returns for 2007
        exit();
        die('legacy');
        Debug::dump($fc); die();
        return new Response('Hello Osso2007 world!');
    }
}
