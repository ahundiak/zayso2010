<?php
namespace Zayso\CoreBundle\Component\FormType\Person;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class PersonPersonEditFormType extends AbstractType
{
    protected $manager = null;
    
    public function __construct($manager)
    {
        $this->manager = $manager;
    }
    
    public function getName() { return 'personPersonEdit'; }

    // Leave for now as Primary should not be a choice
    protected $relationPickList = array
    (
        'Primary' => 'Primary - Owner',
        'Family'  => 'Family - Full control over person',
        'Peer'    => 'Peer - Only to signup for games'
    );
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('relation', 'choice', array(
            'label'         => 'Person Relation',
            'required'      => true,
            'choices'       => $this->relationPickList,
            'read_only'     => true,
        ));
        $builder->add('person2', new PersonEditFormType($this->manager));
    }
}
