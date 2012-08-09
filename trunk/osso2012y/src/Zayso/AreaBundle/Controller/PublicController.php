<?php
namespace Zayso\AreaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Zayso\CoreBundle\Controller\PublicController as CorePublicController;

class PublicController extends CorePublicController
{
    public function indexAction(Request $request)
    {
        $manager = $this->get('zayso_core.account.manager');
        $account = $manager->newAccount();
        
        $signinFormType = $this->get('zayso_core.account.signin.formtype');
        $signinForm = $this->createForm($signinFormType, $account);
        
        $tplData = array();
        $tplData['account']             = $account;
        $tplData['signinForm']          = $signinForm->createView();
        $tplData['janrain_token_route'] = 'zayso_core_account_signin_rpx';  //$this->container->getParameter('zayso_core.openid.route');
        
        return $this->renderx('Public:index.html.twig',$tplData);
    }
}
