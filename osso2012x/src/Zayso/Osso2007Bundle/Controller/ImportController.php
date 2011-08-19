<?php

namespace Zayso\Osso2007Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Zayso\Osso2007Bundle\Component\Debug;

class ImportController extends BaseController
{
    public function eaysoAction()
    {
        $user = $this->getUser();
        if (!$user->isSignedIn()) return $this->redirect($this->generateUrl('_osso2007_welcome'));

        $tplData = $this->getTplData();
        return $this->render('Osso2007Bundle:Import:eayso.html.twig',$tplData);
    }
    public function eaysoPostAction()
    {
        $user = $this->getUser();
        if (!$user->isSignedIn()) return $this->redirect($this->generateUrl('_osso2007_welcome'));

        $files = $this->getRequest()->files;

        $importFile = $files->get('import_file');

        $params = array
        (
            'projectId'      => $this->getProjectId(),
            'inputFileName'  => $importFile->getPathName(), // /var/tmp/whatever
            'clientFileName' => $importFile->getClientOriginalName(),
        );
        // Get the class
        $importClassName = $this->getImportClassName($params['inputFileName'],$params['clientFileName']);

        // Only do eayso stuff here
        if (strpos($importClassname,'ZaysoBundle') === false) $importClassName = null;
        
        die($importClassName);
        
        return $this->redirect($this->generateUrl('_osso2007_import_eayso'));
    }
    /* ===========================================================
     * Quick and dirty way to map import classes
     */
    protected $map = array
    (
        'Zayso\EaysoBundle\Import\VolunteerImport'     => array('AYSOID','DOB','Gender','Membershipyear','MI'),
        'Zayso\EaysoBundle\Import\CertificationImport' => array('AYSOID','CertificationDesc','CertDate','MembershipTermName'),

        'Osso2007_Team_Sch_SchTeamImport'        => array('Region', 'Div','Schedule Team','Physical Team'),
        'Osso2007_Schedule_Import_SchImport'     => array('Date','Time','Field','Home Team','Away Team','Number'),

        'Eayso_Reg_Cert_Type_RegCertTypeImport'  => array('id','desc1','desc2','desc3','table reg_cert_type'),
        'Osso2007_Team_Phy_PhyTeamImport'        => array('TeamDesignation', 'TeamID','TeamAsstCoachFName'),
        'Osso2007_Team_Phy_PhyTeamRosterImport'  => array('Team Designation','Region #','Asst. Team Coach AYSO ID'),
        'Osso2007_Project_ProjectImport'         => array('project_table'),
    );
    protected function getImportClassName($tmpName,$clientName)
    {
        $fp = fopen($tmpName,'r');
        if (!$fp) return NULL;

        $header = fgetcsv($fp);
        fclose($fp);

        foreach($this->map as $class => $names)
        {
            $haveAll = true;
            foreach($names as $name)
            {
                if (array_search($name,$header) === FALSE) $haveAll = false;
            }
            if ($haveAll) return $class;
        }
        return NULL;
    }
}
