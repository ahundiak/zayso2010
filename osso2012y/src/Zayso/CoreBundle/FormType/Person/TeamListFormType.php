<?php
namespace Zayso\CoreBundle\FormType\Person;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TeamListFormType extends AbstractType
{
    protected $manager   = null;
    protected $projectId = null;

    public function __construct($manager,$projectId)   
    { 
        $this->manager   = $manager;
        $this->projectId = $projectId;
    }   
    public function getName() { return 'person_team_list'; }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('teamRels', 'collection', array(
            'type' => new TeamEditFormType($this->manager,$this->projectId),
        ));
        
    }
}
