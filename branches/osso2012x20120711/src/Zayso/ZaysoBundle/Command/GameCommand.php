<?php

namespace Zayso\ZaysoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Zayso\ZaysoBundle\Component\Import\ProjectImport;

use Zayso\ZaysoBundle\Entity\Project;

use Zayso\ZaysoBundle\Entity\Account;
use Zayso\ZaysoBundle\Entity\AccountPerson;

class GameCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('zayso:game')
            ->setDescription('Loads game information')
        ;
    }
    protected function getOsso2007Games()
    {
        $projectIds = array(71,72,73);

        $em = $this->getContainer()->get('doctrine')->getEntityManager('osso2007');
        $qb = $em->createQueryBuilder();

        $qb->addSelect('event');
        $qb->addSelect('field');
        $qb->addSelect('fieldSite');
        $qb->addSelect('eventTeam');
        $qb->addSelect('schTeam');
        $qb->addSelect('phyTeam');
        $qb->addSelect('projectItem');
        $qb->addSelect('phyTeamPersons');
        $qb->addSelect('phyTeamPerson');

        $qb->from('Osso2007Bundle:Event','event');

        $qb->leftJoin('event.field',          'field');
        $qb->leftJoin('field.fieldSite',      'fieldSite');
        $qb->leftJoin('event.eventTeams',     'eventTeam');
        $qb->leftJoin('eventTeam.schTeam',    'schTeam');
        $qb->leftJoin('schTeam.phyTeam',      'phyTeam');
        $qb->leftJoin('phyTeam.projectItem',  'projectItem');
        $qb->leftJoin('phyTeam.persons',      'phyTeamPersons');
        $qb->leftJoin('phyTeamPersons.person','phyTeamPerson');

        $qb->andWhere($qb->expr()->in('event.projectId',$projectIds));

        $qb->addOrderBy('event.eventId');
        $qb->addOrderBy('eventTeam.eventTeamTypeId');

        return $qb->getQuery()->getResult();

    }
    protected $orgs = array();
    protected function getOsso2007Org($id)
    {
        if (isset($this->orgs[$id])) return $this->orgs[$id];

        $em = $this->getContainer()->get('doctrine')->getEntityManager('osso2007');
        $qb = $em->createQueryBuilder();

        $qb->addSelect('unit');
        $qb->from('Osso2007Bundle:Unit','unit');
        $qb->andWhere($qb->expr()->in('unit.unitId',$id));

        $items = $qb->getQuery()->getResult();
        if (count($items) != 1)
        {
            $this->orgs[$id] = 'Unknown ' . $id;
            return $this->orgs[$id];
        }
        $key = $items[0]->getKeyx();
        $this->orgs[$id] = $key;
        return $key;
    }
    protected function exportOsso2007Games()
    {
        $games = $this->getOsso2007Games();

        $fp = fopen('../datax/games.csv','wt');
        fputs($fp,"EID,PID,ENUM,Type,Season,ST,EC,Year,Unit,FID,F Org,FS Key,F Key,Date,Time,");
        fputs($fp,"ET Type,ET Org,ET Age,ET Gen,");
        fputs($fp,"ST PID,ST Desc,ST Org,ST Age,ST Gen,");
        fputs($fp,"PT PID,PT Org,PT Age,PT Gen,PT Seq,PT Name,Eayso ID,Eayso Des,");
        fputs($fp,"HC ID,HC First Name,HC Last Name,HC AYSOID,HC ORG");
        fputs($fp,"\n");
        foreach($games as $game)
        {
            $gameTeams = $game->getGameTeams();
            if (count($gameTeams) == 0) $this->writeGameTeam($fp,$game,null);
            else
            {
                foreach($gameTeams as $gameTeam)
                {
                    $this->writeGameTeam($fp,$game,$gameTeam);
                }
            }
       }
        fclose($fp);
    }
    protected function writeGameTeam($fp,$game,$gameTeam)
    {
        $gameLine = sprintf("%d,%d,%d,%s,%s,%s,%s,%d,%s,%d,%s,%s,%s,%s,%s",
            $game->getId(),
            $game->getProjectId(),
            $game->getNum(),

            $game->getEventType(),
            $game->getSeasonType(),
            $game->getScheduleType(),
            $game->getEventClass(),

            $game->getRegYear(),
            $game->getRegionKey(),
            $game->getFieldId(),
            $game->getFieldRegionKey(), // Want field site as well?
            $game->getFieldSiteKey(),
            $game->getFieldKey(),
            $game->getDate(),
            $game->getTime()
        );
        if (!$gameTeam)
        {
            fputs($fp,$gameLine . "\n");
            return;
        }
        $gameTeamLine = sprintf(",%s,%s,%s,%s",
            $gameTeam->getTeamType(),
            $gameTeam->getRegionKey(),
            $gameTeam->getAgeKey(),
            $gameTeam->getGenderKey()
       );
       $schTeam = $gameTeam->getSchTeam();
       if (!$schTeam)
       {
            fputs($fp,$gameLine . $gameTeamLine . "\n");
            return;
       }
       // Could add season type and schedule type but assume the same as event info, really a project id
       $schTeamLine = sprintf(",%d,%s,%s,%s,%s",
           $schTeam->getProjectId(),
           $schTeam->getDescShort(),
           $schTeam->getRegionKey(),
           $schTeam->getAgeKey(),
           $schTeam->getGenderKey()
       );
       $phyTeam = $schTeam->getPhyTeam();
       if (!$phyTeam)
       {
            fputs($fp,$gameLine . $gameTeamLine . $schTeamLine . "\n");
            return;
       }
       $phyTeamLine = sprintf(",%d,%s,%s,%s,%d,%s,%s,%s",
           $phyTeam->getProjectId(),
           $phyTeam->getRegionKey(),
           $phyTeam->getAgeKey(),
           $phyTeam->getGenderKey(),
           $phyTeam->getDivisionSeqNum(),
           $phyTeam->getName(),
           $phyTeam->getEaysoId(),
           $phyTeam->getEaysoDes()
       );
       $person = $phyTeam->getHeadCoach();
       if (!$person)
       {
            fputs($fp,$gameLine . $gameTeamLine . $schTeamLine . $phyTeamLine . "\n");
            return;
       }
       $headCoachLine = sprintf(",%d,%s,%s,%s,%s",
           $person->getPersonId(),
           $person->getFirstName(),
           $person->getLastName(),
           $person->getAysoid(),
           $person->getRegionKey()
       );
       fputs($fp,$gameLine . $gameTeamLine . $schTeamLine . $phyTeamLine . $headCoachLine . "\n");
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $this->exportOsso2007Games();
    }
}
