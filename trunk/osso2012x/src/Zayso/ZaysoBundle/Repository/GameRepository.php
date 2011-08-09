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
class GameRepository extends EntityRepository
{
    /* ========================================================================
     * Basic one game
     */
    public function loadGame($project,$num)
    {
        $em = $this->getEntityManager();
    
        if (is_object($project)) $projectId = $project->getId();
        else                     $projectId = $project;
    
        $search = array('project' => $projectId, 'num' => $num);

        $item = $this->findOneBy($search);
        if ($item) return $item;

        return null;
    }
    /* ========================================================================
     * Schedule team
     */
    public function loadSchTeam($project,$key)
    {
        $em = $this->getEntityManager();

        // Search for existing
        $repo = $em->getRepository('ZaysoBundle:SchTeam');

        if (is_object($project)) $projectId = $project->getId();
        else                     $projectId = $project;

        $search = array('project' => $projectId, 'teamKey' => $key);

        $item = $repo->findOneBy($search);
        if ($item) return $item;

        $search = array('project' => $projectId, 'teamKey2' => $key);
        $item = $repo->findOneBy($search);
        if ($item) return $item;

        return null;
    }
}
?>
