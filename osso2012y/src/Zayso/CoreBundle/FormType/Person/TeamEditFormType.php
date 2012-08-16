<?php
namespace Zayso\CoreBundle\FormType\Person;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TeamEditFormType extends AbstractType
{   
    protected $manager   = null;
    protected $projectId = null;

    public function __construct($manager,$projectId)   
    { 
        $this->manager   = $manager;
        $this->projectId = $projectId;
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Zayso\CoreBundle\Entity\PersonTeamRel',
        ));
    }    
    public function getName() { 'person_team_edit'; }

    // Relations
    protected $relPickList = array
    (
        'Referee'    => 'I am a referee for this team',
        
        'Parent'     => 'I am a parent/guardian of a player on this team',
        'Spectator'  => 'I plan to watch this team play',
        'Player'     => 'I play on this team',
        
        'Head-Coach' => 'I am the head coach of this team',
        'Asst-Coach' => 'I am the assistant coach of this team',
        'Manager'    => 'I am the manager of this team',
        
        'Blocked-Soft'      => 'Blocked-Soft - I should avoid refereeing this team',
        'Blocked-By-Person' => 'Blocked-Hard - I will not referee this team',
    );
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $manager = $this->manager;
        
        $builder->add('type', 'choice', array(
            'label'         => 'Team Relation',
            'required'      => false,
            'empty_value'   => 'Choose Relationship to Team',
            'choices'       => $this->relPickList,
        ));
        $builder->add('priority', 'choice', array(
            'label'         => 'Team Relation',
            'required'      => false,
            'empty_value'   => 'Priority',
            'empty_data'    => 0,
            'choices'       => array(0 => 'Priority', 1 => 1, 2=> 2, 3=> 3, 4=> 4, 5 => 5),
        ));
        
        $builder->add('delete', 'checkbox', array(
            'label'     => 'Remove',
            'required'  => false,
        ));
        
        $builder->add('team','entity', array(
            'label'         => 'Team',
            'property'      => 'desc',
            'required'      => false,
            'empty_value'   => 'Choose Team',
            'class'         => 'Zayso\CoreBundle\Entity\Team',
            'em'            => $manager->getEntityManagerName(),
            'query_builder' => $manager->qbPhyTeamsForProject($this->projectId),
        ));
    }
}
