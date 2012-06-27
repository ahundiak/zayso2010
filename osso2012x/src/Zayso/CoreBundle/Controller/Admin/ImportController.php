<?php

namespace Zayso\CoreBundle\Controller\Admin;

use Zayso\CoreBundle\Controller\BaseController;
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
    public $inputFileName;
    public $clientFileName;
    public $importServiceId;
    public $type;
    
    public $attachment;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }
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
        
        $builder->add('type', 'choice', array(
            'label'   => 'Import Type',
            'choices' => array
            (
                0 => 'Auto Detect',
                'Sendoff2012Schedule'  => 'Sendoff2012Schedule',
                'S5Games2011Schedule'  => 'S5Games2011Schedule',
                'S5Games2012Schedule'  => 'S5Games2012Schedule',
                'NatGames2012PhyTeams' => 'NatGames2012PhyTeams',
                'NatGames2012Schedule' => 'NatGames2012Schedule',
                'NatGames2012RefSched' => 'NatGames2012RefSched',
            ),
        ));
        
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
        'zayso_natgames.account.import'         => array('AP ID', 'Account', 'AYSOID'),
        
        'zayso_core.eayso.region.import'        => array('org_key','parent_key','desc1','desc2'),
        
        'zayso_core.eayso.volunteer.import'     => array('AYSOID','Region','WorkPhoneExt','Membershipyear'),
        
        'zayso_core.eayso.certification.import' => array
        (
            'AYSOID','RegionNumber','MembershipTermName','CertificationDesc',
        ),
        
        'zayso_area.game.schedule.import' => array(
            'PID','Number','Date','Time','Field',
            array('Home','Home Team'),
            array('Away','Away Team')),
    );
    protected $importServiceIdTypeMap = array
    (
        'Sendoff2012Schedule'  => 'zayso_area.sendoff.import',
        'S5Games2011Schedule'  => 'zayso_core.game.tourn.import',
        'S5Games2012Schedule'  => 'zayso_s5games.schedule.import',
        'NatGames2012PhyTeams' => 'zayso_natgames.team.import',    
        'NatGames2012Schedule' => 'zayso_natgames.schedule2012.import',    
        'NatGames2012RefSched' => 'zayso_natgames.schedule2012.import2',    
    );
    protected function getImportServiceId($tmpFileName, $type = null)
    {
        if ($type)
        {
            if (!isset($this->importServiceIdTypeMap[$type]))
            {
                return null;
            }
            return $this->importServiceIdTypeMap[$type];
        }
        $fp = fopen($tmpFileName,'r');
        if (!$fp) return null;

        $header = fgetcsv($fp);
        fclose($fp);

        foreach($this->importServiceIdMap as $serviceId => $names)
        {
            $haveAll = true;
            foreach($names as $namesx)
            {
                if (!is_array($namesx)) $namesx = array($namesx);
                $haveOne = false;
                foreach($namesx as $name)
                {
                    if (array_search($name,$header) !== false) $haveOne = true;
                }
                if (!$haveOne) $haveAll = false;
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
        $serviceId = $this->getImportServiceId($inputFileName,$form['type']->getData());
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
        $importData = new ImportData($this->getProjectId());
        $importType = new ImportType();
        $form = $this->createForm($importType, $importData);

        if ($this->isAdmin() && $request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {   
                $import = $this->get($importData->importServiceId);
                
                $results = $import->process($importData);
                
                // $importData->results = $results;
                $msg = $import->getResultMessage();
                $request->getSession()->setFlash('importMsg',$msg);
                
                return $this->redirect($this->generateUrl('zayso_core_admin_import'));
                
                die($import->getResultMessage());
            }
        }
        $tplData = array();
        $tplData['form']      = $form->createView();
        $tplData['importMsg'] = $request->getSession()->getFlash('importMsg');
        return $this->render('ZaysoCoreBundle:Admin:import.html.twig',$tplData);
    }
}
