<?php

namespace Zayso\NatGamesBundle\Component\Form\Type\Account;

use Zayso\CoreBundle\Component\DataTransformer\DateTransformer;
use Zayso\CoreBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\CoreBundle\Component\DataTransformer\AysoidTransformer;
use Zayso\CoreBundle\Component\DataTransformer\RegionTransformer;
use Zayso\CoreBundle\Component\DataTransformer\PasswordTransformer;

use Zayso\NatGamesBundle\Component\Form\Type\Account\AccountBaseFormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class AccountProfileAysoFormType extends AccountBaseFormType
{
    protected $name  = 'accountProfileAyso';
    protected $group = 'edit';
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('aysoid',   'text', array('label' => 'AYSO ID',             'attr' => array('readonly' => true,'size' => 10)));
        $builder->add('region',   'text', array('label' => 'AYSO Region Number',  'attr' => array('readonly' => true,'size' =>  6)));
        $builder->add('memYear',  'text', array('label' => 'AYSO Membership Year','attr' => array('readonly' => true,'size' =>  8),'required' => false,));
        $builder->add('safeHaven','text', array('label' => 'AYSO Safe Haven',     'attr' => array('readonly' => true,'size' => 16),'required' => false,));
        $builder->add('refBadge', 'text', array('label' => 'AYSO Referee Badge',  'attr' => array('readonly' => true,'size' => 16),'required' => false,));
        $builder->add('refDate',  'text', array('label' => 'AYSO Referee Date',   'attr' => array('readonly' => true,'size' => 16),'required' => false,));

        $builder->add('accountPersonId', 'hidden', array('property_path' => false, 'data' => $builder->getData()->getId()));

        $builder->get('region' )->appendClientTransformer(new RegionTransformer());
        $builder->get('aysoid' )->appendClientTransformer(new AysoidTransformer());
        $builder->get('refDate')->appendClientTransformer(new DateTransformer());
    }
}
