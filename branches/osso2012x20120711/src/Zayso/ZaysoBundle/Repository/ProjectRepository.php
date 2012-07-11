<?php
namespace Zayso\ZaysoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

use Zayso\ZaysoBundle\Entity\Project;
use Zayso\ZaysoBundle\Entity\ProjectSeqn;
use Zayso\ZaysoBundle\Entity\ProjectPerson;

use Zayso\ZaysoBundle\Entity\Person;
use Zayso\ZaysoBundle\Entity\PersonRegistered;
use Zayso\ZaysoBundle\Entity\AccountPerson;

/* =========================================================================
 * The repository
 */
class ProjectRepository extends EntityRepository
{
  /* ========================================================================
   * Proejct person interface
   */
  public function loadProjectPerson($project,$person)
  {
    $em = $this->getEntityManager();
    
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
    if (!is_object($project)) $project = $em->getReference('ZaysoBundle:Project',$projectId);
    if (!is_object($person))  $person  = $em->getReference('ZaysoBundle:Person', $personId);
    
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
    public function loadProjectSeqn($project,$name,$seqn=1000)
    {
        $em = $this->getEntityManager();

        // Search for existing
        if (is_object($project)) $projectId = $project->getId();
        else                     $projectId = $project;

        $search = array('project' => $projectId, 'name' => $name);

        $repo = $em->getRepository('ZaysoBundle:ProjectSeqn');
        $item = $repo->findOneBy($search);
        if ($item) return $item;

        // Make one
        if (!is_object($project)) $project = $em->getReference('ZaysoBundle:Project',$projectId);

        $item = new ProjectSeqn();
        $item->setProject($project);
        $item->setName   ($name);
        $item->setSeqn   ($seqn);

        $em->persist($item);
  //$em->flush($item);

        return $item;
    }
    public function getNextSeqn($project,$name,$count = 1)
    {
        $em = $this->getEntityManager();

        $item = $this->loadProjectSeqn($project,$name);

        $seqn = $item->getSeqn();

        $item->setSeqn($seqn + $count);

        // Required for defered explicit
        $em->persist($item);

        // This needs to be surrounded by try/catch version exception
        // $em->flush();

        return $seqn + 1;
    }
    public function checkSeqn($project,$name,$seqn)
    {
        $em = $this->getEntityManager();

        $item = $this->loadProjectSeqn($project,$name);

        if ($seqn > $item->getSeqn())
        {
            $item->setSeqn($seqn);
            $em->persist($item);
        }
    }
}
?>
