<?php
namespace Zayso\NatGamesBundle\Controller;

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
use Zayso\CoreBundle\Component\DataTransformer\AysoidTransformer;
use Zayso\CoreBundle\Component\DataTransformer\StripTagsTransformer;

class ClassesForm extends AbstractType
{
    protected $name  = 'classes';
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
        
        $builder->add('email',     'text', array('label' => 'Email',      'attr' => array('size' => 35)));
        $builder->add('cellPhone', 'text', array('label' => 'Cell Phone', 'attr' => array('size' => 20), 'required' => false));
        $builder->get('cellPhone')->appendClientTransformer(new PhoneTransformer());
                
        $builder->add('aysoid', 'text', array('label' => 'AYSO ID (8 digits)', 'required' => false, 'attr' => array('size' => 10)));
        $builder->add('region', 'text', array('label' => 'AYSO Region Number', 'required' => false, 'attr' => array('size' =>  6)));
        
        $builder->add('role', 'choice', array(
            'label'         => 'Role',
            'required'      => false,
            'empty_value'   => false,
            'choices'       => $this->rolePickList,
        ));
        $builder->add('class', 'choice', array(
            'label'         => 'Class',
            'required'      => false,
            'empty_value'   => false,
            'choices'       => $this->classPickList,
        ));
        
        $builder->add('notes', 'textarea', array('label' => 'Notes', 'required' => false, 
            'attr' => array('rows' => 4, 'cols' => 80, 'wrap' => 'hard', 'class' =>'textarea')));
        
        $builder->get('notes')->appendClientTransformer(new StripTagsTransformer());
        
        $builder->add('avail', 'choice', array(
            'label'         => 'Availablity',
            'required'      => true,
            'choices'       => $this->availPickList,
            'expanded'      => true,
            'multiple'      => true,
            'attr' => array('class' => 'radio-medium'),
        ));
    }
    protected $availPickList = array
    (
        'Sun' => 'Sun',
        'Mon' => 'Mon',
        'Tue' => 'Tue',
      //'Wed' => 'Wed',
    );
    protected $classPickList = array
    (
        'National'        => 'National Referee',
        'Advanced'        => 'Advanced Referee',
        'Intermediate'    => 'Intermediate Referee',
        'Regional Clinic' => 'Regional Clinic',
    );
    protected $rolePickList = array
    (
        'Student'    => 'Student',
        'Instructor' => 'Instructor',
    );
}
class ClassesPerson
{
    public $firstName;
    public $lastName;

    public $email;
    public $cellPhone;
    
    public $aysoid;
    public $region;
    
    public $role; // Student or instructor
    public $class;
    
    public $avail;
    
    public $notes;
    
    /** @Assert\NotBlank(message="First Name cannot be blank.", groups={"create","edit"}) */
    public function getFirstName () { return $this->firstName; }

    /** @Assert\NotBlank(message="Last Name cannot be blank.", groups={"create","edit"}) */
    public function getLastName () { return $this->lastName; }

    /**
     * @Assert\NotBlank(message="Email cannot be blank.",groups={"create","edit","add"})
     * @Assert\Email   (message="Email is not valid.",   groups={"create","edit","add"})
     */
    public function getEmail() { return $this->email; }
    
    public function getAysoid() { return $this->aysoid;  }
    public function getRegion() { return $this->region;  }
    
    public function getAvailSun() { return $this->getAvailForDay('Sun'); }
    public function getAvailMon() { return $this->getAvailForDay('Mon'); }
    public function getAvailTue() { return $this->getAvailForDay('Tue'); }
    public function getAvailWed() { return $this->getAvailForDay('Wed'); }
    
    public function getAvailForDay($day) 
    { 
        if (!is_array($this->avail)) return 'No';
        if (array_search($day,$this->avail) === false) return 'No';
        return 'Yes';
    }

}

class ClassesController extends Controller
{
    public function signupAction(Request $request)
    {
        $msg = null;
        
        $formType = new ClassesForm();
        $person   = new ClassesPerson();
        
        if ($request->getMethod() == 'GET')
        {
            $user = $this->get('security.context')->getToken()->getUser();
            if (is_object($user))
            {
                $accountManager = $this->get('zayso_model.account.manager');
                $personx = $accountManager->getPerson(array('personId' => $user->getPersonId()));
                if ($personx)
                {
                    $person->lastName  = $personx->getLastName();
                    $person->firstName = $personx->getFirstName();
                    $person->email     = $personx->getEmail();
                    $person->cellPhone = $personx->getCellPhone();
                    $person->aysoid    = substr($personx->getAysoid(),5);
                    
                    $region = (int)substr($personx->getOrgKey(),5);
                    if (!$region) $region = '';
                    $person->region = $region;
                }
            }
        }
        
        $form = $this->createForm($formType, $person);
        
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $this->sendEmail($person);
                $msg = 'Application Submitted';
            }
        }
        
        $tplData = array();
        $tplData['form'] = $form->createView();
        $tplData['msg']  = $msg;

        return $this->render('ZaysoNatGamesBundle:Classes:form.html.twig',$tplData);
    }
    protected function sendEmail($person)
    {
        $mailerEnabled = $this->container->getParameter('mailer_enabled');
        if (!$mailerEnabled) return;
        
        $message = \Swift_Message::newInstance();
        $subject = sprintf('[NatGames2012Class] %s %s %s',$person->firstName,$person->lastName,$person->class);
        $message->setSubject($subject);
        $message->setFrom(array('ahundiak@zayso.org' => 'ZaysoNatGames2012'));
        
      //$message->setTo  (array($referee->email,'deanjohnson@knology.net'));
      //$message->setTo  (array($referee->email));
        
        $message->setBcc (array('ahundiak@gmail.com'));
        
        $message->setBody($this->renderView('ZaysoNatGamesBundle:Classes:email.txt.twig', 
            array('person' => $person, 'gen' => $this)
        ));

        $this->get('mailer')->send($message);
        
    }
    public function csv($person)
    {
        $phoneXfer = new PhoneTransformer();
        
        $date = date('m/d/Y');
        
        $line = array
        (
            $date,
            
            $person->firstName,
            $person->lastName,
            
            $person->email,
            $phoneXfer->transform($person->cellPhone),
            
            $person->aysoid,
            $person->region,
           
            $person->role,
            $person->class,
            
            $person->getAvailSun(),
            $person->getAvailMon(),
            $person->getAvailTue(),
            $person->getAvailWed(),
            
          //$person->notes,    
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
