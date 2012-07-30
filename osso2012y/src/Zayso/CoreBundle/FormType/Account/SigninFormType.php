<?php
namespace Zayso\CoreBundle\FormType\Account;

use Zayso\CoreBundle\Component\DataTransformer\PasswordTransformer;

use Zayso\CoreBundle\Component\FormValidator\UserNamePassValidator;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class SigninFormType extends AbstractType
{
    public function getName() { return 'accountSignin'; }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('userName', 'text',     array('label' => 'User Name, Email or AYSOID:', 'attr' => array('size' => 20)));
        $builder->add('userPass', 'password', array('label' => 'Password:', 'attr' => array('size' => 15)));
        
        //$builder->addValidator(new UserNamePassValidator($this->em));

        //$builder->get('userPass')->appendClientTransformer(new PasswordTransformer());
    }
}