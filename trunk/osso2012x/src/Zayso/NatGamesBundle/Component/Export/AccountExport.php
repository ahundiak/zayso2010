<?php

namespace Zayso\NatGamesBundle\Component\Export;

use Zayso\CoreBundle\Component\Debug;

use Zayso\CoreBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\CoreBundle\Component\DataTransformer\RegionTransformer;

class AccountExport
{
    protected $excel = null;
    protected $manager = null;
    protected $projectId = 0;
    
    protected $projectPersonsIndex = 0;
    
    protected $phoneTransformer;
    protected $regionTransformer;
    
    protected $persons;
    
    public function __construct($manager,$projectId,$excel)
    {
        $this->manager   = $manager;
        $this->projectId = $projectId;
        $this->excel     = $excel;
        
        $this->phoneTransformer  = new PhoneTransformer();
        $this->regionTransformer = new RegionTransformer();
        
        $this->persons = null;
    }
    // Returns array of flattend people
    public function getPersons()
    {
        if ($this->persons) return $this->persons;
        
        $this->persons = array();
        
        $items = $this->manager->loadPersonsForProject($this->projectId);
        foreach($items as $item)
        {
            $person = array();
          
            $person['id']        = $item->getId();
            $person['projectId'] = $this->projectId;
         
            $person['lastName']  = $item->getLastName();
            $person['firstName'] = $item->getFirstName();
            $person['nickName']  = $item->getNickName();
            $person['email']     = $item->getEmail();
            $person['cellPhone'] = $item->getCellPhone();   
            
            $org = $item->getOrgz();
          
            $person['region']    = substr($org->getId(),4);
            $person['regionDesc']= $org->getDesc2();
            $person['state']     = $org->getState();
            
            $aysoCert = $item->getAysoCertz();
            $person['aysoid']    = substr($aysoCert->getRegKey(),5);
            $person['memYear']   = $aysoCert->getMemYear();
            $person['safeHaven'] = $aysoCert->getSafeHaven();
            $person['refBadge']  = $aysoCert->getRefBadge();
            
            $projectPerson = $item->getProjectPerson($this->projectId);
            $plans = $projectPerson->get('plans');
            if (!is_array($plans)) $plans = array();
            
            $planItems = array
            (
                'attend', 
                'will_referee',
                'ground_transport',
                'hotel', 
                'do_assessments',
                'other_jobs',
                't_shirt_size',
            );
            foreach($planItems as $planItem)
            {
                if (isset($plans[$planItem])) $person[$planItem] = $plans[$planItem];
                else                          $person[$planItem] = null;
            }
            $this->persons[$item->getId()] = $person;
        }
        return $this->persons;
    }
    protected function generateProjectPersons($ss)
    {
        $map = array(
            'PEID'         => 'id',
            'Last Name'    => 'lastName',
            'First Name'   => 'firstName',
            'Nick Name'    => 'nickName',
            'Email'        => 'email',
            'Cell Phone'   => 'cellPhone',
            'Region'       => 'region',
            'Regon Desc'   => 'regionDesc',
            'ST'           => 'state',
            'AYSOID'       => 'aysoid',
            'MY'           => 'memYear',
            'Safe Haven'   => 'safeHaven',
            'Ref Badge'    => 'refBadge',
            'Attend'       => 'attend',
            'Referee'      => 'will_referee',
            'Ground Trans' => 'ground_transport',
            'Hotel'        => 'hotel',
            'Will Assess'  => 'do_assessments',
            'Volunteer'    => 'other_jobs',
            'T-Shirt'      => 't_shirt_size'
        );
        $ws = $ss->setActiveSheetIndex($this->projectPersonsIndex);
        $ws->setTitle('People');
        
        $row = 1;
        $col = 0;
        foreach(array_keys($map) as $header)
        {
            $ws->setCellValueByColumnAndRow($col++,$row,$header);
        }

        $persons = $this->getPersons();
        foreach($persons as $person)
        {
            $row++;
            $col = 0;
            foreach($map as $propName)
            {
                $ws->setCellValueByColumnAndRow($col++,$row,$person[$propName]);
            }
        }
    }
    protected function generateProjectPersonsx($ss)
    {
        
        $ws = $ss->setActiveSheetIndex($this->projectPersonsIndex);
        $ws->setTitle('People');
        
        $headers = array(
            'PEID','Last Name','First Name','Nick Name',
            'Email','Cell Phone','Region','Regon Desc','ST',
            'AYSOID','MY','Safe Haven','Ref Badge',
            'Attend','Referee','Ground Trans','Hotel','Will Assess','Volunteer','T-Shirt'
        );

        $row = 1;
        $col = 0;
        foreach($headers as $header)
        {
            $ws->setCellValueByColumnAndRow($col++,$row,$header);
        }
        $persons = $this->manager->loadPersonsForProject($this->projectId);
        //die('Person Count: ' . count($persons) . "\n");
        foreach($persons as $person)
        {
            $row++;
            $col = 0;
          //$ws->setCellValueByColumnAndRow($col++,$row,$this->projectId);
            $ws->setCellValueByColumnAndRow($col++,$row,$person->getId());
            $ws->setCellValueByColumnAndRow($col++,$row,$person->getLastName());
            $ws->setCellValueByColumnAndRow($col++,$row,$person->getFirstName());
            $ws->setCellValueByColumnAndRow($col++,$row,$person->getNickName());
            $ws->setCellValueByColumnAndRow($col++,$row,$person->getEmail());
            $ws->setCellValueByColumnAndRow($col++,$row,$this->phoneTransformer->transform($person->getCellPhone()));   
            
            $org = $person->getOrgz();
          //$ws->setCellValueByColumnAndRow($col++,$row,$this->regionTransformer->transform($org->getId()));
            $ws->setCellValueByColumnAndRow($col++,$row,substr($org->getId(),4));
            
            $ws->setCellValueByColumnAndRow($col++,$row,$org->getDesc2());
            $ws->setCellValueByColumnAndRow($col++,$row,$org->getState());
            
            $aysoCert = $person->getAysoCertz();
            $ws->setCellValueByColumnAndRow($col++,$row,substr($aysoCert->getRegKey(),5));
            $ws->setCellValueByColumnAndRow($col++,$row,$aysoCert->getMemYear());
            $ws->setCellValueByColumnAndRow($col++,$row,$aysoCert->getSafeHaven());
            $ws->setCellValueByColumnAndRow($col++,$row,$aysoCert->getRefBadge());
            
            $projectPerson = $person->getProjectPerson($this->projectId);
            $plans = $projectPerson->get('plans');
            if (!is_array($plans)) $plans = array();
            
            $items = array
            (
                'attend', 
                'will_referee',
                'ground_transport',
                'hotel', 
                'do_assessments',
                'other_jobs',
                't_shirt_size',
            );
            foreach($items as $item)
            {
                if (isset($plans[$item])) $ws->setCellValueByColumnAndRow($col++,$row,$plans[$item]);
                else                      $ws->setCellValueByColumnAndRow($col++,$row,'');
            }
        }
        
    }
    public function generate()
    {
        $excel = $this->excel;
        
        $ss = $excel->newSpreadSheet();

        $this->generateProjectPersons($ss);
        
        // Output
        $ss->setActiveSheetIndex(0);
        $objWriter = $excel->newWriter($ss); // \PHPExcel_IOFactory::createWriter($ss, 'Excel5');

        ob_start();
        $objWriter->save('php://output'); // Instead of file name
        return ob_get_clean();
    }
    public function export($accountPersons)
    {
        $phoneTransformer = new PhoneTransformer();
        
        $excel = $this->excel;
        
        $ss = $excel->newSpreadSheet();
        $ws = $ss->setActiveSheetIndex(0);
        $ws->setTitle('Referees');

        $headers = array(
            'AP ID','Account','First Name','Last  Name','Nick  Name',
            'Email','Cell Phone','Region',
            'AYSOID','DOB','Gender','Ref Badge','Ref Date','Safe Haven','MY',
            'Attend','Referee','Sun','Mon','Tue','Wed','Thu','Fri','Sat','Sun');

        $row = 1;
        $col = 0;
        foreach($headers as $header)
        {
            $ws->setCellValueByColumnAndRow($col,$row,$header);
            $col++;
        }
        foreach($accountPersons as $ap)
        {
            $row++;
            $ws->setCellValueByColumnAndRow( 0,$row,$ap->getId());
            $ws->setCellValueByColumnAndRow( 1,$row,$ap->getUserName());
            $ws->setCellValueByColumnAndRow( 2,$row,$ap->getFirstName());
            $ws->setCellValueByColumnAndRow( 3,$row,$ap->getLastName());
            $ws->setCellValueByColumnAndRow( 4,$row,$ap->getNickName());
            $ws->setCellValueByColumnAndRow( 5,$row,$ap->getEmail());
            $ws->setCellValueByColumnAndRow( 6,$row,$phoneTransformer->transform($ap->getCellPhone()));
        }

        // Finish up
        $ss->setActiveSheetIndex(0);
        $objWriter = $excel->newWriter($ss); // \PHPExcel_IOFactory::createWriter($ss, 'Excel5');

        ob_start();
        $objWriter->save('php://output'); // Instead of file name
        return ob_get_clean();
    }
}
?>
