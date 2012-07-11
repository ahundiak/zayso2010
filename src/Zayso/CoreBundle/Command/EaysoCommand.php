<?php
namespace Zayso\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class EaysoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('core:eayso:import')
            ->setDescription('Eayso Imports')
        ;
    }
    protected function getVolImport()  { return $this->getContainer()->get('zayso_core.eayso.volunteer.import' ); }
    protected function getCertImport() { return $this->getContainer()->get('zayso_core.eayso.certification.import' ); }
    protected function getProjectId()  { return $this->getContainer()->getParameter('zayso_area.project.master'); }
    
    protected function import()
    {
        $projectId = $this->getProjectId();
        echo "Import $projectId\n";
    }
    protected function importVol($file)
    {
        $params = array('inputFileName' => $file);
        
        $import = $this->getVolImport();
        
        $results = $import->process($params);

        echo "Zayso Import {$results['msg']} \n";
    }
    protected function importCert($file)
    {
        $params = array('inputFileName' => $file);
        
        $import = $this->getCertImport();
        
        $results = $import->process($params);

        echo "Zayso Import {$results['msg']} \n";
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
      //$this->test1();
      //$this->test2();
      //$this->import();
        
        $datax = "C:/home/ahundiak/datax/eayso/vols/";
        
      //$this->importVol ($datax . 'Vols.csv');
        
      //$this->importCert($datax . 'SafeHaven.csv');
      //$this->importCert($datax . 'RefSafeHaven.csv');
      //$this->importCert($datax . 'CoachSafeHaven.csv');
        
        $this->importCert($datax . 'RefNat.csv');
        $this->importCert($datax . 'RefNat2.csv');
        $this->importCert($datax . 'RefAdvanced.csv');
        $this->importCert($datax . 'RefInt.csv');
        $this->importCert($datax . 'RefReg.csv');
        $this->importCert($datax . 'RefU8.csv');
        $this->importCert($datax . 'RefAssistant.csv');
        
   }
}
?>
