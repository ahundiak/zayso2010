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

class AccountCreateData implements \ArrayAccess
{
    /** @Assert\NotBlank() */
    public $userName;

    /** @Assert\NotBlank() */
    public $userPass1;

    /** @Assert\NotBlank() */
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

    public $refBadge = 'None';
    public $projectId = 52;
    
    /**
     * Assert\True(message = "Passwords do not match")
     * Results in a form (not specific field) error
     */
    public function isPasswordLegal()
    {
        if ( $this->userPass1 != $this->userPass2) return false;
        return true;
    }
    // Array access
    public function offsetGet   ($name) { return $this->$name; }
    public function offsetExists($name) { return isset($this->$name); }
    public function offsetSet   ($name, $value) { $this->$name = $value; }
    public function offsetUnset ($name) { unset($this->$name); }
}
class UserNameValidator implements FormValidatorInterface
{
    public function __construct($em)
    {
        $this->em = $em;
    }
    public function validate(FormInterface $form)
    {
        $userName = $form['userName']->getData();

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
class AccountCreateType extends AbstractType
{
    public function __construct($em,$refBadgePickList)
    {
        $this->em = $em;
        $this->refBadgePickList = $refBadgePickList;
    }
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('userName', 'text', array('label' => 'User Name', 'attr' => array('size' => 35)));

        $builder->add('userPass1', 'password', array('label' => 'Password'));
        $builder->add('userPass2', 'password', array('label' => 'Password(confirm)'));

        $builder->add('firstName', 'text', array('label' => 'AYSO First Name'));
        $builder->add('lastName',  'text', array('label' => 'AYSO Last Name'));
        $builder->add('nickName',  'text', array('label' => 'Nick Name'));

        $builder->add('aysoid',    'text', array('label' => 'AYSO ID',    'attr' => array('size' => 10)));
        $builder->add('email',     'text', array('label' => 'Email',      'attr' => array('size' => 35)));
        $builder->add('cellPhone', 'text', array('label' => 'Cell Phone', 'attr' => array('size' => 20)));
        $builder->add('region', 'integer', array('label' => 'AYSO Region Number', 'attr' => array('size' => 4)));

        $builder->add('projectId','hidden');
        $builder->add('projectIdx','hidden',array('data' => 123, 'property_path' => false));

        $builder->add('refBadge', 'choice', array(
            'label'         => 'AYSO Referee Badge',
            'required'      => true,
          //'empty_value'   => false,
            'choices'       => $this->refBadgePickList,
        ));
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
        $builder->addValidator(new UserNameValidator($this->em));
    }
    public function getName()
    {
        return 'accountCreate';
    }
}
class EditController extends BaseController
{
    public function createAction(Request $request)
    {
        $refBadgePickList = array
        (
            'None'         => 'None',
            'Regional'     => 'Regional',
            'Intermediate' => 'Intermediate',
            'Advanced'     => 'Advanced',
            'National'     => "National",
            'National 2'   => 'National 2',
            'Assistant'    => 'Assistant',
            'U8 Official'  => 'U8',
        );

        // New form stuff
        $accountData = new AccountCreateData();
        $accountType = new AccountCreateType($this->getEntityManager(),$refBadgePickList);

        $form = $this->createForm($accountType, $accountData);

        if ($request->getMethod() == 'POST')
        {
            $message = \Swift_Message::newInstance();
            $message->setSubject('Hello Email');
            $message->setFrom('ahundiak@zayso.org');
            $message->setTo  ('ahundiak@gmail.com');
            $message->setBody('The Body');
//      ->setBody($this->renderView('HelloBundle:Hello:email.txt.twig', array('name' => $name)))
    
            $this->get('mailer')->send($message);

            $form->bindRequest($request);

            if ($form->isValid())
            {
                // perform some action, such as saving the task to the database
                return $this->redirect($this->generateUrl('_natgames_account_create'));
            }
        }
        $tplData = $this->getTplData();
        $tplData['form'] = $form->createView();

        return $this->render('NatGamesBundle:Account:create.html.twig',$tplData);
    }
    public function createPostAction()
    {
        $em = $this->getEntityManager();

        $request = $this->getRequest();
    
        $accountCreateData = $request->request->get('accountCreateData');

        // Really don't want to store the passwords
        $accountCreateDatax = $accountCreateData;
        $accountCreateDatax['userPass1'] = '';
        $accountCreateDatax['userPass2'] = '';
        $session = $this->getRequest()->getSession();
        $session->set('accountCreateData',$accountCreateDatax);

        $accountRepo = $em->getRepository('ZaysoBundle:Account');
        $account = $accountRepo->create($accountCreateData);
        if (is_array($account))
        {
            $session->setFlash('errors',$account);
            return $this->redirect($this->generateUrl('_natgames_account_create'));
        }

        // Setup project person
        $projectId = $this->getProjectId();
        $personId  = $account->getPrimaryPersonId();

        $projectRepo = $em->getRepository('ZaysoBundle:Project');

        $projectPerson = $projectRepo->loadProjectPerson($projectId,$personId);
        $projectPerson->set('accountCreateData',$accountCreateDatax);

        $todo = array('projectPlans' => true, 'openid' => true, 'projectLevels' => true);
        $projectPerson->set('todo',$todo);
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
        $session->set('userData',$userData);

        // Also save signin information
        $accountSigninData = array
        (
            'userName' => $account->getUserName(),
            'userPass' => '',
        );
        $session->set('accountSigninData',$accountSigninData);
        //
        //print_r($accountCreateData); die();
    
        return $this->redirect($this->generateUrl('_natgames_home'));
  }
}
