<?php
namespace Zayso\CoreBundle\FormType\Account;

use Zayso\CoreBundle\Constraint\UserNameConstraint;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;

class EditFormType extends AbstractType
{
    protected $manager = null;
    
    public function getName() { return 'accountEdit'; }
    
    public function __construct($manager)
    {
        $this->manager = $manager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('userName', 'text',  array(
            'label' => 'New User Name', 
            'attr' => array('size' => 40),
            'constraints' => new UserNameConstraint()));
        
        $builder->add('userPass', 'repeated', array(
            'type'     => 'password',
            'label'    => 'Change Password',
            'required' => false,
            'invalid_message' => 'The password fields must match.',
            'options'        => array('required' => false), // Did not seem to work
            'first_options'  => array('label' => 'New Password'),
            'second_options' => array('label' => 'New Password(repeat)'),
            'first_name'  => 'pass1',
            'second_name' => 'pass2', // form.userPass.pass1
        ));
        return;
        
      // The new validator pulls the exsting user name from the token user
      //$builder->add('userNamex','hidden',array('data' => $builder->getData()->getUserName(), 'property_path' => false));

        $builder->add('userPass1', 'password', array('property_path' => 'userPass', 'required' => false, 'label' => 'Password'));
        $builder->add('userPass2', 'password', array('property_path' => false,      'required' => false, 'label' => 'Password(confirm)'));
       
        // Use of a call back validator is depreciated, supposed tp use FormEvent:POST_BIBD, need example
        $builder->addValidator(new CallbackValidator(function($form)
        {
            if($form['userPass1']->getData() != $form['userPass2']->getData())
            {
                $form['userPass2']->addError(new FormError('Passwords do not match'));
            }
        }));
    }
}