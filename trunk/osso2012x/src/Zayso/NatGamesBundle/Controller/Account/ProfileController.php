<?php

namespace Zayso\NatGamesBundle\Controller\Account;

use Zayso\NatGamesBundle\Controller\BaseController;

use Zayso\ZaysoBundle\Component\DataTransformer\DateTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\AysoidTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\RegionTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\PasswordTransformer;

use Zayso\ZaysoBundle\Component\Form\Validator\UserNameValidator;

use Zayso\ZaysoBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\CallbackValidator;

class AccountProfileContactFormType extends AbstractType
{
    public function getName() { return 'accountProfileContact'; }
    public function __construct($em) { $this->em = $em; }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('firstName', 'text', array('label' => 'AYSO First Name'));
        $builder->add('lastName',  'text', array('label' => 'AYSO Last Name'));
        $builder->add('nickName',  'text', array('label' => 'Nick Name','required' => false,));

        $builder->add('email',     'text', array('label' => 'Email',      'attr' => array('size' => 35)));
        $builder->add('cellPhone', 'text', array('label' => 'Cell Phone', 'attr' => array('size' => 20), 'required' => false,));

        $builder->add('accountPersonId', 'hidden', array('property_path' => false, 'data' => $builder->getData()->getId()));
        
        $builder->get('cellPhone')->appendClientTransformer(new PhoneTransformer());
    }
}
class AccountProfileAysoFormType extends AbstractType
{
    public function getName() { return 'accountProfileAyso'; }
    public function __construct($em) { $this->em = $em; }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('aysoid',   'text', array('label' => 'AYSO ID',             'attr' => array('readonly' => true,'size' => 10)));
        $builder->add('region',   'text', array('label' => 'AYSO Region Number',  'attr' => array('readonly' => true,'size' =>  6)));
        $builder->add('memYear',  'text', array('label' => 'AYSO Membership Year','attr' => array('readonly' => true,'size' =>  8),'required' => false,));
        $builder->add('safeHaven','text', array('label' => 'AYSO Aafe Haven',     'attr' => array('readonly' => true,'size' => 16),'required' => false,));
        $builder->add('refBadge', 'text', array('label' => 'AYSO Referee Badge',  'attr' => array('readonly' => true,'size' => 16),'required' => false,));
        $builder->add('refDate',  'text', array('label' => 'AYSO Referee Date',   'attr' => array('readonly' => true,'size' => 16),'required' => false,));

        $builder->add('accountPersonId', 'hidden', array('property_path' => false, 'data' => $builder->getData()->getId()));

        $builder->get('region' )->appendClientTransformer(new RegionTransformer());
        $builder->get('aysoid' )->appendClientTransformer(new AysoidTransformer());
        $builder->get('refDate')->appendClientTransformer(new DateTransformer());
    }
}
class AccountProfilePasswordFormType extends AbstractType
{
    public function getName() { return 'accountProfilePassword'; }
    public function __construct($em) { $this->em = $em; }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('userName', 'text',  array('label' => 'User Name', 'attr' => array('size' => 35)));
        $builder->add('userNamex','hidden',array('data' => $builder->getData()->getUserName(), 'property_path' => false));

        $builder->add('userPass1', 'password',
                array('required' => false, 'property_path' => 'userPass', 'label' => 'Password'));
        $builder->add('userPass2', 'password',
                array('required' => false, 'property_path' => false,      'label' => 'Password(confirm)'));

