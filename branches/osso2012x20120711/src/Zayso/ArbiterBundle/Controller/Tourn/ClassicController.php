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

class ClassicTournForm extends TournForm
{
    // protected $levelToRefPickList = array('D1' => 'Division 1', 'D2' => 'Division 2');
 
}
class ClassicController extends TournController
{
    protected $tournName = 'Classic';
       
    public function signupAction(Request $request)
    {
        $msg = null;
        
        $formType = new ClassicTournForm();
        $referee  = new TournOfficial();
        
        $form = $this->createForm($formType, $referee);
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $this->trap($referee);
                
                //$this->sendEmail($referee);
                $msg = 'Application Submitted';
            }
        }
        
        $tplData = array();
        $tplData['form'] = $form->createView();
        $tplData['msg']  = $msg;

        return $this->render('ZaysoArbiterBundle:Tourn\Classic:form.html.twig',$tplData);
    }
    protected function sendEmail($referee)
    {
        $mailerEnabled = $this->container->getParameter('mailer_enabled');
        if (!$mailerEnabled) return;
        
        $message = \Swift_Message::newInstance();
        $message->setSubject('[Classic2012] Ref App ' . $referee->firstName . ' ' . $referee->lastName);
        $message->setFrom(array('ahundiak@zayso.org' => 'ZaysoClassic2012'));
        
        $message->setTo  (array($referee->email,'ahundiak@gmail.com'));
        
        $message->setBcc (array('ahundiak@gmail.com'));
        
        $message->setBody($this->renderView('ZaysoArbiterBundle:Tourn\Classic:email.txt.twig', 
            array('referee' => $referee, 'gen' => $this)
        ));

        $this->get('mailer')->send($message);
        
    }
    public function csv($referee)
    {
        $phoneXfer = new PhoneTransformer();
        
        $date = date('m/d/Y');
        
        $line = array
        (
            $date,
            
            $referee->firstName,
            $referee->lastName,
            $referee->nickName,
            
            $referee->email,
            $phoneXfer->transform($referee->cellPhone),
            $referee->homeCity,
            $referee->homeState,
            $referee->age,
            $referee->gender,
            
      'R' . substr($referee->ussfid,5),
            $referee->refBadge,
            $referee->refState,
            $referee->refExp,
            
            $referee->assessmentRequest,
            
            $referee->lodgingFri,
            $referee->lodgingSat,
            $referee->lodgingWith,
            $referee->travelingWith,
            $referee->teamAff,
            $referee->teamAffDesc,
            
            $referee->levelToRef,
            $referee->comfortLevelCenter,
            $referee->comfortLevelAssistant,
            
            $referee->availFri,
            $referee->availSat,
            $referee->availSun,
             
           'END', 
        );
        // Convert to csv
        $fp = fopen('php://temp','r+');
        fputcsv($fp,$line);
        rewind($fp);
        $csv = stream_get_contents($fp);
        fclose($fp);
        return $csv;
    }
}
?>
