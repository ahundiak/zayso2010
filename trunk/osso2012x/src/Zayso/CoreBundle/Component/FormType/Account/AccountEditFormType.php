<?php
namespace Zayso\CoreBundle\Component\FormType\Account;

use Zayso\CoreBundle\Component\DataTransformer\PasswordTransformer;
use Zayso\CoreBundle\Component\FormValidator\UserNameValidator;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class AccountEditFormType extends AccountBaseFormType
{
    protected $name = 'accountEdit';
    protected $group = 'edit';

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('userName', 'text',  array('label' => 'User Name', 'attr' => array('size' => 35)));
        $builder->add('userNamex','hidden',array('data' => $builder->getData()->getUserName(), 'property_path' => false));

        $builder->add('userPass1', 'password', array('property_path' => 'userPass', 'required' => false, 'label' => 'Password'));
        $builder->add('userPass2', 'password', array('property_path' => false,      'required' => false, 'label' => 'Password(confirm)'));
       
        $builder->addValidator(new CallbackValidator(function($form)
        {
            if($form['userPass1']->getData() != $form['userPass2']->getData())
            {
                $form['userPass2']->addError(new FormError('Passwords do not match'));
            }
        }));
        $builder->addValidator(new UserNameValidator($this->em));

        $builder->get('userPass1')->appendClientTransformer(new PasswordTransformer());
        $builder->get('userPass2')->appendClientTransformer(new PasswordTransformer());
    }
}