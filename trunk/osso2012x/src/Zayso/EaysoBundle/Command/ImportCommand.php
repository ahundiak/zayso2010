<?php

namespace Zayso\EaysoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Zayso\EaysoBundle\Import\VolunteerImport;
use Zayso\EaysoBundle\Import\CertificationImport;

use Zayso\EaysoBundle\Component\Debug;

class ImportCommand extends ContainerAwareCommand
{
    protected function getEntityManager()
    {
        /*
        $doctrine = $this->getContainer()->get('doctrine');
        $ems = $doctrine->getEntityManagers();
        echo $doctrine->getDefaultEntityManagerName() . "\n";
        print_r(array_keys($ems));
        die();
        */
        return $this->getContainer()->get('doctrine')->getEntityManager('eayso');
    }
    protected function configure()
    {
        $this
            ->setName('eayso:import')
            ->setDescription('Import Eayso Volunteer/Certs')
            ->addArgument('file', InputArgument::OPTIONAL, 'Input File Name','../datax/Vols.csv')
        ;
    }
    protected function importVol($file)
    {
        $params = array('inputFileName' => $file);
        $import = new VolunteerImport($this->getEntityManager());
        $results = $import->process($params);

        echo "Zayso Import {$results['msg']} \n";
    }
    protected function importVols($datax)
    {
        $files = array('Vols2008.csv','Vols2009.csv','Vols2010.csv','Vols.csv');
        foreach($files as $file)
        {
            $this->importVol($datax . $file);
        }
    }
    protected function importCert($file)
    {
        $params = array('inputFileName' => $file);
        $import = new CertificationImport($this->getEntityManager());
        $results = $import->process($params);

        echo "Zayso Import {$results['msg']} \n";
    }
    protected function importCerts($datax)
    {
        $files = array(
            'CoachBadge.csv',
            'SafeHaven.csv',
            'CoachSafeHaven.csv',
            'RefSafeHaven.csv',
            'RefNat.csv','RefNat2.csv','RefAdvanced.csv','RefInt.csv',
            'RefReg.csv','RefAssistant.csv','RefAssistant2.csv','RefU8.csv',
        );
        foreach($files as $file)
        {
            $this->importCert($datax . $file);
        }
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getEntityManager();

        $inputFileName = $input->getArgument('file');

        $datax = "C:/home/ahundiak/datax/eayso/vols/";
        
        $this->importVol('../datax/Vols.csv');

        //$this->importCerts($datax);
        return;        
    }
}
