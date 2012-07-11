<?php
class S5GamesPersonExport
{
    protected $counts = array();
    
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
    public function __construct($excel,$persons)
    {
        $this->excel = $excel;
        $this->persons = $persons;
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
    protected function generateProjectPersons($ws,$persons)
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
            /*
            'Attend'       => 'attend',
            'Referee'      => 'will_referee',
            'Ground Trans' => 'ground_transport',
            'Hotel'        => 'hotel',
            'Will Assess'  => 'do_assessments',
            'Volunteer'    => 'other_jobs',
            'T-Shirt'      => 't_shirt_size'*/
        );
        $ws->setTitle('Everyone');
        $row = $this->setHeaders($ws,$map);
        
        foreach($persons as $person)
        {
            $this->setRow($ws,$map,$person,$row);
        }
        $this->counts['Everyone'] = $row - 1;
    }
    public function generate()
    {
        // Spreadsheet
        $ss = $this->excel->newSpreadSheet();
        $ws = $ss->getSheet(0);
        
        $this->generateProjectPersons ($ss->getSheet(0),$this->persons);

        // Output
        $ss->setActiveSheetIndex(0);
        $objWriter = $this->excel->newWriter($ss); // \PHPExcel_IOFactory::createWriter($ss, 'Excel5');

        ob_start();
        $objWriter->save('php://output'); // Instead of file name
        return ob_get_clean();
        
    }
}
$export = new S5GamesPersonExport($excel,$persons);

echo $export->generate();
 
?>
