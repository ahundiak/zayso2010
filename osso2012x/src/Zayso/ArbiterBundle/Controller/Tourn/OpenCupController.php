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

class OpenCupFormType extends AbstractType
{
    protected $name  = 'tournOpenCup';
    protected $group = 'create';

    public function __construct($em = null)
    {
        $this->em = $em;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array();

        if ($this->group) $defaultOptions['validation_groups'] = array($this->group);

        return $defaultOptions;
    }
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('firstName', 'text', array('label' => 'First Name'));
        $builder->add('lastName',  'text', array('label' => 'Last Name'));
        $builder->add('nickName',  'text', array('label' => 'Nick Name', 'required' => false,));
        
        $builder->add('email',     'text', array('label' => 'Email',      'attr' => array('size' => 35)));
        $builder->add('cellPhone', 'text', array('label' => 'Cell Phone', 'attr' => array('size' => 20)));
        
        $builder->add('homeState', 'choice', array(
            'label'         => 'Home State',
            'required'      => false,
            'empty_value'   => false,
            'choices'       => $this->statePickList,
        ));
        $builder->add('homeCity', 'text', array('label' => 'Home City', 'attr' => array('size' => 30)));
        
        $builder->add('travelingWith', 'text', array('label' => 'Traveling with', 'required' => false, 'attr' => array('size' => 30)));
         
        $builder->add('age','text', array('label' => 'Your age (years)','attr' => array('size' => 4)));
        
        $builder->add('gender', 'choice', array(
            'label'         => 'Your Gender',
            'required'      => true,
            'choices'       => $this->genderPickList,
            'expanded'      => true,
            'multiple'      => false,
            'attr' => array('class' => 'gender'),
        ));
        $builder->add('assessmentRequest', 'choice', array(
            'label'         => 'Assessment Request',
            'required'      => true,
            'choices'       => $this->assessmentRequestPickList,
            'expanded'      => true,
            'multiple'      => false,
            'attr' => array('class' => 'radio-medium'),
        ));
        /*
        $builder->add('availability', 'choice', array(
            'label'         => 'Your Availability',
            'required'      => true,
            'choices'       => $this->availabilityPickList,
            'expanded'      => true,
            'multiple'      => true,
        ));*/
        $builder->add('availFri', 'choice', array(
            'label'         => 'Availability - Friday',
            'required'      => false,
            'empty_value'   => false,
            'choices'       => array('None' => 'None', 'Evening' => 'Kickoff 5pm', 'Not Sure' => 'Not Sure'),
        ));
        $builder->add('availSat', 'choice', array(
            'label'         => 'Availability - Saturday',
            'required'      => false,
            'empty_value'   => false,
            'choices'       => array('None' => 'None', 'All Day' => 'All Day', 
                'Morning' => 'Morning Only', 'Afternoon' => 'Afternoon Only', 'Not Sure' => 'Not Sure'),
        ));
        $builder->add('availSun', 'choice', array(
            'label'         => 'Availability - Sunday',
            'required'      => false,
            'empty_value'   => false,
            'choices'       => array('None' => 'None', 'All Day' => 'All Day', 
                'Morning' => 'Morning Only', 'Afternoon' => 'Afternoon Only', 'Not Sure' => 'Not Sure'),
        ));
        
        $builder->add('lodgingRequest', 'choice', array(
            'label'         => 'Lodging Request',
            'required'      => false,
            'choices'       => $this->lodgingRequestPickList,
            'expanded'      => false,
            'multiple'      => false,
            'empty_value'   => false,
        ));
        $builder->add('lodgingWith', 'text', array('label' => 'Lodging with', 'required' => false, 'attr' => array('size' => 30)));
        
        // Dean did not want this, now optional
        $builder->add('ussfid', 'text', array('label' => 'USSF ID (16 digits)', 'required' => false, 'attr' => array('size' => 18)));
        
        $builder->add('refBadge', 'choice', array(
            'label'         => 'USSF Referee Badge',
            'required'      => false,
            'empty_value'   => false,
            'choices'       => $this->refBadgePickList,
        ));
        $builder->add('refState', 'choice', array(
            'label'         => 'USSF State',
            'required'      => false,
            'empty_value'   => false,
            'choices'       => $this->statePickList,
        ));
        $builder->add('refExp','text', array('label' => 'Experience (years)','attr' => array('size' => 4)));
         
        $builder->get('ussfid'   )->appendClientTransformer(new UssfidTransformer());
        $builder->get('cellPhone')->appendClientTransformer(new PhoneTransformer());
        
        $builder->add('teamAff', 'choice', array(
            'label'         => 'Team/Club Affiliation',
            'required'      => false,
            'empty_value'   => false,
            'choices'       => $this->teamAffPickList,
        ));
        $builder->add('teamAffDesc', 'text', array('label' => 'Team/Club Name/Age/Gender', 'required' => false, 'attr' => array('size' => 30)));

