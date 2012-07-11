<?php
/* ========================================================================
 * The basic idea is to encapsulate as much of this stuff as possible in a service
 */
namespace Zayso\AreaBundle\Component\Manager;

use Zayso\CoreBundle\Component\Debug;

use Zayso\CoreBundle\Entity\Team;
use Zayso\CoreBundle\Entity\Event;
use Zayso\CoreBundle\Entity\EventTeam;
use Zayso\CoreBundle\Entity\ProjectField;

use Zayso\AreaBundle\Component\FormType\Search\PersonSearchEntity;

class SearchManager extends BaseManager
{
    public function newSearchEntity() { return new PersonSearchEntity(); }
    
    protected function getParam($params,$name,$default = null)
    {
        if (!isset($params[$name])) return $default;
        return $params[$name];
    }
    public function search($params)
    {
        $firstName = $params['firstName'];
        $lastName  = $params['lastName'];
        $nickName  = $params['nickName'];
        
        if (!$firstName && !$lastName && !$nickName) return array();
        
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        //$qb->addSelect('person');
        //$qb->addSelect('personRegistered');
        
        $qb->addSelect('person , personRegistered');
        
        $qb->from('ZaysoCoreBundle:Person',      'person');
        $qb->leftJoin('person.registeredPersons','personRegistered');
        
        if ($firstName) $qb->andWhere($qb->expr()->like('person.firstName',$qb->expr()->literal($firstName . '%')));
        if ($lastName)  $qb->andWhere($qb->expr()->like('person.lastName', $qb->expr()->literal($lastName  . '%')));
        if ($nickName)  $qb->andWhere($qb->expr()->like('person.nickName', $qb->expr()->literal($nickName  . '%')));
        
        // Just ayso for now
        $qb->andWhere($qb->expr()->eq('personRegistered.regType', $qb->expr()->literal('AYSOV')));
                
        $query = $qb->getQuery();
        $items = $query->getResult();
        return $items;
    }
}
?>
