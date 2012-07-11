<?php
namespace Zayso\ZaysoBundle\Component\Import;

use Zayso\ZaysoBundle\Component\Debug;
use Zayso\ZaysoBundle\Component\Import\BaseImport;
use Zayso\ZaysoBundle\Component\Manager\ProjectManager;

use Zayso\ZaysoBundle\Entity\Project;
use Zayso\ZaysoBundle\Entity\ProjectGroup;


class ProjectImport extends BaseImport
{
    //PID	Type	Description	Status	Date Begin	Date End	Mem Year	Cal Year	Season

    protected $record = array
    (
      'id'       => array('cols' => 'PID',          'req' => true, 'default' => 0),
      'parentId' => array('cols' => 'PPID',         'req' => true, 'default' => null),
      'group'    => array('cols' => 'Project Group','req' => true, 'default' => ''),
      'type'     => array('cols' => 'Type',         'req' => true, 'default' => ''),
      'desc'     => array('cols' => 'Description',  'req' => true, 'default' => ''),
      'status'   => array('cols' => 'Status',       'req' => true, 'default' => ''),
      'dateBeg'  => array('cols' => 'Date Begin',   'req' => true, 'default' => ''),
      'dateEnd'  => array('cols' => 'Date End',     'req' => true, 'default' => ''),
      'memYear'  => array('cols' => 'Mem Year',     'req' => true, 'default' => ''),
      'calYear'  => array('cols' => 'Cal Year',     'req' => true, 'default' => ''),
      'season'   => array('cols' => 'Season',       'req' => true, 'default' => ''),
    );
    public function __construct(ProjectManager $projectManager)
    {
        $this->projectManager = $projectManager;

        parent::__construct($projectManager->getEntityManager());
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
    public function processItem($item)
    {
        $em = $this->getEntityManager();

        if (!$item->id) return;
        $this->total++;

        $project = $this->projectManager->getProjectForId($item->id);
        if (!$project)
        {
            $project = new Project();
            $project->setId($item->id);
            $em->persist($project);
        }
        $project->setStatus     ($item->status);
        $project->setDescription($item->desc);

        $project->setProjectGroup($this->getProjectGroup($item->group));

        if ($item->parentId)
        {
            $parentProject = $this->projectManager->getProjectForId($item->parentId);
            $project->setParent($parentProject);
        }
        else $project->setParent(null);

        if (($this->total % 100) == 0) $em->flush();
    }
    public function process($params = array())
    {
        // $this->projectId = $params['projectId'];
        return parent::process($params);
    }
}

?>
