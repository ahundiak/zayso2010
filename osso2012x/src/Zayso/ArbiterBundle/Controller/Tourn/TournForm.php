<?php
namespace Zayso\ArbiterBundle\Controller\Tourn;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

use Zayso\CoreBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\CoreBundle\Component\DataTransformer\UssfidTransformer;
use Zayso\CoreBundle\Component\DataTransformer\StripTagsTransformer;

class TournForm extends AbstractType
{
    protected $name  = 'tourn';
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
            'attr' => array('class' => 'radio-medium'),
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
        $builder->add('teamAffDesc', 'text', array('label' => 'Team Name/Division', 'required' => false, 'attr' => array('size' => 30)));

        $builder->add('levelToRef', 'choice', array(
            'label'         => 'Level to Referee At',
            'required'      => false,
            'empty_value'   => false,
            'choices'       => $this->levelToRefPickList,
        ));
        $builder->add('comfortLevelCenter', 'choice', array(
            'label'         => 'Division - Center',
            'required'      => false,
            'empty_value'   => false,
            'choices'       => $this->comfortLevelPickList,
        ));
        $builder->add('comfortLevelAssistant', 'choice', array(
            'label'         => 'Division - Assistant',
            'required'      => false,
            'empty_value'   => false,
            'choices'       => $this->comfortLevelPickList,
        ));
        $builder->add('notes', 'textarea', array('label' => 'Notes to Assignor', 'required' => false, 
            'attr' => array('rows' => 8, 'cols' => 80, 'wrap' => 'hard', 'class' =>'textarea')));
        
        $builder->get('notes')->appendClientTransformer(new StripTagsTransformer());
        //$builder->appendClientTransformer(new StripTagsTransformer());
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
?>
