<?php
/* =======================================================================
 * This works reasonably well
 * Probably want to add a default project
 */
namespace Zayso\CoreBundle\Component\FormType\Admin\Person;

use Zayso\CoreBundle\Component\DataTransformer\AysoidTransformer;
use Zayso\CoreBundle\Component\DataTransformer\RegionTransformer;

use Zayso\CoreBundle\Component\FormValidator\RegionValidator;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class ProjectEditFormType extends AbstractType
{
    protected $manager = null;
    
    public function __construct($manager)
    {
        $this->manager = $manager;
    }
    public function getName() { return 'personProjectEdit'; }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('project','entity', array(
            'label'    => 'Current Project',
            'class'    => 'ZaysoCoreBundle:Project',
            'property' => 'description',
            
            'empty_value' => 'Choose Project',
            'required'    => false,
            
            'em'            => $this->manager->getEntityManagerName(),
            'query_builder' => $this->manager->qbActiveProjects(),
        )); 
    }
}
