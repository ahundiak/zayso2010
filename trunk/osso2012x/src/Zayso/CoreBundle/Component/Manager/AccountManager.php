<?php

namespace Zayso\CoreBundle\Component\Manager;

use Zayso\CoreBundle\Component\Debug;

class AccountManager
{
    protected $em;
    protected $masterPassword;
    
    public function __construct($em,$masterPassword)
    {
        $this->em = $em;
        $this->masterPassword = $masterPassword;
    }
    public function getEntityManager() { return $this->em; }
    public function clear() { $this->em->clear(); }
    public function flush() { $this->em->flush(); }
    
    /* ===========================================================
     * Person loading routines
     */
    public function loadPersonForAysoid($aysoid)
    {
        return $this->loadPerson(array('aysoid' => $aysoid));
    }
    public function loadPerson($params)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('person');
        $qb->addSelect('registeredPerson');
        $qb->addSelect('projectPerson');
        $qb->addSelect('org');

        $qb->from('ZaysoCoreBundle:Person','person');

        $qb->leftJoin('person.registeredPersons','registeredPerson');
        $qb->leftJoin('person.projectPersons',   'projectPerson');
        $qb->leftJoin('projectPerson.project',   'project');
        $qb->leftJoin('person.org',              'org');

        if (isset($params['aysoid']))
        {
            $qb->andWhere($qb->expr()->eq('registeredPerson.regKey',$qb->expr()->literal($params['aysoid'])));
        }
        if (isset($params['projectId']))
        {
            $qb->andWhere($qb->expr()->in('project.id',$params['projectId']));
        }
        $query = $qb->getQuery();
        
        $items = $query->getResult();

        if (count($items) == 1) return $items[0];

        return null;
    }    
}
?>
