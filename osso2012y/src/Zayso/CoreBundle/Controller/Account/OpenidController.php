<?php
namespace Zayso\CoreBundle\Controller\Account;

use Zayso\CoreBundle\Controller\BaseController as CoreBaseController;

use Symfony\Component\HttpFoundation\Request;

/* ====================================================================
 * NatGames
 */
class OpenidController extends CoreBaseController
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
        die('identifier ' . $profile['identifier']);
        
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
        $manager = $this->get('zayso_core.account.manager');
        
        $account = $manager->loadAccountWithEverything($this->getProjectId(),$this->getUser()->getAccountId());
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
        
        $tplData['janrain_token_route'] = 'zayso_core_account_openid_add_rpx';
        
        return $this->renderx('ZaysoCoreBundle:Account\Openid:add.html.twig',$tplData);
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
