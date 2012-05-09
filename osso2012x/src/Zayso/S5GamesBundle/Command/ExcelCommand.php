<?php

namespace Zayso\S5GamesBundle\Command;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Component\Import\ExcelBaseImport;

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
            ->setName('s5games:excel')
            ->setDescription('Spreadsheet Testing')
        ;
    }
    protected function test4()
    {
        $export = $this->getContainer()->get('zayso_s5games.game2011.export');
        
        file_put_contents('../datax/S5Games2011.xls',$export->generate());
        
    }
    protected function test5()
    {   
        $excel = $this->getContainer()->get('zayso_core.format.excel');
        
        $reader = $excel->load('../datax/S5Games2011.xls');
        $ws = $reader->getSheetByName('Schedule'); // Or jest getSheet($num)
        
        // Loaded PHPExcel PHPExcel_Worksheet
        echo "Loaded " . get_class($reader) . ' ' . get_class($ws) . ' ' . $ws->getTitle() . "\n";
        echo 'Highest Col: ' . $ws->getHighestColumn() . "\n"; // 0
        echo 'Highest Row: ' . $ws->getHighestRow() . "\n";    // 207
        
        $data = $ws->toArray();
        echo 'Data Count: ' . count($data) . "\n";
        
        $headers = $data[0];
        print_r($headers);
        print_r($data[1]);
        
        $sheets = $reader->getAllSheets();
        foreach($sheets as $sheet)
        {
            echo 'Sheet: ' . $sheet->getTitle() . "\n";
        }
        die();
        // $cells = $ws->getCellCollection();
        $rows = $ws->getRowIterator();
        foreach($rows as $row)
        {
            echo 'Row ' . get_class($row) . "\n";
            
            $cells = $row->getCellIterator();
            foreach($cells as $cell)
            {
                Debug::dump($cell); die('cell');
            }
        }
        
    }
    protected function test6()
    {
        $import = $this->getContainer()->get('zayso_core.game.tourn.import');
        
        $params = array(
            'projectId'     => 61,
            'inputFileName' => '../datax/S5Games2011.xls',
            'sheetName'     => 'Schedule',
        );
        $results = $import->process($params);
        Debug::dump($results);
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->test6();
    }
}
