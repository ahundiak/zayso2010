<?php
namespace Zayso\CoreBundle\Component\FormType\Admin\Person;

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

class PersonEditFormType extends AbstractType
{
    protected $manager = null;
    
    public function __construct($manager)
    {
        $this->manager = $manager;
    }
    public function getName() { return 'personEdit'; }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('firstName', 'text', array('label' => 'First Name'));
        $builder->add('lastName',  'text', array('label' => 'Last Name'));
        $builder->add('nickName',  'text', array('label' => 'Nick Name', 'required' => false,));
        
        $builder->add('gender', 'choice', array(
            'label'    => 'Gender',
            'required' => true,
            'choices'  => array('N' => 'None', 'M' => 'Male', 'F' => 'Female'),
        ));
        $builder->add('dob',       'text', array('label' => 'Date of Birth',      'attr' => array('size' => 8),'required' => false,));

        $builder->add('email',     'text', array('label' => 'Email',      'attr' => array('size' => 35),'required' => false,));
        $builder->add('cellPhone', 'text', array('label' => 'Cell Phone', 'attr' => array('size' => 20),'required' => false,));
        
        $builder->add('regAYSO',              new AYSOEditFormType($this->manager));
        $builder->add('currentProjectPerson', new ProjectEditFormType($this->manager));
        
/*
        $builder->add('aysoid',    'text', array('label' => 'AYSO ID',            'read_only' => true, 'attr' => array('size' => 10)));
        $builder->add('orgKey',    'text', array('label' => 'AYSO Region Number', 'read_only' => true, 'attr' => array('size' => 4)));

        $builder->add('refBadge', 'choice', array(
            'label'         => 'AYSO Referee Badge',
            'required'      => true,
            'choices'       => $this->refBadgePickList,
            'read_only'     => true,
        ));
 * */

        $builder->get('cellPhone')->appendClientTransformer(new PhoneTransformer());
        
        //$builder->get('orgKey'   )->appendClientTransformer(new RegionTransformer());
        //$builder->get('aysoid'   )->appendClientTransformer(new AysoidTransformer());
 
    }
    protected $refBadgePickList = array
    (
        'None'         => 'None',
        'Regional'     => 'Regional',
        'Intermediate' => 'Intermediate',
        'Advanced'     => 'Advanced',
        'National'     => 'National',
        'National 2'   => 'National 2',
        'Assistant'    => 'Assistant',
        'U8 Official'  => 'U8',
    );
}
