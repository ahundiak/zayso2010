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

class AccountCreateType extends AbstractType
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

        $builder->add('userPass1', 'password', array('property_path' => 'userPass', 'label' => 'Password'));
        $builder->add('userPass2', 'password', array('property_path' => false,      'label' => 'Password(confirm)'));

        $builder->add('firstName', 'text', array('label' => 'AYSO First Name'));
        $builder->add('lastName',  'text', array('label' => 'AYSO Last Name'));
        $builder->add('nickName',  'text', array('label' => 'Nick Name', 'required' => false,));

        $builder->add('aysoid',    'text', array('label' => 'AYSO ID',    'attr' => array('size' => 10)));
        $builder->add('email',     'text', array('label' => 'Email',      'attr' => array('size' => 35)));
        $builder->add('cellPhone', 'text', array('label' => 'Cell Phone', 'attr' => array('size' => 20),'required' => false,));
        $builder->add('region',    'text', array('label' => 'AYSO Region Number', 'attr' => array('size' => 4)));

        $builder->add('refBadge', 'choice', array(
            'label'         => 'AYSO Referee Badge',
            'required'      => true,
            'choices'       => $this->refBadgePickList,
        ));
        $builder->addValidator(new CallbackValidator(function($form)
        {
            if($form['userPass1']->getData() != $form['userPass2']->getData())
            {
                $form['userPass2']->addError(new FormError('Passwords do not match'));
            }
        }));
        $builder->addValidator(new UserNameValidator($this->em));

        $builder->get('userPass1')->appendClientTransformer(new PasswordTransformer());
        $builder->get('userPass2')->appendClientTransformer(new PasswordTransformer());
        $builder->get('cellPhone')->appendClientTransformer(new PhoneTransformer());
        $builder->get('region'   )->appendClientTransformer(new RegionTransformer());
        $builder->get('aysoid'   )->appendClientTransformer(new AysoidTransformer());
    }
    public function getName()
    {
        return 'accountCreate';
    }
}
class CreateController extends BaseController
{
    public function createAction(Request $request)
    {
        // New form stuff
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->newAccountPerson(array('projectId' => $this->getProjectId()));

        $accountData = $accountPerson; // new AccountCreateData();
        $accountType = new AccountCreateType($this->getEntityManager());

        $form = $this->createForm($accountType, $accountPerson);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $account = $this->createAccount($accountPerson);
                
                if ($account) return $this->redirect($this->generateUrl('_natgames_home'));
                
                // Try again!
                // return $this->redirect($this->generateUrl('_natgames_account_create'));
            }
        }
        $tplData = $this->getTplData();
        $tplData['form'] = $form->createView();

        return $this->render('NatGamesBundle:Account:create.html.twig',$tplData);
    }
    public function createAccount($accountPerson)
    {
        $accountManager = $this->getAccountManager();
        $em = $accountManager->getEntityManager();

        $person = $accountManager->getPerson(array('projectId' => $this->getProjectId(),'aysoid' => $accountPerson->getAysoid()));
        //die('Count ' . count($person->getMembers()));
        if ($person) {
            $accountPerson->setPerson($person);
            // $projectPerson = $person->getNatGamesProjectPerson();
            //die($projectPerson->getId() . ' ' . $projectPerson->getPerson()->getId() . ' ' . $projectPerson->getProject()->getId());
        }
        else
        {
            // Check for same person, different project
            // If found copy existing newProjectPerson and set it
        }
        // $members = $accountPerson->getPerson()->getMembers();
        //die('Count ' . count($accountPerson->getPerson()->getNatGamesProjectPerson()->getProject()->getPersons()));

        $em->persist($accountPerson);
      //$em->persist($accountPerson->getAccount());
      //$em->persist($accountPerson->getPerson());
      //$em->persist($accountPerson->getPerson()->getNatGamesProjectPerson());
      //$em->persist($accountPerson->getPerson()->getAysoRegisteredPerson());

        // Should be try/catch
        $em->flush();

        // Signin
        $account = $accountPerson->getAccount();
        $member  = $accountPerson; // $account->getPrimaryMember();
        $userData = array
        (
            'accountId' => $account->getId(),
            'memberId'  => $member->getId(),
          //'personId'  => $member->getPerson()->getId(),
            'projectId' => $this->getProjectId(),
        );
        $this->getSession()->set('userData',$userData);

        // Also save signin information
        $accountSigninData = array
        (
            'userName' => $account->getUserName(),
            'userPass' => '',
        );
        $this->getSession()->set('accountSigninData',$accountSigninData);
    
        return $account;
        
        return $this->redirect($this->generateUrl('_natgames_home'));
  }
  /*
   *             $message = \Swift_Message::newInstance();
            $message->setSubject('Hello Email');
            $message->setFrom('ahundiak@zayso.org');
            $message->setTo  ('ahundiak@gmail.com');
            $message->setBody('The Body');
//      ->setBody($this->renderView('HelloBundle:Hello:email.txt.twig', array('name' => $name)))

            // $this->get('mailer')->send($message);

   */
}
