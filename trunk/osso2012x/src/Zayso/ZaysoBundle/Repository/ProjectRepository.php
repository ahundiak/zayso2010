<?php
namespace Zayso\ZaysoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

use Zayso\ZaysoBundle\Entity\Project;
use Zayso\ZaysoBundle\Entity\ProjectPerson;
use Zayso\ZaysoBundle\Entity\Person;
use Zayso\ZaysoBundle\Entity\PersonRegistered;
use Zayso\ZaysoBundle\Entity\AccountPerson;

/* =========================================================================
 * The repository
 */
class ProjectRepository extends EntityRepository
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
    $repo = $em->getRepository('ZaysoBundle:ProjectPerson');

    if (is_object($project)) $projectId = $project->getId();
    else                     $projectId = $project;
    
    if (is_object($person))  $personId = $person->getId();
    else                     $personId = $person;
    
    $search = array('project' => $projectId, 'person' => $personId);

    $item = $repo->findOneBy($search);
    if ($item) return $item;
    
    // Make one
    if (!is_object($project)) $project = $em->getPartialReference('ZaysoBundle:Project',$projectId);
    if (!is_object($person))  $person  = $em->getPartialReference('ZaysoBundle:Person', $personId);
    
    $item = new ProjectPerson();
    $item->setProject($project);
    $item->setPerson ($person);
    $item->setStatus ('Active');

    $em->persist($item);
    $em->flush($item);
    
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
