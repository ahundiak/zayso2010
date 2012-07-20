<?php
namespace Zayso\CoreBundle\Component\FormType\Person;

use Zayso\CoreBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\CoreBundle\Component\DataTransformer\AysoidTransformer;
use Zayso\CoreBundle\Component\DataTransformer\RegionTransformer;

use Zayso\CoreBundle\Component\FormValidator\RegionValidator;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class PersonSearchFormType extends AbstractType
{
    public function getName() { return 'personSearch'; }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('firstName', 'text', array('required' => false, 'label' => 'AYSO First Name'));
        $builder->add('lastName',  'text', array('required' => false, 'label' => 'AYSO Last Name'));
        $builder->add('nickName',  'text', array('required' => false, 'label' => 'Nick Name'));
        
        $builder->add('aysoid',    'text', array('required' => false, 'label' => 'AYSO ID',     'attr' => array('size' => 10)));
        $builder->add('region',    'text', array('required' => false, 'label' => 'AYSO Region', 'attr' => array('size' => 4)));

        $builder->get('region')->appendClientTransformer(new RegionTransformer());
        $builder->get('aysoid')->appendClientTransformer(new AysoidTransformer());
 
    }
}
