<?php

namespace Zayso\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('core:delete')
            ->setDescription('Delete Schedule')
        ;
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
        
        $query->setParameter('type','pool');
        $count = $query->getResult();
        
        $query->setParameter('type','physical');
        $count = $query->getResult();
        
        echo 'Team count ' . $count . "\n";
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->deleteSchedule(79);
    }
}
?>
