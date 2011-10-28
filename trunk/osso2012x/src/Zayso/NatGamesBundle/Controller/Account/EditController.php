<?php

namespace Zayso\NatGamesBundle\Controller\Account;

use Zayso\NatGamesBundle\Controller\BaseController;

use Zayso\ZaysoBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\PasswordTransformer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;
use Symfony\Component\Form\DataTransformerInterface;

class AccountEditUserNameValidator implements FormValidatorInterface
{
    public function __construct($em)
    {
        $this->em = $em;
    }
    public function validate(FormInterface $form)
    {
        // Only check if username was changed
        $userName  = $form['userName']->getData();
        $userNamex = $form['userNamex']->getData();
        if ($userName == $userNamex) return;

        $conn = $this->em->getConnection();

        $sql = 'SELECT id FROM account WHERE user_name = :userName';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('userName' => $userName));
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (isset($row['id']))
        {
            // print_r($row); die(count($row));
            $form['userName']->addError(new FormError('Account Name is already being used. Please select another name.'));
        }
    }
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
        $builder->add('userName', 'text',  array('label' => 'User Name', 'attr' => array('size' => 35)));
        $builder->add('userNamex','hidden',array('data' => $builder->getData()->getUserName(), 'property_path' => false));

        $builder->add('userPass1', 'password', array('property_path' => 'userPass', 'label' => 'Password'));
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
        
        $builder->addValidator(new CallbackValidator(function($form)
        {
            if($form['userPass1']->getData() != $form['userPass2']->getData())
            {
                $form['userPass2']->addError(new FormError('Passwords do not match'));
            }
        }));/*
        $builder->addValidator(new CallbackValidator(function($form)
        {
            $region = (int)$form['region']->getData();
            if (($region < 1) || ($region > 1999))
            {
                $form['region']->addError(new FormError('Invalid region number'));
            }
        }));*/
        $builder->addValidator(new AccountEditUserNameValidator($this->em));

        $builder->get('userPass1')->appendClientTransformer(new PasswordTransformer());
        $builder->get('userPass2')->appendClientTransformer(new PasswordTransformer());
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

        // Load in tha account person
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->getAccountPerson(array('accountPersonId' => $id, 'projectId' => $this->getProjectId()));
        if (!$accountPerson)
        {
            die('Invalid account person id ' . $id);
        }
        
        // Form
        $accountType = new AccountEditType($this->getEntityManager());
        $form = $this->createForm($accountType, $accountPerson);

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
