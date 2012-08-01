<?php

namespace Zayso\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Zayso\CoreBundle\Component\Debug;

use Zayso\CoreBundle\Controller\BaseController as CoreBaseController;

class PublicController extends CoreBaseController
{
    /* =============================================================
     * Need to rsearch on how to override these sorts of templates
     * Or if it is even worth doing this sort of thing
     */
    public function deniedAction()
    { 
        $tplData = array();
        return $this->renderx('ZaysoCoreBundle:Public:denied.html.twig',$tplData);
    }
    public function denied2Action()
    { 
        $tplData = array();
        return $this->renderx('ZaysoCoreBundle:Public:denied2.html.twig',$tplData);
    }
    
    public function textalertsAction()
    { 
        $tplData = array();
        return $this->renderx('Welcome:textalerts.html.twig',$tplData);
    }
    public function contactAction()
    { 
        $tplData = array();
        return $this->renderx('Welcome:contact.html.twig',$tplData);
    }
    public function scheduleAction()
    { 
        $tplData = array();
        return $this->renderx('Schedule:schedule.html.twig',$tplData);
    }
    public function shuttleAction()
    { 
        $tplData = array();
        return $this->renderx('Welcome:shuttle.html.twig',$tplData);
    }
    public function championsAction()
    { 
        $tplData = array();
        return $this->renderx('Welcome:champions.html.twig',$tplData);
    }
    public function offlineAction()
    { 
        $tplData = array();
        return $this->renderx('Welcome:offline.html.twig',$tplData);
    }
}
