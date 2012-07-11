<?php
namespace Zayso\ZaysoBundle\Component\Form\Type;

use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormError;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('firstName', 'text', array('label' => 'Legal First Name'));
        $builder->add('lastName',  'text', array('label' => 'Legal Last  Name'));
        $builder->add('nickName',  'text', array('label' => 'Nick Name'));
        $builder->add('email',     'text', array('label' => 'Email'));
    }
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Zayso\ZaysoBundle\Entity\Person',
        );
    }
    public function getName()
    {
        return 'person';
    }
}

?>
