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
      'status'    => array('cols' => 'status',     'req' => true, 'default' => 'Active'),
    );
    public function __construct(ProjectManager $projectManager)
    {
        $this->projectManager = $projectManager;

        parent::__construct($projectManager->getEntityManager());
    }
    protected $orgs = array();
    public function processItem($item)
    {
        $em = $this->getEntityManager();

        $id = $item->orgKey;
        if (!$id) return;

        $this->total++;
        
        if (isset($this->orgs[$id])) $org = $this->orgs[$id];
        else
        {
            $org = $this->projectManager->getOrgForId($id);
            if (!$org)
            {
                $org = new Org();
                $org->setId($id);
                $em->persist($org);
            }
            $this->orgs[$id] = $org;
        }
        $org->setDesc1($item->desc1);
        $org->setDesc2($item->desc2);
        $org->setCity ($item->city);
        $org->setState($item->state);

        if ($item->status) $org->setStatus($item->status);

        $parentId = $item->parentKey;
        if ($parentId)
        {
            if (isset($this->orgs[$parentId])) $parentOrg = $this->orgs[$parentId];
            else
            {
                $parentOrg = $this->projectManager->getOrgForId($parentId);
                if (!$parentOrg) die('Parent Org: ' . $parentId);
                else
                {
                    $this->orgs[$parentId] = $parentOrg;
                }
            }
            $org->setParent($parentOrg);
        }
        //else $org->setParent(null);
        // $em->flush();

        if (($this->total % 100) == 0) $em->flush();
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
