<?php
namespace Zayso\ArbiterBundle\Controller\Tourn;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Zayso\CoreBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\CoreBundle\Component\DataTransformer\UssfidTransformer;

class KicksController extends TournController
{
    protected $tournName = 'Kicks';
       
    public function signupAction(Request $request)
    {
        $msg = null;
        
        $formType = new TournForm();
        $referee  = new TournOfficial();
        
        $form = $this->createForm($formType, $referee);
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $this->trap($referee);
                
                //die($referee->notes);
                // return $this->redirect($this->generateUrl('zayso_natgames_home'));
                //$this->sendEmail($referee);
                //$msg = 'Application Submitted';
              //$msg = $this->csv($referee);
            }
            //die('Not valid???');
        }
        
        $tplData = array();
        $tplData['form'] = $form->createView();
        $tplData['msg']  = $msg;

        return $this->render('ZaysoArbiterBundle:Tourn\Kicks:offline.html.twig',$tplData);
        return $this->render('ZaysoArbiterBundle:Tourn\Kicks:form.html.twig',$tplData);
    }
    public function signupActionTest(Request $request)
    {
        $form = array(
            'firstName' => array('name' => 'firstName', 'type' => 'text', 'label' => 'First Name'),
            'lastName'  => array('name' => 'lastName',  'type' => 'text', 'label' => 'Last Name'),
        );
        $tplData = array();
        $tplData['form'] = $form;
        return $this->render('ZaysoArbiterBundle:Tourn\Kicks:form.html.twig',$tplData);
    }
}
?>
