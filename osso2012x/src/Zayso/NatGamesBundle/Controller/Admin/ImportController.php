<?php

namespace Zayso\NatGamesBundle\Controller\Admin;

use Zayso\NatGamesBundle\Controller\BaseController;
use Zayso\CoreBundle\Component\Debug;

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
    public $importServiceId;
    
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

        $builder->add('attachment', 'file', array('label' => 'Import File','attr' => array('size' => 60)));
        
        // Really don't need and would probably be a session variable
        // $builder->add('processed', 'text',  array('label' => 'Processed File', 'attr' => array('size' => 40, 'readonly'=>true)));
        
        $builder->addValidator(new ImportFileValidator());
    }
    public function getName()
    {
        return 'import';
    }
}
class ImportFileValidator implements FormValidatorInterface
{
    protected $importServiceIdMap = array
    (
        'zayso.natgames.account.import'  => array('AP ID', 'Account', 'AYSOID'),
        'zayso.core.region.import'       => array('org_key','parent_key','desc1','desc2'),
        
        'Eayso_Reg_Main_RegMainImport'           => array('AYSOID','WorkPhoneExt','Membershipyear'),
    );
    protected function getImportServiceId($tmpFileName)
    {
        $fp = fopen($tmpFileName,'r');
        if (!$fp) return null;

        $header = fgetcsv($fp);
        fclose($fp);

        foreach($this->importServiceIdMap as $serviceId => $names)
        {
            $haveAll = true;
            foreach($names as $name)
            {
                if (array_search($name,$header) === false) $haveAll = false;
            }
            if ($haveAll) return $serviceId;
        }
        return null;
    }
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
        
        // Match file with import service id
        $serviceId = $this->getImportServiceId($inputFileName);
        if (!$serviceId)
        {
            $form['attachment']->addError(new FormError('Cannot determine type of file to import.'));
            return;            
        }
        $importData->importServiceId = $serviceId;

    }
}
class ImportController extends BaseController
{
    public function indexAction(Request $request)
    {
        $importData = new ImportData();
        $importType = new ImportType();
        $form = $this->createForm($importType, $importData);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                //$importClassName = $importData->importClassName;
                //$import = new $importClassName($this->getEntityManager(),$this->get('account.manager'));
                
                $import = $this->get($importData->importServiceId);
                
                $results = $import->process($importData);
                
                // $importData->results = $results;
                $msg = $import->getResultMessage();
                $request->getSession()->setFlash('importMsg',$msg);
                
                return $this->redirect($this->generateUrl('zayso_natgames_admin_import'));
                
                die($import->getResultMessage());
            }
        }
        $tplData = $this->getTplData();
        $tplData['form']      = $form->createView();
        $tplData['importMsg'] = $request->getSession()->getFlash('importMsg');
        return $this->render('ZaysoNatGamesBundle:Admin:import.html.twig',$tplData);
    }
    /* =================================================
     * Don't think this is used
     *
    public function accountsAction($_format)
    {
        
        // Check auth
        if (!$this->isAuth()) return $this->redirect($this->generateUrl('zayso_natgames_welcomex'));

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
        
        if ($_format == 'html') return $this->render('ZaysoNatGamesBundle:Admin:accounts.html.twig',$tplData);
        
        $response = $this->render('ZaysoNatGamesBundle:Admin:accounts.csv.php',$tplData);
        $response->headers->set('Content-Type', 'application/csv');
        return $response;
    }
    */
}
