<?php
namespace Zayso\AreaBundle\Component\FormType\Admin\Team;

use Doctrine\ORM\EntityRepository;
use Zayso\CoreBundle\Repository\TeamRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;


class EditFormType extends AbstractType
{
    protected $name = 'teamEdit';
    public function getName() { return $this->name; }

    public function __construct($em)
    {
        $this->em = $em;
    }
    protected $em;
    protected $emName = 'teams';
    
    protected function getEntityManager() { return $this->em; }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('source', 'choice', array(
            'label'   => 'Source',
            'choices' => array('manual' => 'manual', 'schedule_import' => 'schedule_import','eayso' => 'eayso'),
        ));
        $builder->add('type', 'choice', array(
            'label'   => 'Type',
            'choices' => array('Schedule' => 'Schedule', 'Physical' => 'Physical', 'Permanent' => 'Permanent'),
        ));
        $builder->add('status', 'choice', array(
            'label'   => 'Status',
            'choices' => array('Active' => 'Active','Inactive' => 'Inactive'),
        ));
        $builder->add('level', 'choice', array(
            'label'   => 'Level',
            'choices' => array('Regular' => 'Regular', 'Select' => 'Select', 'VIP' => 'VIP'),
        ));
        $builder->add('age', 'choice', array(
            'label'   => 'Age',
            'choices' => array(
                'U05' => 'U05', 'U06' => 'U06', 'U07' => 'U07', 'U08' => 'U08',
                'U10' => 'U10', 'U12' => 'U12', 'U14' => 'U14', 'U16' => 'U16', 'U19' => 'U19',
            ),
        ));
        $builder->add('gender', 'choice', array(
            'label'   => 'Gender',
            'choices' => array(
                'U' => 'Unknown', 'B' => 'Boys', 'G' => 'Girls', 'C' => 'Coed', 'V' => 'VIP',
            ),
        ));
        $builder->add('teamKey',        'text',array('label'=> 'Team Schedule Key'));
        $builder->add('teamKeyExpanded','text',array('label'=> 'Team Expanded Key', 'required'=> false));
        $builder->add('eaysoTeamId',    'text',array('label'=> 'EAYSO Team Id',     'required'=> false));
        $builder->add('eaysoTeamDesig', 'text',array('label'=> 'EAYSO Team Desig',  'required'=> false));
        
        $builder->add('teamName',       'text',array('label'=> 'Team Name',   'required'=> false));
        $builder->add('teamColors',     'text',array('label'=> 'Team Colors', 'required'=> false));
        
        // Organization
        $builder->add('orgDesc','text',array('label'=> 'Region', 'required'=> false, 'read_only' => true));
        
        $qb = $this->em->createQueryBuilder();
        $qb->addSelect('org');
        $qb->from('ZaysoCoreBundle:Org','org');
      //$qb->andWhere($qb->expr()->in('team.project', $projectId));
        $qb->addOrderBy('org.desc2');
         
        $builder->add('org', 'entity', array(
            'property' => 'desc2',
            'class'    => 'ZaysoCoreBundle:Org',
            'em'       => $this->emName,
            'query_builder' => $qb,
       
        ));
        
    }
}
?>
