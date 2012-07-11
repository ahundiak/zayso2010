<?php

namespace Zayso\NatGamesBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExcelCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('NatGames:excel')
            ->setDescription('Spreadsheet Testing')
        ;
    }
    protected function importRegions()
    {
        $inputFileName = '../datax/Regions.csv';
        $params = array
        (
            'inputFileName' => $inputFileName,
        );
        $em = $this->getContainer()->get('doctrine')->getEntityManager('osso2012');

        $import = $this->getContainer()->get('zayso.core.region.import');
        $results = $import->process($params);
        echo "Zayso Import {$results['msg']} \n";

    }
    protected function test1()
    {
        $ss = new \PHPExcel();

        $ws = $ss->setActiveSheetIndex(0);
        $ws->setTitle('Referees');
        $ws->setCellValue('A1', 'A1');
        $ws->setCellValue('B2', 'B2');
        $ws->setCellValue('C3', 'C3');

        $ss->createSheet();
        $wsRegions = $ss->setActiveSheetIndex(1);
        $wsRegions->setTitle('Regions');
        $wsRegions->setCellValue('A1', 'A1');
        $wsRegions->setCellValue('B2', 'B2');
        $wsRegions->setCellValue('C3', 'C3');

        $wsRegions->setCellValueByColumnAndRow(0,5,'Col 0 Row 5');
        $wsRegions->setCellValueByColumnAndRow(1,5,'Col 1 Row 5');

        $ws->setCellValue('C4', 'C4');
        $ss->setActiveSheetIndex(0);

        $objWriter = \PHPExcel_IOFactory::createWriter($ss, 'Excel5');
        $objWriter->save('../datax/test1.xls');

    }
    protected function test3()
    {
        
        $accountManager = $this->getContainer()->get('zayso_core.account.manager');
        $accountPersons = $accountManager->getAccountPersons(array('projectId' => 52));

        $excel = $this->getContainer()->get('zayso_core.format.excel');
        
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
            $ws->setCellValueByColumnAndRow( 6,$row,$ap->getCellPhone());
        }

        // Finish up
        $ss->setActiveSheetIndex(0);
        $objWriter = $excel->newWriter($ss); // \PHPExcel_IOFactory::createWriter($ss, 'Excel5');

        ob_start();
        $objWriter->save('php://output'); // Instead of file name
        $buffer = ob_get_clean();

        file_put_contents('../datax/test3.xls',$buffer);

    }
    protected function test4()
    {
        $accountManager = $this->getContainer()->get('zayso_core.account.manager');
        $accountPersons = $accountManager->getAccountPersons(array('projectId' => 52));

        $export = $this->getContainer()->get('zayso_natgames.account.export');
        
        file_put_contents('../datax/test4.xls',$export->generate());
        
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->test4();
    }
}
