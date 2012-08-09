<?php

namespace Zayso\CoreBundle\FormType\Misc;

use Zayso\CoreBundle\DataTransformer\PhoneTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PhoneFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new PhoneTransformer();
        $builder->addModelTransformer($transformer);
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'The phone number is incorrect',
        ));
    }
    public function getParent() { return 'text'; }
    public function getName()   { return 'phone_edit'; }
}

?>
