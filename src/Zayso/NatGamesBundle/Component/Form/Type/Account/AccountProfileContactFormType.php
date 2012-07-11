<?php

namespace Zayso\NatGamesBundle\Component\Form\Type\Account;

use Zayso\CoreBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\CoreBundle\Component\DataTransformer\AysoidTransformer;
use Zayso\CoreBundle\Component\DataTransformer\RegionTransformer;
use Zayso\CoreBundle\Component\DataTransformer\PasswordTransformer;

use Zayso\CoreBundle\Component\FormValidator\UserNameValidator;

use Zayso\NatGamesBundle\Component\Form\Type\Account\AccountBaseFormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class AccountProfileContactFormType extends AccountBaseFormType
{
    protected $name  = 'accountProfileContact';
    protected $group = 'edit';
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('firstName', 'text', array('label' => 'AYSO First Name'));
        $builder->add('lastName',  'text', array('label' => 'AYSO Last Name'));
        $builder->add('nickName',  'text', array('label' => 'Nick Name','required' => false,));

        $builder->add('email',     'text', array('label' => 'Email',      'attr' => array('size' => 35)));
        $builder->add('cellPhone', 'text', array('label' => 'Cell Phone', 'attr' => array('size' => 20), 'required' => false,));

        $builder->add('accountPersonId', 'hidden', array('property_path' => false, 'data' => $builder->getData()->getId()));
        
        $builder->get('cellPhone')->appendClientTransformer(new PhoneTransformer());
    }    
}
