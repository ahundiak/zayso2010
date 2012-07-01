<?php
namespace Zayso\CoreBundle\Component\FormType\Person;

use Zayso\CoreBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\CoreBundle\Component\DataTransformer\AysoidTransformer;
use Zayso\CoreBundle\Component\DataTransformer\RegionTransformer;
use Zayso\CoreBundle\Component\DataTransformer\PasswordTransformer;

use Zayso\CoreBundle\Component\FormValidator\RegionValidator;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class PersonTeamEditFormType extends AbstractType
{
    protected $name = 'personTeamEdit';
    
    protected $manager   = null;
    protected $projectId = null;

    public function __construct($manager,$projectId)   
    { 
        $this->manager   = $manager;
        $this->projectId = $projectId;
    }
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => $this->manager->getPersonTeamRelClass(), // 'Zayso\CoreBundle\Entity\PersonTeamRel'
        );
    }    
    public function getName() { return $this->name; }

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
    public function buildForm(FormBuilder $builder, array $options)
    {
        $manager = $this->manager;
        
        $builder->add('type', 'choice', array(
            'label'         => 'Team Relation',
            'required'      => false,
            'empty_value'   => 'Choose Relationship to Team',
            'choices'       => $this->relPickList,
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
            'class'         => $manager->getTeamClass(), // 'Zayso\CoreBundle\Entity\Team',
            'em'            => $manager->getEntityManagerName(),
            'query_builder' => $manager->qbPhyTeamsForProject($this->projectId),
        ));
    }
}
