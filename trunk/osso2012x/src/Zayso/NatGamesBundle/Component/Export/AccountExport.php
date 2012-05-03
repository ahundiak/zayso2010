<?php

namespace Zayso\NatGamesBundle\Component\Export;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Component\DataTransformer\PhoneTransformer;

class AccountExport
{
    protected $excel = null;
    
    public function __construct($excel)
    {
        $this->excel = $excel;
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
