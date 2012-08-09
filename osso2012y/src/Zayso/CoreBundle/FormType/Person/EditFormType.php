<?php
namespace Zayso\CoreBundle\FormType\Person;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EditFormType extends AbstractType
{
    protected $manager = null;
    
    public function __construct($manager)
    {
        $this->manager = $manager;
    }
    public function getName()   { return 'person_edit'; }
    public function getParent() { return 'form'; }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', 'text', array('label' => 'AYSO First Name'));
        $builder->add('lastName',  'text', array('label' => 'AYSO Last Name'));
        $builder->add('nickName',  'text', array('label' => 'Nick Name', 'required' => false,));

        $builder->add('email',     'text', array('label' => 'Email',      'attr' => array('size' => 35)));
        $builder->add('cellPhone', 'phone_edit', array('label' => 'Cell Phone', 'attr' => array('size' => 20),'required' => false,));
        
        // Read only works for the text fields but not the select fields
        // Disabled works as expected though cannot copy/paste
        $builder->add('regAYSOV', 'zayso_core_ayso_vol', array('read_only' => false, 'disabled' => false));

 
    }
}
