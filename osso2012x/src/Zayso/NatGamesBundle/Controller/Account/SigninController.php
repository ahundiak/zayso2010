<?php

namespace Zayso\NatGamesBundle\Controller\Account;

use Zayso\NatGamesBundle\Controller\BaseController;

use Zayso\ZaysoBundle\Component\DataTransformer\PasswordTransformer;
use Zayso\ZaysoBundle\Component\Form\Validator\UserNamePassValidator;

use Zayso\ZaysoBundle\Entity\Account;
use Zayso\ZaysoBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class AccountSigninFormType extends AbstractType
{
    public function getName() { return 'accountSignin'; }
    public function __construct($em) { $this->em = $em; }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('userName', 'text',     array('label' => 'User Name', 'attr' => array('size' => 35)));
        $builder->add('userPass', 'password', array('label' => 'Password'));

        $builder->add('accountId','hidden', array('property_path' => false));
        $builder->add('memberId', 'hidden', array('property_path' => false));

        $builder->addValidator(new UserNamePassValidator($this->em));
        $builder->get('userPass')->appendClientTransformer(new PasswordTransformer());
    }
}
class SigninController extends BaseController
{
    public function signoutAction()
    {
        $session = $this->getSession();
        $session->set('userData',null);
        return $this->redirect($this->generateUrl('_natgames_welcomex'));
    }
    public function signinAction(Request $request)
    {
        $account = new Account();

        // Remember me
        if ($request->getMethod() == 'GET')
        {
            $session = $this->getSession();
            $accountSigninData = $session->get('accountSigninData');
            if (isset($accountSigninData['userName'])) $account->setUserName($accountSigninData['userName']);
        }
        // Form
        $formType = new AccountSigninFormType($this->getEntityManager());
        $form = $this->createForm($formType, $account);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $session = $this->getSession();

                $userData = array
                (
                    'accountId' => $form['accountId']->getData(),
                    'memberId'  => $form['memberId' ]->getData(),
                    'projectId' => $this->getProjectId(),
                );
                $session->set('userData',$userData);

                // Remeber me
                $session->set('accountSigninData',array('userName' => $form['userName']->getData()));

                return $this->redirect($this->generateUrl('_natgames_home'));
            }
        }
        $tplData = $this->getTplData();
        $tplData['form'] = $form->createView();
        
        return $this->render('NatGamesBundle:Account:signin.html.twig',$tplData);
    }
}
