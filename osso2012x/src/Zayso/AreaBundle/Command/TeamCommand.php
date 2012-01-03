<?php
namespace Zayso\AreaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TeamCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('area:team')
            ->setDescription('Team Tests')
        ;
    }
    protected function getTeamManager() { return $this->getContainer()->get('zayso_area.team.manager'); }
    protected function getProjectId()   { return 77; }
    
    protected function test1()
    {
        $teamManager = $this->getTeamManager();
        $em = $teamManager->getEntityManager();
        
        $project = $teamManager->getProjectReference($this->getProjectId());
        
        $team = $teamManager->newTeamPhysicalEayso();
        
        $team->setProject($project);
        $team->setTeamKey('U08C01');
        
        $team->setTeamName  ('Green Machine');
        $team->setTeamColors('Green/Black/Green');
        
        $em->persist($team);
        $em->flush();
        
    }
    protected function test2()
    {
        $teamManager = $this->getTeamManager();
        $projectId = $this->getProjectId();
        
        $team = $teamManager->loadTeamPhysicalForTeamKey($projectId,'U08C01');

        if (!$team) echo 'No team for U08C01' . "\n";
        else        echo 'Found team: ' . $team->getTeamKey() . ' ' . $team->getTeamName() . "\n";
        
    }
    protected function test3()
    {
        $import = $this->getContainer()->get('zayso_area.team.physical.eayso.summary.import');
        
        $params = array();
        $params['projectId'] = $this->getProjectId();
        $params['inputFileName'] = '../datax/Teams0498Winter2011.csv';
        
        $import->process($params);
        echo $import->getResultMessage() . "\n";
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
      //$this->test1();
      //$this->test2();
        $this->test3();
    }
}
?>
