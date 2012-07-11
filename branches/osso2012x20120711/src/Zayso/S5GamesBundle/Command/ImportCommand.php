<?php

namespace Zayso\S5GamesBundle\Command;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Component\Import\ExcelBaseImport;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('s5games:import')
            ->setDescription('Import Testing')
        ;
    }
    protected function testExport2011Schedule()
    {
        $export = $this->getContainer()->get('zayso_s5games.game2011.export');
        
        file_put_contents('../datax/S5Games2011.xls',$export->generate());
        
    }
    protected function testImport2011Schedule()
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
    protected function testImport2012Teams()
    {
        $import = $this->getContainer()->get('zayso_core.game.teams.import');
        
        $params = array(
            'projectId'     => 62,
            'inputFileName' => '../datax/S5Games2012Teams20120515.xls',
            'sheetName'     => 'Teams',
        );
        $results = $import->process($params);
        Debug::dump($results);
    }
    protected function testImport2012Schedule()
    {
        $import = $this->getContainer()->get('zayso_s5games.schedule.import');
        
        $params = array();
        $params['projectId'] = 62;
        $params['inputFileName'] = '../datax/S5GamesSchedule20120607.xls';
        
        $import->process($params);
        echo $import->getResultMessage() . "\n";
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
      //$this->deleteSchedule(62);
        $this->testImport2012Schedule();
    }
    protected function deleteSchedule($projectId)
    {
        $em = $this->getContainer()->get('zayso_core.game.entity_manager');
        
        $project = $em->getReference('ZaysoCoreBundle:Project',$projectId);
        
        $qb = $em->createQueryBuilder();

        $qb->addSelect('game');
        
        $qb->from('ZaysoCoreBundle:Event', 'game');

        $qb->andWhere($qb->expr()->eq('game.project',$qb->expr()->literal($projectId)));
        
        $games = $qb->getQuery()->getResult();
        foreach($games as $game)
        {
            $em->remove($game); // Takes care of event_team and event_person
        }
        $em->flush();
        echo 'Game count ' . count($games) . "\n";
        
        // Need to do teams from the bottom up
        $dql = 'DELETE ZaysoCoreBundle:Team team WHERE team.project = :project AND team.type=:type';
        
        $query = $em->createQuery($dql);
        $query->setParameter('project', $project);
        
        $query->setParameter('type','game');
        $count = $query->getResult();
        
        $query->setParameter('type','playoff');
        $count = $query->getResult();
        
        $query->setParameter('type','pool');
        $count = $query->getResult();
        
//        $query->setParameter('type','physical');
//        $count = $query->getResult();
        
        echo 'Team count ' . $count . "\n";
    }
}
