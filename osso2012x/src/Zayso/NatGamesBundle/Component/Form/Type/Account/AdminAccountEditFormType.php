<?php
namespace Zayso\NatGamesBundle\Component\Form\Type\Account;

use Zayso\CoreBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\CoreBundle\Component\DataTransformer\AysoidTransformer;
use Zayso\CoreBundle\Component\DataTransformer\RegionTransformer;
use Zayso\CoreBundle\Component\DataTransformer\PasswordTransformer;

use Zayso\CoreBundle\Component\FormValidator\UserNameValidator;
use Zayso\CoreBundle\Component\FormValidator\RegionValidator;

use Zayso\NatGamesBundle\Component\Form\Type\Account\AccountBaseFormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class AdminAccountEditFormType extends AccountBaseFormType
{
    protected $name  = 'adminAccountEdit';
    protected $group = 'edit';
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('userName', 'text',  array('label' => 'User Name', 'attr' => array('size' => 35)));
        $builder->add('userNamex','hidden',array('data' => $builder->getData()->getUserName(), 'property_path' => false));

        $builder->add('userPass1', 'password', 
                array('required' => false, 'property_path' => 'userPass', 'label' => 'Password'));
        $builder->add('userPass2', 'password', 
                array('required' => false, 'property_path' => false,      'label' => 'Password(confirm)'));

        $builder->add('firstName', 'text', array('label' => 'AYSO First Name'));
        $builder->add('lastName',  'text', array('label' => 'AYSO Last Name'));
        
        $builder->add('nickName',  'text', array('label' => 'Nick Name','required' => false,));

        $builder->add('aysoid',    'text', array('label' => 'AYSO ID',    'attr' => array('size' => 10)));
        $builder->add('email',     'text', array('label' => 'Email',      'attr' => array('size' => 35)));
        $builder->add('cellPhone', 'text', array('label' => 'Cell Phone', 'attr' => array('size' => 20), 'required' => false,));
        
        $builder->add('region',  'search', array('label' => 'AYSO Region Number', 'attr' => array('size' => 4)));
        
        $builder->add('refDate',   'text', array('label' => 'AYSO Referee Date',  'attr' => array('size' => 8),'required' => false,));
        $builder->add('dob',       'text', array('label' => 'Date of Birth',      'attr' => array('size' => 8),'required' => false,));

        $builder->add('refBadge', 'choice', array(
            'label'         => 'AYSO Referee Badge',
            'required'      => true,
            'choices'       => $this->refBadgePickList,
        ));
        $builder->add('safeHaven', 'choice', array(
            'label'         => 'AYSO Safe Haven',
            'required'      => true,
            'choices'       => $this->safeHavenPickList,
        ));
        $builder->add('memYear', 'choice', array(
            'label'         => 'AYSO Mem Year',
            'required'      => true,
            'choices'       => $this->memYearPickList,
        ));
        $builder->add('gender', 'choice', array(
            'label'         => 'Gender',
            'required'      => true,
            'choices'       => $this->genderPickList,
        ));
       
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
