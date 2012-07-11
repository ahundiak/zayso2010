<?php
namespace Zayso\CoreBundle\Component\FormType\Account;

use Zayso\CoreBundle\Component\DataTransformer\PasswordTransformer;

use Zayso\CoreBundle\Component\FormValidator\UserNamePassValidator;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class AccountResetPasswordFormType extends AccountBaseFormType
{
    protected $name  = 'accountResetPassword';
    protected $group = 'view';

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('userName', 'text', array('label' => 'User Name, Email or AYSOID:', 'attr' => array('size' => 20)));
        
        $builder->addValidator(new UserNamePassValidator($this->em));
    }
}