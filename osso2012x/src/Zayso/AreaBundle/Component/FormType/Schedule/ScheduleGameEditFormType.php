<?php
namespace Zayso\AreaBundle\Component\FormType\Schedule;

use Doctrine\ORM\EntityRepository;
use Zayso\CoreBundle\Repository\TeamRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class ScheduleTeamFormType extends AbstractType
{
    protected $name = 'team';
    public function getName() { return $this->name; }
    
    public function __construct($em,$emName,$projectId)
    {
        $this->em = $em;
        $this->emName = $emName;
        $this->projectId = $projectId;
    }
    protected $em;
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('type', 'text', array(
            'attr' => array('size' => 6),
            'read_only' => true,
          //'label' => 'Team Keyx',
          //'property' => 'teamKey',
          //'class' => 'ZaysoCoreBundle:EventTeam',
        ));
        
        $projectId = $this->projectId;
        
        $qb = $this->em->createQueryBuilder();
        $qb->addSelect('team');
        $qb->from('ZaysoCoreBundle:Team','team');
        $qb->andWhere($qb->expr()->in('team.project', $projectId));
        $qb->addOrderBy('team.key1');
         
        $builder->add('team', 'entity', array(
            'property' => 'teamKey',
            'class'    => 'ZaysoCoreBundle:Team',
            'em'       => $this->emName,
            'query_builder' => $qb,
//          'query_builder' => function(TeamRepository $er)
//          {
//              return $er->qbTeams();            
//          }
       
        ));
    }
}
class ScheduleGameEditFormType extends AbstractType
{
    protected $name = 'gameEdit';
    public function getName() { return $this->name; }

    public function __construct($em)
    {
        $this->em = $em;
    }
    protected $em;
    protected $emName = 'games';
    
    protected function getEntityManager() { return $this->em; }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('num',    'text', array('label' => 'Number'));
        $builder->add('type',   'text', array('label' => 'Type'));
        $builder->add('date',   'text', array('label' => 'Date'));
        $builder->add('time',   'text', array('label' => 'Time'));
        $builder->add('pool',   'text', array('label' => 'Pool', 'required' => false));
        
        $builder->add('status', 'choice', array(
            'label'   => 'Status',
            'choices' => $this->statusPickList,
        ));
        
        $game = $builder->getData();
        $projectId = $game->getProject()->getId();
        
        $builder->add('teams', 'collection', array('type' => new ScheduleTeamFormType($this->em,$this->emName,$projectId)));

        $qb = $this->em->createQueryBuilder();
        $qb->addSelect('field');
        $qb->from('ZaysoCoreBundle:ProjectField','field');
        $qb->andWhere($qb->expr()->in('field.project', $projectId));
        $qb->addOrderBy('field.key1');
       
        
        $builder->add('field','entity', array(
            'label'    => 'Field',
            'class'    => 'ZaysoCoreBundle:ProjectField',
            'property' => 'key',
            'em'       => $this->emName,
            'query_builder' => $qb,
        ));
        
        
    }
    protected $statusPickList = array('Active' => 'Active', 'Cancelled' => 'Cancelled', 'Processed' => 'Processed');
}
?>
