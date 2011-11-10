<?php

namespace Zayso\NatGamesBundle\Controller\Account;

use Zayso\NatGamesBundle\Controller\BaseController;

use Zayso\ZaysoBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\AysoidTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\RegionTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\PasswordTransformer;

use Zayso\ZaysoBundle\Component\Form\Validator\UserNameValidator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class AccountPersonAddFormType extends AbstractType
{
    protected $refBadgePickList = array
    (
        'None'         => 'None',
        'Regional'     => 'Regional',
        'Intermediate' => 'Intermediate',
        'Advanced'     => 'Advanced',
        'National'     => 'National',
        'National 2'   => 'National 2',
        'Assistant'    => 'Assistant',
        'U8 Official'  => 'U8',
    );
    protected $accountRelationPickList = array
    (
        'Family' => 'Family - Full control over account',
        'Peer'   => 'Peer - Only to signup for games'
    );

    public function __construct($em) { $this->em = $em; }
    public function getName() { return 'accountPersonAdd'; }
    public function getDefaultOptions(array $options)
    {
        return array('validation_groups' => array('add') );
    }

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
    }
}
class AccountPersonController extends BaseController
{
    public function addAction(Request $request)
    {   
        // New form stuff
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->newAccountPerson(array('projectId' => $this->getProjectId()));
        $accountPerson->setAccountRelation('Family');
        
        $formType = new AccountPersonAddFormType($this->getEntityManager());

        $form = $this->createForm($formType, $accountPerson);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $accountPerson = $this->addAccountPerson($accountPerson);
                
                //if ($accountPerson) return $this->redirect($this->generateUrl('_natgames_account_profile'));
                
            }
            else die('Not validated');
        }
        $tplData = $this->getTplData();
        $tplData['form'] = $form->createView();

        return $this->render('NatGamesBundle:Account\Person:add.html.twig',$tplData);
    }
    public function addAccountPerson($accountPerson)
    {   
        $accountManager = $this->getAccountManager();
        $em = $accountManager->getEntityManager();

        // Set to active account
        $account = $this->getUser()->getAccount();
        $accountPerson->setAccount($account);

        // Replace person if exists
        $person = $accountManager->getPerson(array('projectId' => $this->getProjectId(),'aysoid' => $accountPerson->getAysoid()));
        if ($person)
        {
            // Need to ensure same person is not attached to account more than once
            if ($account->getPersonForId($person->getId())) return $accountPerson;

            // Use it
            $accountPerson->setPerson($person);
        }
        // Should be try/catch
        $em->persist($accountPerson);
        $em->flush();

        return $accountPerson;
  }
}
