<?php
namespace Zayso\CoreBundle\Component\FormType\Person;

use Zayso\CoreBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\CoreBundle\Component\DataTransformer\AysoidTransformer;
use Zayso\CoreBundle\Component\DataTransformer\RegionTransformer;
use Zayso\CoreBundle\Component\DataTransformer\PasswordTransformer;

use Zayso\CoreBundle\Component\FormValidator\RegionValidator;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class PersonTeamListFormType extends AbstractType
{
    protected $name  = 'personTeamList';
    protected $group = 'edit';
    
    protected $manager   = null;
    protected $projectId = null;

    public function __construct($manager,$projectId)   
    { 
        $this->manager   = $manager;
        $this->projectId = $projectId;
    }
    
    public function getName() { return $this->name; }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $manager = $this->manager;
        
        $builder->add('teamRels', 'collection', array(
            'type'         => new PersonTeamEditFormType($manager,$this->projectId),
          //'allow_add'    => true,
          //'by_reference' => false,
        ));
        
    }
}
