<?php
namespace Zayso\ZaysoBundle\Component\Form\Type;

use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormError;

use Zayso\ZaysoBundle\Component\Form\Type\AccountPersonType;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('userName', 'text', array('label' => 'User Name'));
        
        $builder->add('userPass', 'password', array('label' => 'Password'));

        $builder->add('userPassConfirm', 'password', array(
            'label' => 'Password(confirm)',
            'property_path' => false
        ));
        $builder->add('refBadge', 'choice', array(
            'choices'       => array('1' => 'Regional', '2' => 'Intermediate', '3' => 'Advanced'),
            'required'      => false,
            'property_path' => false,
            'label'         => 'Referee Badge',
            'empty_value'   => 'Choose an option',
        ));

        $builder->add('status', 'text', array('label' => 'Account Status'));

        $builder->add('accountPerson', new AccountPersonType());

        $builder->addValidator(new CallbackValidator(function($form)
        {
            if($form['userPassConfirm']->getData() != $form['userPass']->getData())
            {
                $form['userPassConfirm']->addError(new FormError('Passwords do not match'));
            }
        }));

    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Zayso\ZaysoBundle\Entity\Account',
        );
    }
    public function getName()
    {
        return 'account';
    }
}

?>
