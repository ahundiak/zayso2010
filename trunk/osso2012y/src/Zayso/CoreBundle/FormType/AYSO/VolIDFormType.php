<?php
namespace Zayso\CoreBundle\FormType\AYSO;

use Zayso\CoreBundle\DataTransformer\AYSO\VolIDTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VolIDFormType extends AbstractType
{
    protected $manager = null;
    public function __construct($manager) { $this->manager = $manager; }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new VolIDTransformer($this->manager);
        $builder->addModelTransformer($transformer);
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'Invalid id',
            'label'           => 'AYSO Volunteer ID (8-digits)',
            'attr'            => array('size' => 10)
        ));
    }
    public function getParent() { return 'text'; }
    public function getName()   { return 'zayso_core_ayso_vol_id'; }
}

?>
