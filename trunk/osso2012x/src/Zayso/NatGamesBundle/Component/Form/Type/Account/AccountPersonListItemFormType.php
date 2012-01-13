<?php
namespace Zayso\NatGamesBundle\Component\Form\Type\Account;

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

class AccountPersonListItemFormType extends AccountBaseFormType
{
    protected $name  = 'accountPersonListItem';
  //protected $group = 'add';
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('accountRelation', 'text', array('attr' => array('readonly' => true,'size' => 5)));
        
        $builder->add('firstName', 'text', array('attr' => array('readonly' => true,'size' => 10)));
        $builder->add('lastName',  'text', array('attr' => array('readonly' => true,'size' => 10)));
        $builder->add('nickName',  'text', array('attr' => array('readonly' => true,'size' => 10),'required' => false,));

        $builder->add('aysoid',    'text', array('attr' => array('readonly' => true,'size' =>  6)));
        $builder->add('email',     'text', array('attr' => array('readonly' => true,'size' => 35)));
        $builder->add('cellPhone', 'text', array('attr' => array('readonly' => true,'size' => 20),'required' => false,));
        $builder->add('region',    'text', array('attr' => array('readonly' => true,'size' =>  3)));
        
        $builder->add('memYear',   'text', array('attr' => array('readonly' => true,'size' =>  4),'required' => false,));
        $builder->add('safeHaven', 'text', array('attr' => array('readonly' => true,'size' =>  8),'required' => false,));
        $builder->add('refBadge',  'text', array('attr' => array('readonly' => true,'size' =>  8)));

        $builder->get('cellPhone')->appendClientTransformer(new PhoneTransformer());
        $builder->get('region'   )->appendClientTransformer(new RegionTransformer());
        $builder->get('aysoid'   )->appendClientTransformer(new AysoidTransformer());
    }
}
