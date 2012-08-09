<?php
namespace Zayso\CoreBundle\FormType\AYSO;

use Zayso\CoreBundle\DataTransformer\AYSO\RegionTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegionFormType extends AbstractType
{
    protected $manager = null;
    public function __construct($manager) { $this->manager = $manager; }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new RegionTransformer($this->manager);
        $builder->addModelTransformer($transformer);
        
      // Nopes, services not allowed
      //$builder->addModelTransformer('zayso_core_ayso_region_data_transformer');
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'Unknown region number',
            'label'           => 'AYSO Region',
            'attr'            => array('size' => 4)
        ));
    }
    public function getParent() { return 'text'; }
    public function getName()   { return 'zayso_core_ayso_region'; }
}

?>
