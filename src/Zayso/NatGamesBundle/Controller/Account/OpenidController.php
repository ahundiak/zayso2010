<?php

namespace Zayso\NatGamesBundle\Controller\Account;

use Zayso\NatGamesBundle\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;

class OpenidController extends BaseController
{
    public function addRpxAction(Request $request)
    {
        // Load the profile
        $profile = $this->get('zayso_core.openid.rpx')->getProfile();
        if (!is_array($profile))
        {
            $request->getSession()->setFlash('openid_add_error',$profile);
            return $this->redirect($this->generateUrl('zayso_core_account_openid_add'));
        }
        // Maybe move all of this to account manager

        // See if already have one
        $accountManager = $this->getAccountManager();
        $openid = $accountManager->getOpenidForIdentifier($profile['identifier']);
        if ($openid) return $this->redirect($this->generateUrl('zayso_core_account_openid_add'));

        // Make one
        $em = $this->getAccountManager()->getEntityManager();
        $openid = $accountManager->newOpenid($profile);

        $user = $this->getUser();
        $accountPersonId = $user->getAccountPersonId();
        $accountPerson = $em->getReference('ZaysoCoreBundle:AccountPerson',$accountPersonId);
        $openid->setAccountPerson($accountPerson);

        $em->persist($openid);
        $em->flush();

        return $this->redirect($this->generateUrl('zayso_core_account_openid_add'));
    }
    // Move to account manager?
    protected function addOpenId($profile)
    {
        // Make sure identifier is not already in use
        $accountManager = $this->getAccountManager();
        $em = $this->getAccountManager()->getEntityManager();

        $openid = $accountManager->getOpenidForIdentifier($profile->get('identifier'));
        if ($openid) return;

        $openid = $accountManager->newOpenid($profile);

        $user = $this->getUser();
        $accountPersonId = $user->getAccountPersonId();
        $accountPerson = $em->getReference('ZaysoCoreBundle:AccountPerson',$accountPersonId);

        $openid->setAccountPerson($accountPerson);
      //$openid->setPersonId       ($user->getPersonId());

        $em->persist($openid);
        $em->flush();
    }
    public function addAction(Request $request)
    {
        if ($request->getMethod() == 'POST') return $this->addPostAction($request);

        $openids = $this->getAccountManager()->getOpenidsForAccount($this->getUser()->getAccountId());

        $tplData = $this->getTplData();
        $tplData['error'] = $request->getSession()->getFlash('openid_add_error');
        $tplData['openids'] = $openids;
        
        return $this->render('ZaysoNatGamesBundle:Account\Openid:add.html.twig',$tplData);
   }
    public function addPostAction(Request $request)
    {
        $info = $request->request->get('openids');
        if (isset($info['remove']))
        {
            foreach($info['remove'] as $id)
            {
                $this->getAccountManager()->deleteOpenid($id);
            }
        }
        return $this->redirect($this->generateUrl('zayso_core_account_openid_add'));

    }
}