        $builder->add('levelToRef', 'choice', array(
            'label'         => 'Level to Referee At',
            'required'      => false,
            'empty_value'   => false,
            'choices'       => $this->levelToRefPickList,
        ));
        $builder->add('comfortLevelCenter', 'choice', array(
            'label'         => 'Comfort Level - Center',
            'required'      => false,
            'empty_value'   => false,
            'choices'       => $this->comfortLevelPickList,
        ));
        $builder->add('comfortLevelAssistant', 'choice', array(
            'label'         => 'Comfort Level - Assistant',
            'required'      => false,
            'empty_value'   => false,
            'choices'       => $this->comfortLevelPickList,
        ));
        $builder->add('notes', 'textarea', array('label' => 'Notes to Assignor', 'required' => false, 
            'attr' => array('rows' => 8, 'cols' => 50, 'wrap' => 'hard')));
    }
    protected $refBadgePickList = array
    (
        'None'    => 'None',
        'Grade 9' => 'Grade 9',
        'Grade 8' => 'Grade 8',
        'Grade 7' => 'Grade 7',
        'Grade 6' => 'Grade 6',
        'Grade 5' => 'Grade 5',
        'Grade 4' => 'Grade 4',
        'Grade 3' => 'Grade 3',
        'Grade 2' => 'Grade 2',
        'Grade 1' => 'Grade 1',
        'ZZ'      => 'See Remarks',
    );
    protected $statePickList = array
    (
        'AL' => 'Alabama',
        'AR' => 'Arkansas',
        'GA' => 'Gerogia',
        'LA' => 'Louisiana',
        'MS' => 'Mississippi',
        'TN' => 'Tennessee',
        'ZZ' => 'See Notes',
    );
    protected $assessmentRequestPickList = array
    (
        'None'     => 'None',
        'Informal' => 'Informal',
        'Formal'   => 'Formal',
    );
    protected $lodgingRequestPickList = array
    (
        'None' => 'None',
        'Both' => 'Friday and Saturday Night',
        'Fri'  => 'Friday Night Only',
        'Sat'  => 'Saturday Night Only',
    );
    protected $teamAffPickList = array
    (
        'None' => 'None',
        'Yes'  => 'Yes and the club/team is playing in this tournament',
        'Yesx' => 'Yes but the club/team is not playing in this tournament',
    );
    protected $genderPickList = array ('M' => 'Male', 'F' => 'Female');
    
    protected $levelToRefPickList = array('Elite' => 'Elite', 'Competitive' => 'Competitive');
    
    protected $comfortLevelPickList = array
    (
        'U10B' => 'U10 Boys', 'U10G' => 'U10 Girls',
        'U12B' => 'U12 Boys', 'U12G' => 'U12 Girls',
        'U14B' => 'U14 Boys', 'U14G' => 'U14 Girls',
        'U16B' => 'U16 Boys', 'U16G' => 'U16 Girls',
        'U19B' => 'U19 Boys', 'U19G' => 'U19 Girls',
   );
   protected $availabilityPickList = array
   (
       'FriNight' => 'Friday Night (5:00PM CST Kickoff)',
       'SatMorn'  => 'Saturday Morning',
       'SatAfter' => 'Saturday Afternoon',
       'SunMorn'  => 'Sunday Morning',
       'SunAfter' => 'Sunday Afternoon',
       
   );
}
class OpenCupReferee
{
    public $firstName;
    public $lastName;
    public $nickName;
    
    public $age;
    public $gender = 'M';
    
    public $email;
    public $cellPhone;
    public $homeState = 'AL';
    public $homeCity;
    public $travelingWith;
    
    public $assessmentRequest = 'None';
    public $lodgingRequest = 'None';
    public $lodgingWith;
    public $teamAff = 'None';
    public $teamAffDesc;
    
    public $ussfid;
    public $refBadge = 'Grade 8';
    public $refState = 'AL';
    public $refExp   = 0;
    
    public $levelToRef = 'Competitive';
    public $comfortLevelCenter    = 'U10B';
    public $comfortLevelAssistant = 'U10B';
    
    public $availFri = 'None';
    public $availSat = 'None';
    public $availSun = 'None';
    
    public $notes;
    
    /** @Assert\NotBlank(message="First Name cannot be blank.", groups={"create","edit"}) */
    public function getFirstName () { return $this->firstName; }

    /** @Assert\NotBlank(message="Last Name cannot be blank.", groups={"create","edit"}) */
    public function getLastName () { return $this->lastName; }

    public function getNickName () { return $this->nickName; }

    /**
     * @Assert\NotBlank(message="Email cannot be blank.",groups={"create","edit","add"})
     * @Assert\Email   (message="Email is not valid.",   groups={"create","edit","add"})
     */
    public function getEmail() { return $this->email; }
    
    /**
     *  Assert\NotBlank(message="USSF ID cannot be blank", groups={"create","edit","add"})
     *  Assert\Regex(
     *     groups={"create","edit","add"},
     *     pattern="/^(USSFR)?\d{16}$/",
     *     message="USSF ID must be 16-digit number")
     */
    public function getUssfid() { return $this->ussfid;  }
    
    public function __get($name)
    {
        switch($name)
        {
            case 'lodgingFri': return $this->getLodgingFri();                 
            case 'lodgingSat': return $this->getLodgingSat();
        }
        return null;
    }
    public function getLodgingFri()
    {
        switch($this->lodgingRequest)
        {
            case 'Fri':  return 'Yes';
            case 'Both': return 'Yes';
        }
        return 'No';
    }
    public function getLodgingSat()
    {
        switch($this->lodgingRequest)
        {
            case 'Sat':  return 'Yes';
            case 'Both': return 'Yes';
        }
        return 'No';
    }
}
class OpenCupController extends Controller
{
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
                // return $this->redirect($this->generateUrl('zayso_natgames_home'));
                $this->sendEmail($referee);
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
