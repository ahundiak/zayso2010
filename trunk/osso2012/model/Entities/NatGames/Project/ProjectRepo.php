<?php

namespace NatGames\Project;

/* =========================================================================
 * The repository
 */
class ProjectRepo extends \NatGames\EntityRepo
{
  protected $types    = array();
  protected $fields   = array();
  protected $classes  = array();
  protected $statuses = array();

  /* ========================================================================
   * Proejct person interface
   */
  public function saveProjectPerson($item)
  {
    $item->help = 'Please';

    $em = $this->_em;
    //$em->clear();
    $em->persist($item);
    $em->flush();
    //print_r($item->refLevels);
    //die('persisted ' . $item->accountCreate->refBadge);
  }
  public function loadProjectPerson($project,$person)
  {
    $em = $this->_em;
    
    // Search for existing
    $repo = $em->getRepository('NatGames\Project\ProjectPersonItem');

    if (is_object($project)) $projectId = $project->id;
    else                     $projectId = $project;
    
    if (is_object($person))  $personId = $person->id;
    else                     $personId = $person;
    
    $search = array('_project' => $projectId, '_person' => $personId);

    $item = $repo->findOneBy($search);
    if ($item) return $item;
    
    // Make one
    if (!is_object($project)) $project = $em->getPartialReference('NatGames\Project\ProjectItem',$projectId);
    if (!is_object($person))  $person  = $em->getPartialReference('NatGames\Person\PersonItem',  $personId);
    
    $item = new \NatGames\Project\ProjectPersonItem();
    $item->project = $project;
    $item->person  = $person;
    $item->status  = 'Active';
    
    return $item;
  }
  /* ========================================================================
   * Some sequence number stuff
   */
  // Gets the next seqn for a given project
  public function addSeqn($projectId,$key,$seqn = 1000)
  {
    $item = new ProjectSeqnItem();
    $item->setProjectId($projectId);
    $item->setKey1     ($key);
    $item->setSeqn     ($seqn);

    $em = $this->_em;
    $em->persist($item);
    $em->flush();

    return $item;
  }
  public function getSeqnItem($projectId,$key)
  {
    $em = $this->_em;

    $params = array('projectId' => $projectId, 'key1' => $key);

    $item = $em->getRepository('NatGames\Project\ProjectSeqnItem')->findOneBy($params);

    return $item;
  }
  public function getNextSeqn($projectId,$key,$count = 1)
  {
    $em = $this->_em;

    $item = $this->getSeqnItem($projectId,$key);

    if (!$item) $item = $this->addSeqn($projectId,$key);

    $seqn = $item->getSeqn();

    $item->setSeqn($seqn + $count);
    $em->persist($item);

    // This needs to be surrounded by try/catch version exception
    $em->flush();

    return $seqn + 1;
  }
}
?>
