<?php
namespace Zayso\CoreBundle\Component\FormType\Account;

use Zayso\CoreBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\CoreBundle\Component\DataTransformer\AysoidTransformer;
use Zayso\CoreBundle\Component\DataTransformer\RegionTransformer;
use Zayso\CoreBundle\Component\DataTransformer\PasswordTransformer;

use Zayso\CoreBundle\Component\FormValidator\UserNameValidator;
use Zayso\CoreBundle\Component\FormValidator\RegionValidator;

use Zayso\CoreBundle\Component\FormType\Account\AccountBaseFormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class AccountCreateFormType extends AccountBaseFormType
{
    protected $name  = 'accountCreate';
    protected $group = 'create';
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('userName', 'text', array('label' => 'Zayso User Name', 'attr' => array('size' => 35)));

        $builder->add('userPass1', 'password', array('property_path' => 'userPass', 'label' => 'Zayso Password'));
        $builder->add('userPass2', 'password', array('property_path' => false,      'label' => 'Zayso Password(confirm)'));

        $builder->add('firstName', 'text', array('label' => 'AYSO First Name'));
        $builder->add('lastName',  'text', array('label' => 'AYSO Last Name'));
        $builder->add('nickName',  'text', array('label' => 'Nick Name', 'required' => false,));

        $builder->add('aysoid',    'text', array('label' => 'AYSO ID',    'attr' => array('size' => 10)));
        $builder->add('email',     'text', array('label' => 'Email',      'attr' => array('size' => 35)));
        $builder->add('cellPhone', 'text', array('label' => 'Cell Phone', 'attr' => array('size' => 20),'required' => false,));
        $builder->add('region',    'text', array('label' => 'AYSO Region Number', 'attr' => array('size' => 4)));

        $builder->add('refBadge', 'choice', array(
            'label'         => 'AYSO Referee Badge',
            'required'      => true,
            'choices'       => $this->refBadgePickList,
        ));
        
        $builder->add('openidDisplayName', 'text', array('label' => 'Social Network Display Name',
            'attr' => array('required' => false, 'readonly' => true)));
        
        $builder->add('openidUserName', 'text', array('label' => 'Social Network User Name',
            'attr' => array('required' => false, 'readonly' => true)));
        
        $builder->add('openidProvider', 'text', array('label' => 'Social Network Provider',
            'attr' => array('required' => false, 'readonly' => true)));

        $builder->addValidator(new CallbackValidator(function($form)
        {
            if($form['userPass1']->getData() != $form['userPass2']->getData())
            {
                $form['userPass2']->addError(new FormError('Passwords do not match'));
            }
        }));
        $builder->addValidator(new UserNameValidator($this->em));
        $builder->addValidator(new RegionValidator  ($this->em));

        $builder->get('userPass1')->appendClientTransformer(new PasswordTransformer());
        $builder->get('userPass2')->appendClientTransformer(new PasswordTransformer());
        $builder->get('cellPhone')->appendClientTransformer(new PhoneTransformer());
        $builder->get('region'   )->appendClientTransformer(new RegionTransformer());
        $builder->get('aysoid'   )->appendClientTransformer(new AysoidTransformer());
    }
}
