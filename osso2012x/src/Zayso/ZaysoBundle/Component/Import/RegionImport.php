<?php
namespace Zayso\ZaysoBundle\Component\Import;

use Zayso\ZaysoBundle\Component\Debug;
use Zayso\ZaysoBundle\Component\Import\BaseImport;
use Zayso\ZaysoBundle\Component\Manager\ProjectManager;

use Zayso\ZaysoBundle\Entity\Org;
use Zayso\ZaysoBundle\Entity\Project;
use Zayso\ZaysoBundle\Entity\ProjectGroup;

class RegionImport extends BaseImport
{
    //PID	Type	Description	Status	Date Begin	Date End	Mem Year	Cal Year	Season

    protected $record = array
    (
      'orgKey'    => array('cols' => 'org_key',    'req' => true, 'default' => null),
      'parentKey' => array('cols' => 'parent_key', 'req' => true, 'default' => null),
      'desc1'     => array('cols' => 'desc1',      'req' => true, 'default' => ''),
      'desc2'     => array('cols' => 'desc2',      'req' => true, 'default' => ''),
      'city'      => array('cols' => 'city',       'req' => true, 'default' => ''),
      'state'     => array('cols' => 'state',      'req' => true, 'default' => ''),
    );
    public function __construct(ProjectManager $projectManager)
    {
        $this->projectManager = $projectManager;

        parent::__construct($projectManager->getEntityManager());
    }
    public function processItem($item)
    {
        $em = $this->getEntityManager();

        if (!$item->orgKey) return;
        $this->total++;
        
        $org = $this->projectManager->getOrgForKey($item->orgKey);
        if (!$org)
        {
            $org = new Org();
            $org->setId($item->orgKey);
            $em->persist($org);
        }
        $org->setDesc1($item->desc1);
        $org->setDesc2($item->desc2);
        $org->setCity ($item->city);
        $org->setState($item->state);

        // $project->setProjectGroup($this->getProjectGroup($item->group));

        if ($item->parentKey)
        {
            $parentOrg = $this->projectManager->getOrgForKey($item->parentKey);
            if ($parentOrg) $org->setParent($parentOrg);
            else die('Parent Org: ' . $item->parentKey);
        }
        //else $org->setParent(null);
        $em->flush();

        // if (($this->total % 100) == 0) $em->flush();
    }
    public function process($params = array())
    {
        // $this->projectId = $params['projectId'];
        return parent::process($params);
    }
    protected $projectGroups = array();
    public function getProjectGroup($key)
    {

        if (isset($this->projectGroups[$key])) return $this->projectGroups[$key];

        $group = $this->projectManager->getProjectGroupForKey($key);
        if (!$group)
        {
            $group = new ProjectGroup();
            $group->setKey($key);
            $group->setDescription($key);
            $group->setStatus('Active');
            $this->getEntityManager()->persist($group);
        }
        $this->projectGroups[$key] = $group;
        return $group;
    }
}
?>
