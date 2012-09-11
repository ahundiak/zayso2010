<?php
namespace Zayso\ArbiterBundle\Controller\Tourn;

use Symfony\Component\HttpFoundation\Request;

use Zayso\CoreBundle\DataTransformer\PhoneTransformer;

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
            $form->bind($request);

            if ($form->isValid())
            {
                $this->sendRefereeEmail($referee);
                $msg = 'Application Submitted';
            }
        }
        
        $tplData = array();
        $tplData['form'] = $form->createView();
        $tplData['msg']  = $msg;

        return $this->renderx('Tourn\Kicks:form.html.twig',$tplData);
    }
    protected function sendRefereeEmail($referee)
    {
        $mailerEnabled = $this->container->getParameter('mailer_enabled');
        if (!$mailerEnabled) return;
        
        $message = \Swift_Message::newInstance();
        $message->setSubject('[Kicks2012] Ref App ' . $referee->firstName . ' ' . $referee->lastName);
        $message->setFrom(array('ahundiak@zayso.org' => 'ZaysoKicks2012'));
        
        $message->setTo  (array($referee->email,'ahundiak@gmail.com'));
        
        $message->setBcc (array('ahundiak@gmail.com'));
        
        $message->setBody($this->renderView('ZaysoArbiterBundle:Tourn\Kicks:email.txt.twig', 
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
            
      'R' . substr($referee->ussfid,4),
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
            
            //$referee->levelToRef,
            //$referee->comfortLevelCenter,
            //$referee->comfortLevelAssistant,
            
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
