<?php

namespace Zayso\CoreBundle\Controller\Account;

use Zayso\CoreBundle\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;

/* ====================================================================
 * NatGames
 */
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
        // See if have one
        $manager = $this->get('zayso_core.account.home.manager');
        
        $openid = $manager->loadOpenidForIdentifier($profile['identifier']);
        if ($openid) return $this->redirect($this->generateUrl('zayso_core_account_openid_add'));

        // Add it
        $manager->addOpenidToAccount($this->getUser()->getAccountId(),$profile);
        $manager->flush();
        
        return $this->redirect($this->generateUrl('zayso_core_account_openid_add'));
    }
    public function addAction(Request $request, $id = 0)
    {
        $manager = $this->get('zayso_core.account.home.manager');
        
        $account = $manager->loadAccountWithPersons($this->getProjectId(),$this->getUser()->getAccountId());
        if (!$account)
        {
            return $this->redirect($this->generateUrl('zayso_core_home'));
        }
        if ($id && ($account->getId() != $id)) 
        {
             return $this->redirect($this->generateUrl('zayso_core_home'));           
        }
        if ($request->getMethod() == 'POST') return $this->deleteAction($request);

        $tplData = array();
        $tplData['account'] = $account;
        $tplData['error']   = $request->getSession()->getFlash('openid_add_error');
        
        return $this->renderx('Account\Openid:add.html.twig',$tplData);
   }
    public function deleteAction(Request $request)
    {
        $info = $request->request->get('openids');
        if (isset($info['remove']))
        {
            $manager = $this->get('zayso_core.account.home.manager');
            foreach($info['remove'] as $id)
            {
                $manager->deleteOpenid($id);
            }
            $manager->flush();
        }
        return $this->redirect($this->generateUrl('zayso_core_account_openid_add'));

    }
}
