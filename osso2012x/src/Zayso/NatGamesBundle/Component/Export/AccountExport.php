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
    protected $groundTransportIndex = 1;
    
    protected $phoneTransformer;
    protected $regionTransformer;
    
    protected $persons;
    protected $counts = array
    (
        'Confirmed Referees'    => 0,
        'Might Referee'         => 0,
        'Ground Transportation' => 0,
        'Everyone'              => 0,
    );
    
    protected $widths = array
    (
        'PEID'         =>  5,
        'AYSOID'       => 10,
        'Last Name'    => 15,
        'First Name'   => 15,
        'Nick Name'    => 10,
        'Email'        => 10,
        'Cell Phone'   => 12,
        'Region'       =>  6,
        'Regon Desc'   => 25,
        'ST'           =>  4,
        'MY'           =>  5,
        'Safe Haven'   =>  5,
        'Ref Badge'    => 12,
        'Attend'       => 10,
        'Referee'      =>  5,
        'Ground Trans' =>  5,
        'Hotel'        => 20,
        'Will Assess'  => 10,
        'Volunteer'    => 10,
        'T-Shirt'      => 10,
    );
    
    public function __construct($manager,$projectId,$excel)
    {
        $this->manager   = $manager;
        $this->projectId = $projectId;
        $this->excel     = $excel;
        
        $this->phoneTransformer  = new PhoneTransformer();
        $this->regionTransformer = new RegionTransformer();
        
        $this->persons = null;
    }
    protected function setHeaders($ws,$map)
    {
        $col = 0;
        foreach(array_keys($map) as $header)
        {
            $ws->getColumnDimensionByColumn($col)->setWidth($this->widths[$header]);
            $ws->setCellValueByColumnAndRow($col++,1,$header);
        }
        return 1;
    }
    protected function setRow($ws,$map,$person,&$row)
    {
        $row++;
        $col = 0;
        foreach($map as $propName)
        {
            $ws->setCellValueByColumnAndRow($col++,$row,$person[$propName]);
        }
        return $row;
    }
    protected function generateProjectPersons($ws)
    {
        $map = array(
            'PEID'         => 'id',
            'AYSOID'       => 'aysoid',
            'Last Name'    => 'lastName',
            'First Name'   => 'firstName',
            'Nick Name'    => 'nickName',
            'Email'        => 'email',
            'Cell Phone'   => 'cellPhone',
            'Region'       => 'region',
            'Regon Desc'   => 'regionDesc',
            'ST'           => 'state',
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
        $ws->setTitle('Everyone');
        $row = $this->setHeaders($ws,$map);
        
        $persons = $this->getPersons();
        foreach($persons as $person)
        {
            $this->setRow($ws,$map,$person,$row);
        }
        $this->counts['Everyone'] = $row - 1;
    }
    protected function generateGroundTransport($ws)
    {
        $map = array(
            'PEID'         => 'id',
            'AYSOID'       => 'aysoid',
            'Last Name'    => 'lastName',
            'First Name'   => 'firstName',
            'Nick Name'    => 'nickName',
            'Email'        => 'email',
            'Cell Phone'   => 'cellPhone',
            'Regon Desc'   => 'regionDesc',
            'Ground Trans' => 'ground_transport',
            'Hotel'        => 'hotel',
        );
        $ws->setTitle('Ground Transport');
        
        $row = $this->setHeaders($ws,$map);
        
        $persons = $this->getPersons();
        foreach($persons as $person)
        {
            if ($person['ground_transport'] == 'Yes') $this->setRow($ws,$map,$person,$row);
        }
        $this->counts['Ground Transportation'] = $row - 1;
    }
    protected function generateConfirmed($ws)
    {
        $map = array(
            'PEID'         => 'id',
            'AYSOID'       => 'aysoid',
            'Last Name'    => 'lastName',
            'First Name'   => 'firstName',
            'Nick Name'    => 'nickName',
            'Email'        => 'email',
            'Cell Phone'   => 'cellPhone',
            'Region'       => 'region',
            'Regon Desc'   => 'regionDesc',
            'ST'           => 'state',
            'MY'           => 'memYear',
            'Safe Haven'   => 'safeHaven',
            'Ref Badge'    => 'refBadge',
            'T-Shirt'      => 't_shirt_size',
            'Attend'       => 'attend',
            'Referee'      => 'will_referee',
        );
        $ws->setTitle('Confirmed');
        
        $row = $this->setHeaders($ws,$map);
        
        $persons = $this->getPersons();
        foreach($persons as $person)
        {
            $attend =  substr($person['attend'],0,3);
            $referee = substr($person['will_referee'],0,3);
            if ($attend == 'Yes' && $referee == 'Yes') 
            {
                $this->setRow($ws,$map,$person,$row);
            }
        }
        $this->counts['Confirmed Referees'] = $row - 1;
    }
    protected function generateMaybe($ws)
    {
        $map = array(
            'PEID'         => 'id',
            'AYSOID'       => 'aysoid',
            'Last Name'    => 'lastName',
            'First Name'   => 'firstName',
            'Nick Name'    => 'nickName',
            'Email'        => 'email',
            'Cell Phone'   => 'cellPhone',
            'Region'       => 'region',
            'Regon Desc'   => 'regionDesc',
            'ST'           => 'state',
            'MY'           => 'memYear',
            'Safe Haven'   => 'safeHaven',
            'Ref Badge'    => 'refBadge',
            'T-Shirt'      => 't_shirt_size',
            'Attend'       => 'attend',
            'Referee'      => 'will_referee',
        );
        $ws->setTitle('Might Referee');
        
        $row = $this->setHeaders($ws,$map);
        
        $persons = $this->getPersons();
        foreach($persons as $person)
        {
            $attend =  substr($person['attend'],0,3);
            $referee = substr($person['will_referee'],0,3);
            
            $maybe = true;
            if ($attend  == 'No') $maybe = false;
            if ($referee == 'No') $maybe = false;
            
            if ($attend == 'Yes' && $referee == 'Yes') $maybe = false;
            
            if ($maybe) 
            {
                $this->setRow($ws,$map,$person,$row);
            }
        }
        $this->counts['Might Referee'] = $row - 1;
    }
    protected function generateCounts($ws)
    {
        $ws->setTitle('Summary');
        
        $ws->getColumnDimensionByColumn(0)->setWidth(30);
        $ws->setCellValueByColumnAndRow(0,1,'Count Description');
        
        $ws->getColumnDimensionByColumn(1)->setWidth(6);
        $ws->setCellValueByColumnAndRow(1,1,'Count');
        
        $row = 1;
        
        foreach($this->counts as $key => $value)
        {
            $row++;
            $ws->setCellValueByColumnAndRow(0,$row,$key);
            $ws->setCellValueByColumnAndRow(1,$row,$value);
        }
    }
    protected function generateStates($ws)
    {
        $states = array();
        
        $ws->setTitle('States');
        
        $ws->getColumnDimensionByColumn(0)->setWidth(8);
        $ws->setCellValueByColumnAndRow(0,1,'State');
        
        $ws->getColumnDimensionByColumn(1)->setWidth(8);
        $ws->setCellValueByColumnAndRow(1,1,'Confirmed');
        
        $ws->getColumnDimensionByColumn(1)->setWidth(8);
        $ws->setCellValueByColumnAndRow(2,1,'Maybe');
        
        $ws->getColumnDimensionByColumn(1)->setWidth(8);
        $ws->setCellValueByColumnAndRow(3,1,'Total');
 
        $persons = $this->getPersons();
        
        foreach($persons as $person)
        {
            $state = $person['state'];
            if (!$state) $state='??';
            
            $attend =  substr($person['attend'],0,3);
            $referee = substr($person['will_referee'],0,3);
            
            $confirmed = false;
            if ($attend == 'Yes' && $referee == 'Yes') $confirmed = true;
            
            $maybe = true;
            if ($attend  == 'No') $maybe = false;
            if ($referee == 'No') $maybe = false;
            
            if ($attend == 'Yes' && $referee == 'Yes') $maybe = false;
            
            if ($confirmed || $maybe) 
            {
                if (!isset($states[$state])) $states[$state] = array('confirmed' => 0, 'maybe' => 0);
                if ($confirmed) $states[$state]['confirmed']++;
                if ($maybe)     $states[$state]['maybe']++;
            }
        }
        asort($states);
        
        $row = 1;
        $totalConfirmed = 0;
        $totalMaybe = 0;
        
        foreach($states as $key => $value)
        {
            $row++;
            $ws->setCellValueByColumnAndRow(0,$row,$key);
            $ws->setCellValueByColumnAndRow(1,$row,$value['confirmed']);
            $ws->setCellValueByColumnAndRow(2,$row,$value['maybe']);
            $ws->setCellValueByColumnAndRow(3,$row,$value['confirmed'] + $value['maybe']);
            
            $totalConfirmed += $value['confirmed'];
            $totalMaybe     += $value['maybe'];
        }
        $row++;
        $ws->setCellValueByColumnAndRow(0,$row,'Totals');
        $ws->setCellValueByColumnAndRow(1,$row,$totalConfirmed);
        $ws->setCellValueByColumnAndRow(2,$row,$totalMaybe);
        $ws->setCellValueByColumnAndRow(3,$row,$totalConfirmed + $totalMaybe);
    }
    public function generate()
    {
        $excel = $this->excel;
        
        $ss = $excel->newSpreadSheet();
        
        $this->generateConfirmed      ($ss->createSheet(1));
        $this->generateMaybe          ($ss->createSheet(2));
        $this->generateStates         ($ss->createSheet(3));
        $this->generateGroundTransport($ss->createSheet(4));
        $this->generateProjectPersons ($ss->createSheet(5));
        
        $this->generateCounts($ss->getSheet(0));
        
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
    /* =============================================================
     * Returns array of flattened people
     */
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
            $person['cellPhone'] = $this->phoneTransformer->transform($item->getCellPhone());   
            
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
}
?>
