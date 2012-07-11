<?php
namespace Zayso\ArbiterBundle\Controller\Tourn;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

use Zayso\CoreBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\CoreBundle\Component\DataTransformer\UssfidTransformer;

class OpenCupController extends TournController
{
    protected $tournName = 'OpenCup';
       
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
                
                // return $this->redirect($this->generateUrl('zayso_natgames_home'));
                //$this->sendEmail($referee);
                $msg = 'Application Submitted';
              //$msg = $this->csv($referee);
            }
        }
        
        $tplData = array();
        $tplData['form'] = $form->createView();
        $tplData['msg']  = $msg;

        return $this->render('ZaysoArbiterBundle:Tourn\OpenCup:form.html.twig',$tplData);
    }
    protected function sendEmail($referee)
    {
        $mailerEnabled = $this->container->getParameter('mailer_enabled');
        if (!$mailerEnabled) return;
        
        $message = \Swift_Message::newInstance();
        $message->setSubject('[OpenCup2012] Ref App ' . $referee->firstName . ' ' . $referee->lastName);
        $message->setFrom(array('ahundiak@zayso.org' => 'ZaysoOpenCup2012'));
        
      //$message->setTo  (array($referee->email,'deanjohnson@knology.net'));
        $message->setTo  (array($referee->email));
        
        $message->setBcc (array('ahundiak@gmail.com'));
        
        $message->setBody($this->renderView('ZaysoArbiterBundle:Tourn\OpenCup:email.txt.twig', 
            array('referee' => $referee, 'gen' => $this)
        ));

        $this->get('mailer')->send($message);
        
    }
    public function escape($str)
    {
        return trim(str_replace('"',"'",$str));
    }
    public function csv($referee)
    {
        $phoneXfer = new PhoneTransformer();
        
        $time = time();
        $date = date('m/d/Y');
        
        $line = array
        (
            $date,
            
            $this->escape($referee->firstName),
            $this->escape($referee->lastName),
            $this->escape($referee->nickName),
            
            $this->escape($referee->email),
            $this->escape($phoneXfer->transform($referee->cellPhone)),
            $this->escape($referee->homeCity),
            $this->escape($referee->homeState),
            $this->escape($referee->age),
            $this->escape($referee->gender),
            
      'R' . $this->escape(substr($referee->ussfid,5)),
            $this->escape($referee->refBadge),
            $this->escape($referee->refState),
            $this->escape($referee->refExp),
            
            $this->escape($referee->assessmentRequest),
            
            $this->escape($referee->lodgingFri),
            $this->escape($referee->lodgingSat),
            $this->escape($referee->lodgingWith),
            $this->escape($referee->travelingWith),
            $this->escape($referee->teamAff),
            $this->escape($referee->teamAffDesc),
            
            $this->escape($referee->levelToRef),
            $this->escape($referee->comfortLevelCenter),
            $this->escape($referee->comfortLevelAssistant),
            
            $this->escape($referee->availFri),
            $this->escape($referee->availSat),
            $this->escape($referee->availSun),
            
            'END',
          //$this->escape($referee->notes),    
        );
        $csv = '"' . implode('","',$line) . '"';
        return $csv;
    }
}
?>
