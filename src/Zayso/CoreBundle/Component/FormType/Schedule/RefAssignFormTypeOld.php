<?php
namespace Zayso\CoreBundle\Component\FormType\Schedule;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

use Zayso\CoreBundle\Component\DataTransformer\StripTagsTransformer;

class GameTeamReportFormType extends AbstractType
{
    protected $name = 'team';
    public function getName() { return $this->name; }
    
    public function __construct($em,$emName = null)
    {
        $this->em = $em;
        $this->emName = $emName;
    }
    protected $em;
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        //$builder->add('type', 'text', array(
        //    'attr' => array('size' => 6),
        //    'read_only' => true,
        //));
        $builder->add('teamKey', 'text', array(
            'attr' => array('size' => 30),
            'read_only' => true,
        ));
        $builder->add('goalsScored', 'integer', array(
            'attr' => array('size' => 4),
            'required'        => false,
            'error_bubbling'  => true,
            'invalid_message' => 'Goals scored must be a number',
        ));
        $builder->add('cautions', 'integer', array(
            'attr' => array('size' => 4),
            'required' => false,
        ));
        $builder->add('sendoffs', 'integer', array(
            'attr' => array('size' => 4),
            'required' => false,
        ));
        $builder->add('coachTossed', 'integer', array(
            'attr' => array('size' => 4),
            'required' => false,
        ));
        $builder->add('specTossed', 'integer', array(
            'attr' => array('size' => 4),
            'required' => false,
        ));
        $builder->add('sportsmanship', 'integer', array(
            'attr' => array('size' => 4),
            'required' => false,
        ));
         $builder->add('fudgeFactor', 'text', array(
            'attr' => array('size' => 4),
            'required' => false,
        ));
        $builder->add('pointsEarned', 'text', array(
            'attr' => array('size' => 4),
            'required'  => false,
            'read_only' => true,
        ));
        $builder->add('pointsMinus', 'text', array(
            'attr' => array('size' => 4),
            'required'  => false,
            'read_only' => true,
        ));
   }
    public function getParent(array $options)
    {
        return 'form';
    }
}
class GameTeamRelReportFormType extends AbstractType
{
    protected $name = 'teamRel';
    public function getName() { return $this->name; }
    
    public function __construct($em,$emName = null)
    {
        $this->em = $em;
        $this->emName = $emName;
    }
    protected $em;
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('type', 'text', array(
            'attr' => array('size' => 6),
            'read_only' => true,
        ));
        $builder->add('team', new GameTeamReportFormType($this->em));
    }
}
class RefAssignFormType extends AbstractType
{
    protected $name = 'gameRefAssign';
    public function getName() { return $this->name; }

    public function __construct($em)
    {
        $this->em = $em;
    }
    protected $em;
    protected $emName = 'games';
    
    protected function getEntityManager() { return $this->em; }
    
    protected $builder = null;
    
    protected function addText($name,$label,$size=20)
    {
       $this->builder->add($name,'text', array
       (
           'read_only' =>  true, 
           'required'  => false,
           'label'     => $label,
           'attr'      => array('size' => $size),

        ));
         
    }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $this->builder = $builder;
        
        $this->addText('num',      'Number',6);
        $this->addText('type',     'Type',  6);
        $this->addText('date',     'Date',  8);
        $this->addText('time',     'Time',  6);
        $this->addText('pool',     'Pool', 10);
        $this->addText('fieldDesc','Field',12);
        /*
        $builder->add('status', 'choice', array(
            'label'   => 'Game Status',
            'choices' => $this->gameStatusPickList,
        ));*/
        /*
          
        $builder->add('teams', 'collection', array('type' => new GameTeamRelReportFormType($this->em)));
        
        $builder->add('report', 'textarea', array('label' => 'Comments', 'required' => false, 
            'attr' => array('rows' => 12, 'cols' => 78, 'wrap' => 'hard', 'class' =>'textarea')));
        
        $builder->get('report')->appendClientTransformer(new StripTagsTransformer());
        */
    }
    protected $gameStatusPickList = array
    (
        'Active'     => 'Active', 
        'InProgress' => 'In Progress',
        'Played'     => 'Played',
        
        'Suspended'  => 'Suspended',
        'Terminated' => 'Terminated',
        'Forfeited'  => 'Forfeited',
        
        'Cancelled'  => 'Cancelled', 
        'StormedOut' => 'Stormed Out',

    );
}
?>
