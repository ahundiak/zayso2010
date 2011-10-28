<?php

namespace Zayso\NatGamesBundle\Controller\Account;

use Zayso\NatGamesBundle\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;
use Symfony\Component\Form\DataTransformerInterface;

class PhoneTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (!$value) return $value;

        return substr($value,0,3) . '.' . substr($value,3,3) . '.' . substr($value,6,4);
    }
    public function reverseTransform($value)
    {
        return preg_replace('/\D/','',$value);
    }
}
class AccountEditData implements \ArrayAccess
{
    /** @Assert\NotBlank() */
    public $userName;

    public $userPass1;
    public $userPass2;

    /** @Assert\NotBlank() */
    public $firstName;

    /** @Assert\NotBlank() */
    public $lastName;

    public $nickName;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^(AYSOV)?\d{8}$/",
     *     message="Must be 8-digit number")
     */
    public $aysoid;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    public $cellPhone;

    /** @Assert\NotBlank() */
    public $region;

    public $refBadge  = 'None';
    public $refDate   = '';
    public $safeHaven = '';
    public $memYear   = '';
    public $dob       = '';
    public $gender    = '';

    public $projectId = 52;

    public function bind($accountPerson)
    {
        $this->userName  = $accountPerson->getUserName();
        $this->firstName = $accountPerson->getFirstName();
        $this->lastName  = $accountPerson->getLastName();
        $this->nickName  = $accountPerson->getNickName();
        $this->aysoid    = $accountPerson->getAysoid();
        $this->email     = $accountPerson->getEmail();
        $this->cellPhone = $accountPerson->getCellPhone();
    }
    // Array access
    public function offsetGet   ($name) { return $this->$name; }
    public function offsetExists($name) { return isset($this->$name); }
    public function offsetSet   ($name, $value) { $this->$name = $value; }
    public function offsetUnset ($name) { unset($this->$name); }
}
class AccountEditType extends AbstractType
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
    public function __construct($em)
    {
        $this->em = $em;
    }
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('userName', 'text', array('label' => 'User Name', 'attr' => array('size' => 35)));

        $builder->add('userPass1', 'password', array('property_path' => false, 'label' => 'Password'));
        $builder->add('userPass2', 'password', array('property_path' => false, 'label' => 'Password(confirm)'));

        $builder->add('firstName', 'text', array('label' => 'AYSO First Name'));
        $builder->add('lastName',  'text', array('label' => 'AYSO Last Name'));
        $builder->add('nickName',  'text', array('label' => 'Nick Name'));

        $builder->add('aysoid',    'text', array('label' => 'AYSO ID',    'attr' => array('size' => 10)));
        $builder->add('email',     'text', array('label' => 'Email',      'attr' => array('size' => 35)));
        $builder->add('cellPhone', 'text', array('label' => 'Cell Phone', 'attr' => array('size' => 20)));
        $builder->add('region',    'text', array('property_path' => false, 'label' => 'AYSO Region Number', 'attr' => array('size' => 4)));

//        $builder->add('projectId','hidden');
      //$builder->add('projectIdx','hidden',array('data' => 123, 'property_path' => false));

        $builder->add('refBadge', 'choice', array(
            'label'         => 'AYSO Referee Badge',
            'required'      => true,
          //'empty_value'   => false,
            'choices'       => $this->refBadgePickList,
            'property_path' => false,
        ));
        /*
        $builder->addValidator(new CallbackValidator(function($form)
        {
            if($form['userPass1']->getData() != $form['userPass2']->getData())
            {
                $form['userPass2']->addError(new FormError('Passwords do not match'));
            }
        }));
        $builder->addValidator(new CallbackValidator(function($form)
        {
            $region = (int)$form['region']->getData();
            if (($region < 1) || ($region > 1999))
            {
                $form['region']->addError(new FormError('Invalid region number'));
            }
        }));
        $builder->addValidator(new AccountUserNameValidator($this->em));

         */
        $builder->get('cellPhone')->appendClientTransformer(new PhoneTransformer());
    }
    public function getName()
    {
        return 'account';
    }
}

class EditController extends BaseController
{
    public function editAction(Request $request, $id)
    {
        // Verify authorized to edit this account

        // New form stuff
        $accountData = new AccountEditData();
        $accountData->projectId = $this->getProjectId();

        $accountType = new AccountEditType($this->getEntityManager());

        //die($id);
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->getAccountPerson(array('accountPersonId' => $id, 'projectId' => $this->getProjectId()));
        if (!$accountPerson)
        {
            die('Invalid account person id ' . $id);
        }
        $accountData = $accountPerson;
        
        $form = $this->createForm($accountType, $accountData);

        if ($request->getMethod() == 'POST')
        {

            $form->bindRequest($request);

            if ($form->isValid())
            {
                $accountManager->getEntityManager()->flush();
                
                return $this->redirect($this->generateUrl('_natgames_admin_accounts'));
            }
        }
        $tplData = $this->getTplData();
        $tplData['id']   = $id;
        $tplData['form'] = $form->createView();

        return $this->render('NatGamesBundle:Account:edit.html.twig',$tplData);
    }
    public function createAccount($accountCreateData)
    {
        $em = $this->getEntityManager();

        $accountRepo = $em->getRepository('ZaysoBundle:Account');
        $account = $accountRepo->create($accountCreateData);
        if (is_array($account))
        {
            return null; // Hopefully never happens
        }

        // Setup project person
        $projectId = $this->getProjectId();
        $personId  = $account->getPrimaryPersonId();

        $projectRepo = $em->getRepository('ZaysoBundle:Project');

        $projectPerson = $projectRepo->loadProjectPerson($projectId,$personId);
        
        // Save initial creation information sans password
        $alreadyHave = $projectPerson->get('accountCreateData');
        if (!$alreadyHave)
        {
            $accountCreateDatax = $accountCreateData;
            $accountCreateDatax['userPass1'] = '';
            $accountCreateDatax['userPass2'] = '';
            $projectPerson->set('accountCreateData',$accountCreateDatax);
        }
        // Same for todo
        $alreadyHave = $projectPerson->get('todo');
        if (!$alreadyHave)
        {
            $todo = array('projectPlans' => true, 'openid' => true, 'projectLevels' => true);
            $projectPerson->set('todo',$todo);
        }
        $em->flush();

        // Signin
        $member = $account->getPrimaryMember();
        $userData = array
        (
            'accountId' => $account->getId(),
            'memberId'  => $member->getId(),
            'personId'  => $personId,
            'projectId' => $projectId,
        );
        $this->getSession()->set('userData',$userData);

        // Also save signin information
        $accountSigninData = array
        (
            'userName' => $account->getUserName(),
            'userPass' => '',
        );
        $this->getSession()->set('accountSigninData',$accountSigninData);
        //
        //print_r($accountCreateData); die();
    
        return $account;
        
        return $this->redirect($this->generateUrl('_natgames_home'));
  }
}
