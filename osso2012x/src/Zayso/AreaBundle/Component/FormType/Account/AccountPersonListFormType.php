<?php
namespace Zayso\NatGamesBundle\Component\Form\Type\Account;

use Zayso\ZaysoBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\AysoidTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\RegionTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\PasswordTransformer;

use Zayso\ZaysoBundle\Component\Form\Validator\UserNameValidator;

use Zayso\NatGamesBundle\Component\Form\Type\Account\AccountBaseFormType;
use Zayso\NatGamesBundle\Component\Form\Type\Account\AccountPersonListItemFormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class AccountPersonListFormType extends AccountBaseFormType
{
    protected $name  = 'accountPersonList';
    protected $group = '';
    
    public function __construct($em,$type)
    {
        $this->em = $em;
        $this->itemType = $type;
    }
    public function buildForm(FormBuilder $builder, array $options)
    {
      //$builder->add('members', 'collection', array('type' => new AccountPersonListItemFormType($this->em)));        
        $builder->add('members', 'collection', array('type' => $this->itemType));        
    }
}
