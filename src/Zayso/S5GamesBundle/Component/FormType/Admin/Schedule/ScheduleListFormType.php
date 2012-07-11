<?php
namespace Zayso\S5GamesBundle\Component\FormType\Admin\Schedule;

use Doctrine\ORM\EntityRepository;
use Zayso\CoreBundle\Repository\TeamRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class ScheduleListGamePersonFormType extends AbstractType
{
    public function getName() { return 'schListGamePerson'; }
    
    protected $manager;
    protected $projectId;
    
    public function __construct($manager = null, $projectId = null)
    {
        $this->manager   = $manager;
        $this->projectId = $projectId;
    }
    public function setManager($manager) { $this->manager = $manager; }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $manager = $this->manager;
        
        $builder->add('person','entity', array(
            'label'         => 'Person',
            'property'      => 'personName',
            'required'      => false,
            'empty_value'   => 'Choose Person',
            'class'         => $manager->getEventPersonClass(), // 'Zayso\CoreBundle\Entity\Team',
            'em'            => $manager->getEntityManagerName(),
            'query_builder' => $manager->qbRefereesForProject($this->projectId),
        ));
      //$builder->add('pool',  'text', array('attr' => array('size' => 9)));
      
      $builder->add('state', 'choice', array(
          'label'   => 'State',
          'choices' => $this->statePickList,
      ));
    }
    protected $statePickList = array
    (
            'required'      => false,
          //'empty_value'   => 'No State',
        
            'RequestAssignment'   => 'Request Assignment',
            'RequestRemoval'      => 'Request Removal',
            'AssignmentRequested' => 'Assignment Requested',
            'AssignmentApproved'  => 'Assignment Approved',
    );
}
class ScheduleListGameFormType extends AbstractType
{
    public function getName() { return 'schListGame'; }
    
    protected $manager;
    protected $projectId;
    
    public function __construct($manager = null, $projectId = null)
    {
        $this->manager   = $manager;
        $this->projectId = $projectId;
    }
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('pool',  'text', array('attr' => array('size' => 9)));
      //$builder->add('status','text', array('attr' => array('size' => 9)));
        $builder->add('status', 'choice', array(
            'label'   => 'Game Status',
            'choices' => $this->gameStatusPickList,
        ));
        $builder->add('persons','collection',array('type' => new ScheduleListGamePersonFormType($this->manager,$this->projectId)));
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
class ScheduleListFormType extends AbstractType
{
    public function getName() { return 'schList'; }
    
    protected $manager;
    protected $projectId;
    
    public function __construct($manager = null, $projectId = null)
    {
        $this->manager   = $manager;
        $this->projectId = $projectId;
    }
    public function setManager  ($manager)   { $this->manager   = $manager; }
    public function setProjectId($projectId) { $this->projectId = $projectId; }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('games','collection',array('type' => new ScheduleListGameFormType($this->manager,$this->projectId)));
    }
}
?>
