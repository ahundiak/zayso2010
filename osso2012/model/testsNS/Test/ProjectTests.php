<?php

namespace Test;

use \Cerad\Debug;
use \NatGames\Project\ProjectSeqnItem;
use \NatGames\Project\ProjectPersonItem;

class ProjectTests extends BaseTests
{
  protected $projectId = 52;
  protected $seqnKey = 'event';

  function getRepo()   { return $this->em->getRepository('NatGames\Project\ProjectItem'); }
  function getLogger() { return $this->em->getConfiguration()->getSQLLogger();        }

  /* ==================================================================
   * Project person Stuff
   */
  function testProjectDeletePerson()
  {
    $em = $this->em;

    $query = $em->createQuery('DELETE NatGames\Project\ProjectPersonItem item');
    $query->getResult();
  }
  function testProjectCreatePerson()
  {
    $em   = $this->em;
    $repo = $this->getRepo();

    $item = new ProjectPersonItem();
    $item->project = $em->getReference('NatGames\Project\ProjectItem',52);
    $item->person  = $em->getReference('NatGames\Person\PersonItem',1);
    $item->status  = 'Active';

    $data = new \NatGames\DataItem;
    $data->refBadge='Super Duper';
    $data->region = 894;
    
    $item->info = $data;

    // Somewhat conversly, this does not work
    // $item->person = 1;
    
    $em->persist($item);
    $em->flush();

  }
  function testProjectGetPerson()
  {
    $em   = $this->em;
    $repo = $em->getRepository('NatGames\Project\ProjectPersonItem');

    $project = $em->getReference('NatGames\Project\ProjectItem',52);
    $person  = $em->getPartialReference('NatGames\Person\PersonItem',1);

    // Even though they are relations, this does not work
    $search = array('_project' => $project, '_person' => $person);

    // Use regular keys
    $search = array('_project' => $project->id, '_person' => 1);

    $item = $repo->findOneBy($search);

    $this->assertNotNull($item);
    $this->assertEquals('Super Duper',$item->info->refBadge);
    $this->assertEquals(894,$item->info->region);
  }

  /* ==================================================================
   * SEQN Stuff
   */
  function testProjectDeleteSeqn()
  {
    $em = $this->em;
    $em->clear();

    $query = $em->createQuery('DELETE NatGames\Project\ProjectSeqnItem item');
    $query->getResult();
  }
  function testProjectCreateSeqn()
  {
    $item = new ProjectSeqnItem();
    $item->setProjectId($this->projectId);
    $item->setKey1($this->seqnKey);
    $item->setSeqn(1000);

    $em = $this->em;
    $em->persist($item);
    $em->flush();
  }
  function testProjectGetSeqn()
  {
    $repo = $this->getRepo();
    $next = $repo->getNextSeqn($this->projectId,$this->seqnKey);
    $this->assertEquals(1001,$next);

    $next = $repo->getNextSeqn($this->projectId,$this->seqnKey,5);
    $this->assertEquals(1002,$next);

    $next = $repo->getNextSeqn($this->projectId,$this->seqnKey);
    $this->assertEquals(1007,$next);

    $item = $repo->getSeqnItem($this->projectId,$this->seqnKey);
    $this->assertEquals(3,$item->getVersion());
  }
}
?>
