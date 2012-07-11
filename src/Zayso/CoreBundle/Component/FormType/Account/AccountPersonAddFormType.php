<?php
namespace Zayso\CoreBundle\Component\FormType\Account;

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

class AccountPersonAddFormType extends AccountBaseFormType
{
    protected $name = 'accountPersonAdd';
    protected $group = 'add';

    // Leave for now as Primary should not be a choice
    protected $accountRelationPickList = array
    (
        'Family' => 'Family - Full control over account',
        'Peer'   => 'Peer - Only to signup for games'
    );
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('firstName', 'text', array('label' => 'AYSO First Name'));
        $builder->add('lastName',  'text', array('label' => 'AYSO Last Name'));
        $builder->add('nickName',  'text', array('label' => 'Nick Name', 'required' => false,));

        $builder->add('aysoid',    'text', array('label' => 'AYSO ID',    'attr' => array('size' => 10)));
        $builder->add('email',     'text', array('label' => 'Email',      'attr' => array('size' => 35)));
        $builder->add('cellPhone', 'text', array('label' => 'Cell Phone', 'attr' => array('size' => 20),'required' => false,));
        $builder->add('region',    'text', array('label' => 'AYSO Region Number', 'attr' => array('size' => 4)));

        $builder->add('accountRelation', 'choice', array(
            'label'         => 'Account Relation',
            'required'      => true,
            'choices'       => $this->accountRelationPickList,
        ));
        $builder->add('refBadge', 'choice', array(
            'label'         => 'AYSO Referee Badge',
            'required'      => true,
            'choices'       => $this->refBadgePickList,
        ));
        $builder->get('cellPhone')->appendClientTransformer(new PhoneTransformer());
        $builder->get('region'   )->appendClientTransformer(new RegionTransformer());
        $builder->get('aysoid'   )->appendClientTransformer(new AysoidTransformer());
        
        $builder->addValidator(new RegionValidator($this->em));
 
    }
}
