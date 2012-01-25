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
    protected $name  = 'tournDC';
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
            'label'         => 'State traveling from',
            'required'      => true,
            'choices'       => $this->statePickList,
        ));
        $builder->add('homeCity', 'text', array('label' => 'City traveling from', 'attr' => array('size' => 30)));
        
        $builder->add('travelingWith', 'text', array('label' => 'Traveling with', 'required' => false, 'attr' => array('size' => 30)));
         
        $builder->add('age','text', array('label' => 'Your age (years)','attr' => array('size' => 4)));
        
        $builder->add('gender', 'choice', array(
            'label'         => 'Your Gender',
            'required'      => true,
            'choices'       => $this->genderPickList,
            'expanded'      => true,
            'multiple'      => false,
        ));
        $builder->add('assessmentRequest', 'choice', array(
            'label'         => 'Assessment Request',
            'required'      => true,
            'choices'       => $this->assessmentRequestPickList,
            'expanded'      => true,
            'multiple'      => false,
        ));
        $builder->add('availability', 'choice', array(
            'label'         => 'Your Availability',
            'required'      => true,
            'choices'       => $this->availabilityPickList,
            'expanded'      => true,
            'multiple'      => true,
        ));
        $builder->add('lodgingRequest', 'choice', array(
            'label'         => 'Lodging Request',
            'required'      => true,
            'choices'       => $this->lodgingRequestPickList,
            'expanded'      => false,
            'multiple'      => false,
        ));
        $builder->add('lodgingWith', 'text', array('label' => 'Lodging with', 'required' => false, 'attr' => array('size' => 30)));
        
        $builder->add('ussfid',    'text', array('label' => 'USSF ID','attr' => array('size' => 18)));
        
        $builder->add('refBadge', 'choice', array(
            'label'         => 'USSF Referee Badge',
            'required'      => true,
            'choices'       => $this->refBadgePickList,
        ));
        $builder->add('refState', 'choice', array(
            'label'         => 'USSF Registered In',
            'required'      => true,
            'choices'       => $this->statePickList,
        ));
        $builder->add('refExp','text', array('label' => 'Experience (years)','attr' => array('size' => 4)));
         
        $builder->get('ussfid'   )->appendClientTransformer(new UssfidTransformer());
        $builder->get('cellPhone')->appendClientTransformer(new PhoneTransformer());
        
        $builder->add('teamAff', 'choice', array(
            'label'         => 'Team/Club Affiliation',
            'required'      => true,
            'choices'       => $this->teamAffPickList,
        ));
        $builder->add('teamAffDesc', 'text', array('label' => 'Team/Club Name/Age/Gender', 'required' => false, 'attr' => array('size' => 30)));

        $builder->add('levelToRef', 'choice', array(
            'label'         => 'Level to Referee At',
            'required'      => true,
            'choices'       => $this->levelToRefPickList,
        ));
        $builder->add('comfortLevelCenter', 'choice', array(
            'label'         => 'Comfort Level - Center',
            'required'      => true,
            'choices'       => $this->comfortLevelPickList,
        ));
        $builder->add('comfortLevelAssistant', 'choice', array(
            'label'         => 'Comfort Level - Assistant',
            'required'      => true,
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
        'ZZ' => 'See Remarks',
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
    public $homeState;
    public $homeCity;
    public $travelingWith;
    
    public $assessmentRequest = 'None';
    public $lodgingRequest = 'None';
    public $lodgingWith;
    public $teamAff;
    public $teamAffDesc;
    
    public $ussfid;
    public $refBadge = 'Grade 8';
    public $refState = 'AL';
    public $refExp   = 0;
    
    public $levelToRef = 'Competitive';
    public $comfortLevelCenter    = 'U10B';
    public $comfortLevelAssistant = 'U10B';
    public $availability;
    
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
     * @Assert\NotBlank(message="USSF ID cannot be blank", groups={"create","edit","add"})
     * @Assert\Regex(
     *     groups={"create","edit","add"},
     *     pattern="/^(USSFR)?\d{16}$/",
     *     message="USSF ID must be 16-digit number")
     */
    public function getUssfid() { return $this->ussfid;  }
}
class OpenCupController extends Controller
{
    public function signupAction(Request $request)
    {
        $msg = null;
        
        $formType = new OpenCupFormType();
        $referee  = new OpenCupReferee();
        
        $form = $this->createForm($formType, $referee);
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                // return $this->redirect($this->generateUrl('zayso_natgames_home'));
                $this->sendEmail($referee);
                $msg = 'Application Submitted';
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
return;        
        $message = \Swift_Message::newInstance();
        $message->setSubject('[OpenCup2012] Ref App ' . $referee->firstName . ' ' . $referee->lastName);
        $message->setFrom(array('ahundiak@zayso.org' => 'Zayso OpenCup2012'));
        $message->setTo  (array('ahundiak@gmail.com','arthur.hundiak@intergraphgovsolutions.com'));
        
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
        
        $fri = null;
        $sat = null;
        switch($referee->lodgingRequest)
        {
            case 'Fri': $fri = 'Yes'; break;
            case 'Sat': $sat = 'Yes'; break;
            case 'Both': $fri = $sat = 'Yes'; break;
        }
        $availFri = null;
        $availSat = null;
        $availSun = null;
        foreach($referee->availability as $avail)
        {
            switch($avail)
            {
                case 'FriNight': $availFri = 'Night';     break;
                case 'SatMorn':  $availSat = 'Morning'; break;
                case 'SunMorn':  $availSun = 'Morning'; break;
                
                case 'SatAfter':  
                    if ($availSat) $availSat = 'All Day';
                    else           $availSat = 'Afternoon'; 
                    break;
                    
                case 'SunAfter':  
                    if ($availSun) $availSun = 'All Day';
                    else           $availSun = 'Afternoon'; 
                    break;
            }
        }
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
            
            $fri,$sat,
            
            $this->escape($referee->lodgingWith),
            $this->escape($referee->travelingWith),
            $this->escape($referee->teamAff),
            $this->escape($referee->teamAffDesc),
            
            $this->escape($referee->levelToRef),
            $this->escape($referee->comfortLevelCenter),
            $this->escape($referee->comfortLevelAssistant),
            
            $availFri,$availSat,$availSun,
            
          //$this->escape($referee->notes),    
        );
        $csv = '"' . implode('","',$line) . '"';
        return $csv;
    }
}
?>
