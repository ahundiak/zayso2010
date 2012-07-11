<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Zayso\ZaysoBundle\Component\Form\Type;

use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormError;

use Zayso\ZaysoBundle\Component\Form\Type\PersonType;

class AccountPersonType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('status', 'text', array('label' => 'Member Status'));
        $builder->add('person', new PersonType());
    }
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Zayso\ZaysoBundle\Entity\AccountPerson',
        );
    }
    public function getName()
    {
        return 'accountPerson';
    }
}

?>
