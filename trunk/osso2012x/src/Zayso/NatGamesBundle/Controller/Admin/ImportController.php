<?php

namespace Zayso\NatGamesBundle\Controller\Admin;

use Zayso\NatGamesBundle\Controller\BaseController;
use Zayso\ZaysoBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class ImportData implements \ArrayAccess
{
    public $projectId = 52;
    public $inputFileName;
    public $clientFileName;
    public $importClassName;

    public $results;
    public $attachment;

    // Array access
    public function offsetGet   ($name) { return $this->$name; }
    public function offsetExists($name) { return isset($this->$name); }
    public function offsetSet   ($name, $value) { $this->$name = $value; }
    public function offsetUnset ($name) { unset($this->$name); }
}
class ImportType extends AbstractType
{
    public function __construct()
    {
    }
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('projectId', 'hidden');

        $builder->add('attachment', 'file', array('label' => 'Import File', 'attr' => array('size' => 40)));
        
        $builder->addValidator(new ImportFileValidator());
    }
    public function getName()
    {
        return 'import';
    }
}
class ImportFileValidator implements FormValidatorInterface
{
    public function validate(FormInterface $form)
    {
        $attachment = $form['attachment']->getData();
        $attachmentClass = get_class($attachment);
        if (!($attachmentClass == 'Symfony\Component\HttpFoundation\File\UploadedFile'))
        {
            $form['attachment']->addError(new FormError('No File Selected.'));
            return;
        }
        $inputFileName  = $attachment->getPathName(); // /var/tmp/whatever
        $clientFileName = $attachment->getClientOriginalName();

        $importData = $form->getData();
        $importData->inputFileName  = $inputFileName;
        $importData->clientFileName = $clientFileName;
        
        $importData->importClassName = 'Zayso\NatGamesBundle\Component\Import\AccountImport';

    }
}
class ImportController extends BaseController
{
    public function indexAction(Request $request)
    {
        // Check auth
        if (!$this->isAdmin()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        $importData = new ImportData();
        $importType = new ImportType();
        $form = $this->createForm($importType, $importData);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $importClassName = $importData->importClassName;
                $import = new $importClassName($this->getEntityManager());
                $import->process($importData);
                
                die('Valid ' . $importData->inputFileName);
            }
        }
        $tplData = $this->getTplData();
        $tplData['form'] = $form->createView();
        return $this->render('NatGamesBundle:Admin:import.html.twig',$tplData);
    }
    public function accountsAction($_format)
    {
        
        // Check auth
        if (!$this->isAuth()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        $accountManager = $this->get('account.manager');
        $accounts = $accountManager->getAccounts();
        
        $account = $accounts[0];
        $person = $account->getPrimaryMember()->getPerson();
        $projectPerson = $person->getNatGamesProjectPerson();
        $plans = $projectPerson->getPlans();
        //Debug::dump($plans);
        //die();
      //die('Count ' . count($accounts));
        
        //$accounts = array($accounts[0],$accounts[1]);
        
        $tplData = $this->getTplData();
        $tplData['accounts'] = $accounts;
        $tplData['memberx']  = new MemberViewHelper();
        
        if ($_format == 'html') return $this->render('NatGamesBundle:Admin:accounts.html.twig',$tplData);
        
        $response = $this->render('NatGamesBundle:Admin:accounts.csv.php',$tplData);
        $response->headers->set('Content-Type', 'application/csv');
        return $response;
    }
}
