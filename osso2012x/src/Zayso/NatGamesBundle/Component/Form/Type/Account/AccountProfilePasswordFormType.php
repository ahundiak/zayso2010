<?php

namespace Zayso\NatGamesBundle\Component\Form\Type\Account;

use Zayso\ZaysoBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\AysoidTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\RegionTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\PasswordTransformer;

use Zayso\ZaysoBundle\Component\Form\Validator\UserNameValidator;

use Zayso\NatGamesBundle\Component\Form\Type\Account\AccountBaseFormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class AccountProfilePasswordFormType extends AccountBaseFormType
{
    protected $name  = 'accountProfilePassword';
    protected $group = 'edit';

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('userName', 'text',  array('label' => 'New User Name', 'attr' => array('size' => 35)));
        $builder->add('userNamex','hidden',array('data' => $builder->getData()->getUserName(), 'property_path' => false));

        $builder->add('userPass1', 'password',
                array('required' => false, 'property_path' => 'userPass', 'label' => 'New Password'));
        $builder->add('userPass2', 'password',
                array('required' => false, 'property_path' => false,      'label' => 'New Password(confirm)'));

        $builder->add('accountPersonId', 'hidden', array('property_path' => false, 'data' => $builder->getData()->getId()));

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
