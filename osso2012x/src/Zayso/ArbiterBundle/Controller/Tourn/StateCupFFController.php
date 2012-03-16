<?php
namespace Zayso\ArbiterBundle\Controller\Tourn;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

class StateCupFFTournForm extends TournForm
{
    protected $levelToRefPickList = array('D1' => 'Division 1', 'D2' => 'Division 2');
 
}
class StateCupFFController extends Controller
{
    public function signupAction(Request $request)
    {
        $msg = null;
        
        $formType = new StateCupFFTournForm();
        $referee  = new TournOfficial();
        
        $form = $this->createForm($formType, $referee);
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                // return $this->redirect($this->generateUrl('zayso_natgames_home'));
                $this->sendEmail($referee);
                $msg = 'Application Submitted';
              //$msg = $this->csv($referee);
            }
        }
        
        $tplData = array();
        $tplData['form'] = $form->createView();
        $tplData['msg']  = $msg;

        return $this->render('ZaysoArbiterBundle:Tourn\StateCupFF:form.html.twig',$tplData);
    }
    protected function sendEmail($referee)
    {
        $mailerEnabled = $this->container->getParameter('mailer_enabled');
        if (!$mailerEnabled) return;
        
        $message = \Swift_Message::newInstance();
        $message->setSubject('[StateCupFF2012] Ref App ' . $referee->firstName . ' ' . $referee->lastName);
        $message->setFrom(array('ahundiak@zayso.org' => 'ZaysoStateCupFF2012'));
        
        $message->setTo  (array($referee->email,'ohannesy@yahoo.com'));
      //$message->setTo  (array($referee->email));
        
        $message->setBcc (array('ahundiak@gmail.com'));
        
        $message->setBody($this->renderView('ZaysoArbiterBundle:Tourn\StateCupFF:email.txt.twig', 
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