        $builder->add('accountPersonId', 'hidden', array('property_path' => false, 'data' => $builder->getData()->getId()));

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
    }
}
class ProfileController extends BaseController
{
    protected function getContactForm($accountPerson)
    {
        $formType = new AccountProfileContactFormType($this->getEntityManager());
        $form     = $this->createForm($formType, $accountPerson);
        return $form;
    }
    protected function getPasswordForm($accountPerson)
    {
        $formType = new AccountProfilePasswordFormType($this->getEntityManager());
        $form     = $this->createForm($formType, $accountPerson);
        return $form;
    }
    protected function getAysoForm($accountPerson)
    {
        $formType = new AccountProfileAysoFormType($this->getEntityManager());
        $form     = $this->createForm($formType, $accountPerson);
        return $form;
    }
    public function indexAction()
    {
        // Must be signed in
        $user = $this->getUser();
        if (!$user->isSignedIn()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        // Get the current id
        $accountPersonId = $user->getAccountPersonId();
        if (!$accountPersonId) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        // Get the account
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->getAccountPerson(array('accountPersonId' => $accountPersonId, 'projectId' => $this->getProjectId()));
        if (!$accountPerson) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        // Render
        $tplData = $this->getTplData();
        $tplData['id']          = $accountPersonId;

        $tplData['contactForm'] = $this->getContactForm($accountPerson)->createView();
        $tplData['contactFlash']= $this->getSession()->getFlash('accProfileContactUpdated'); // Limit to name

        $tplData['passwordForm'] = $this->getPasswordForm($accountPerson)->createView();
        $tplData['passwordFlash']= $this->getSession()->getFlash('accProfilePassUpdated'); // Limit to name

        $tplData['aysoForm'] = $this->getAysoForm($accountPerson)->createView();

        return $this->render('NatGamesBundle:Account:profile.html.twig',$tplData);
    }
    public function postAction(Request $request)
    {
        $submit = $request->get('accountProfileContactSubmit');
        if ($submit) return $this->postContactAction($request);

        $submit = $request->get('accountProfilePasswordSubmit');
        if ($submit) return $this->postPasswordAction($request);

        return $this->redirect($this->generateUrl('_natgames_account_profile'));
        
    }
    public function postContactAction($request)
    {
        // Pull user
        $postedData = $request->get('accountProfileContact'); // Just an array
        $accountPersonId = $postedData['accountPersonId'];

        // Authorize
        $user = $this->getUser();
        if (!$user->isSignedIn()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        // Get the current id
        if ($accountPersonId != $user->getAccountPersonId()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        // Get the account
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->getAccountPerson(array('accountPersonId' => $accountPersonId, 'projectId' => $this->getProjectId()));
        if (!$accountPerson) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        // Validate
        $form = $this->getContactForm($accountPerson);
        $form->bindRequest($request);
        
        if ($form->isValid())
        {
            $accountManager->getEntityManager()->flush();
            $this->getSession()->setFlash('accProfileContactUpdated','Profile has been updated.');
            return $this->redirect($this->generateUrl('_natgames_account_profile'));
        }
        // Render Errors
        $tplData = $this->getTplData();
        $tplData['id']           = $accountPersonId;

        $tplData['contactForm']  = $form->createView();
        $tplData['passwordForm'] = $this->getPasswordForm($accountPerson)->createView();
        $tplData['aysoForm']      = $this->getAysoForm($accountPerson)->createView();

        $tplData['contactFlash']  = null;
        $tplData['passwordFlash'] = null;

        return $this->render('NatGamesBundle:Account:profile.html.twig',$tplData);

        //Debug::dump($postedData);
        //die('postContactAction');
        //$accountPersonId =
    }
    public function postPasswordAction($request)
    {
        // Pull user
        $postedData = $request->get('accountProfilePassword');
        $accountPersonId = $postedData['accountPersonId'];

        // Authorize
        $user = $this->getUser();
        if (!$user->isSignedIn()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        // Get the current id
        if ($accountPersonId != $user->getAccountPersonId()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        // Get the account
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->getAccountPerson(array('accountPersonId' => $accountPersonId, 'projectId' => $this->getProjectId()));
        if (!$accountPerson) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        // Validate
        $form = $this->getPasswordForm($accountPerson);
        $form->bindRequest($request);

        if ($form->isValid())
        {
            $accountManager->getEntityManager()->flush();
            $this->getSession()->setFlash('accProfilePassUpdated','User Name or Password has been updated.');
            return $this->redirect($this->generateUrl('_natgames_account_profile'));
        }
        // Render Errors
        $tplData = $this->getTplData();
        $tplData['id']           = $accountPersonId;

        $tplData['passwordForm'] = $form->createView();
        $tplData['contactForm']  = $this->getContactForm($accountPerson)->createView();
        $tplData['aysoForm']     = $this->getAysoForm($accountPerson)->createView();

        $tplData['contactFlash']  = null;
        $tplData['passwordFlash'] = null;
        
        return $this->render('NatGamesBundle:Account:profile.html.twig',$tplData);

        //Debug::dump($postedData);
        //die('postContactAction');
        //$accountPersonId =
    }
}
