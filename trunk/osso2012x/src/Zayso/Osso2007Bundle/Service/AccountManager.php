<?php
/* ========================================================================
 * The basic idea is to encapsulate as much of this stuff as possible in a service
 */
namespace Zayso\Osso2007Bundle\Service;

use Zayso\Osso2007Bundle\Component\Debug;

class AccountManager
{
    protected $em = null;
    
    protected function getEntityManager() { return $this->em; }

    public function __construct($em)
    {
        $this->em = $em;
    }
    public function flush()
    {
        return $this->getEntityManager()->flush();
    }
    public function persist($entity)
    {
        return $this->getEntityManager()->persist($entity);
    }
    public function checkAccount($uname,$upass = null)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('account');
        $qb->addSelect('memberx'); // member does not work?
        $qb->addSelect('person');
        $qb->addSelect('openid');

        $qb->from('Osso2007Bundle:Account','account');

        $qb->leftJoin('account.members','memberx');
        $qb->leftJoin('memberx.person', 'person');
        $qb->leftJoin('account.openids','openid');

        $qb->andWhere($qb->expr()->eq('account.accountUser',':uname'));
        $qb->setParameter('uname',$uname);

        $query = $qb->getQuery();
        $items = $query->getResult();

        if (count($items) == 1) return $items[0];

        return null;
    }
}
?>
