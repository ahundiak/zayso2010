<?php

namespace Zayso\NatGamesBundle\Controller\Admin\Account;

use Zayso\NatGamesBundle\Controller\BaseController;

use Zayso\ZaysoBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\AysoidTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\RegionTransformer;
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

class AdminAccountEditUserNameValidator implements FormValidatorInterface
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
class AdminAccountEditType extends AbstractType
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
    protected $safeHavenPickList = array
    (
        'None'    => 'None',
        'AYSO'    => 'AYSO',
        'Coach'   => 'Coach',
        'Referee' => 'Referee',
    );
    protected $memYearPickList = array
    (
        'None' => 'None',
        '2011' => 'MY2011',
        '2010' => 'MY2010',
        '2009' => 'FS2009',
        '2008' => 'FS2008',
        '2007' => 'FS2007',
        '2006' => 'FS2006',
        '2005' => 'FS2005',
        '2004' => 'FS2004',
        '2003' => 'FS2003',
        '2002' => 'FS2002',
        '2001' => 'FS2001',
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
        $builder->add('region',    'text', array('label' => 'AYSO Region Number', 'attr' => array('size' => 6)));
        $builder->add('refDate',   'text', array('label' => 'AYSO Referee Date',  'attr' => array('size' => 8)));

//        $builder->add('projectId','hidden');
      //$builder->add('projectIdx','hidden',array('data' => 123, 'property_path' => false));

        $builder->add('refBadge', 'choice', array(
            'label'         => 'AYSO Referee Badge',
            'required'      => true,
            'choices'       => $this->refBadgePickList,
        ));
        $builder->add('safeHaven', 'choice', array(
            'label'         => 'AYSO Safe Haven',
            'required'      => true,
            'choices'       => $this->safeHavenPickList,
        ));
        $builder->add('memYear', 'choice', array(
            'label'         => 'AYSO Mem Year',
            'required'      => true,
            'choices'       => $this->memYearPickList,
        ));
        
        $builder->addValidator(new CallbackValidator(function($form)
        {
            if($form['userPass1']->getData() != $form['userPass2']->getData())
            {
                $form['userPass2']->addError(new FormError('Passwords do not match'));
            }
        }));
        $builder->addValidator(new AdminAccountEditUserNameValidator($this->em));

        $builder->get('userPass1')->appendClientTransformer(new PasswordTransformer());
        $builder->get('userPass2')->appendClientTransformer(new PasswordTransformer());
        $builder->get('cellPhone')->appendClientTransformer(new PhoneTransformer());
        $builder->get('region'   )->appendClientTransformer(new RegionTransformer());
        $builder->get('aysoid'   )->appendClientTransformer(new AysoidTransformer());
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
        $accountType = new AdminAccountEditType($this->getEntityManager());
        $form = $this->createForm($accountType, $accountPerson);

        if ($request->getMethod() == 'POST')
        {

            $form->bindRequest($request);

            if ($form->isValid())
            {
                $accountManager->getEntityManager()->flush();
                
                return $this->redirect($this->generateUrl('_natgames_admin_account_edit',array('id' => $id)));
            }
        }
        $tplData = $this->getTplData();
        $tplData['id']   = $id;
        $tplData['form'] = $form->createView();

        return $this->render('NatGamesBundle:Admin:Account/edit.html.twig',$tplData);
    }
}
