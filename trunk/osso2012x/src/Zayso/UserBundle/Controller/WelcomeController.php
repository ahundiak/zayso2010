<?php

namespace Zayso\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    public function indexAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $tplData = array();

        if (is_object($user)) $tplData['userClass'] = get_class($user);
        else                  $tplData['userClass'] = 'UC ' . $user; // anon if not signed in

        if (is_object($user)) $tplData['userName'] = $user->getUsername();
        else                  $tplData['userName'] = 'Guest';
        
        return $this->render('UserBundle:Welcome:index.html.twig',$tplData);
    }
}
