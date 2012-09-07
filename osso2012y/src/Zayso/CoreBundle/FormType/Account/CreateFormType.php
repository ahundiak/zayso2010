<?php
namespace Zayso\CoreBundle\FormType\Account;

use Zayso\CoreBundle\Constraint\UserNameConstraint;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

class CreateFormType extends AbstractType
{
    protected $manager = null;
    
    public function getName() { return 'accountCreate'; }
    
    public function __construct($manager)
    {
        $this->manager = $manager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('userName', 'text',  array(
            'label' => 'Zayso User Name', 
            'attr' => array('size' => 40),
            'constraints' => new UserNameConstraint()));
        
        $builder->add('userPass', 'repeated', array(
            'type'     => 'password',
            'label'    => 'Zayso Password',
            'required' => true,
            'invalid_message' => 'The password fields must match.',
            'constraints' => new NotBlank(),
            
            'first_options'  => array('label' => 'Zayso Password'),
            'second_options' => array('label' => 'Zayso Password(repeat)'),
            
            'first_name'  => 'pass1',
            'second_name' => 'pass2', // form.userPass.pass1
        ));
        $builder->add('person', 'person_edit');
    }
}