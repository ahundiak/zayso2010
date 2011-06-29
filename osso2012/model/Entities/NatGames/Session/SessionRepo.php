<?php

namespace NatGames\Session;

use Doctrine\ORM\EntityRepository;

class SessionRepo extends EntityRepository
{
  // Really the only customized thing here
  protected $cookieName = 'NatGames2012';

  protected $sessionId = null;
  protected $items     = array();
  protected $ts        = null;

  protected function getTimeStamp()
  {
    if (!$this->ts) $this->ts = date('YmdHis');
    return $this->ts;
  }
  public function genSessionId()
  {
    if ($this->sessionId) return $this->sessionId;

    $name = $this->getCookieName();

    if (isset($_COOKIE[$name])) {
      $this->sessionId = $_COOKIE[$name];
      return $this->sessionId;
    }
    $sessionId = md5(uniqid());

    setcookie($name,$sessionId,mktime(0, 0, 0, 12, 31, 2015));

    $this->sessionId = $sessionId;

    return $this->sessionId;
  }
  public function setSessionId($sessionId)
  {
    $this->sessionId = $sessionId;
  }
  public function getCookieName() { return $this->cookieName; }
  public function clearCookie()
  {
    $this->sessionId = null;
    setcookie($this->getCookieName(),'',time() - 36000);
  }
  public function load($name)
  {
    if (isset($this->items[$name])) return $this->items[$name];

    $sessionId = $this->genSessionId();
    $app       = $this->getCookieName();

    $search = array(
        'app'  => $app,
        'key'  => $sessionId,
        'name' => $name);

    $sessionData = $this->findOneby($search);

    if (!$sessionData)
    {
      $sessionData = new SessionDataItem();
      $sessionData->setApp ($app);
      $sessionData->setName($name);
      $sessionData->setTsCreated($this->getTimeStamp());
      $sessionData->setTsUpdated($this->getTimeStamp());
    }
    $this->items[$name] = $sessionData;

    return $sessionData;
  }
  public function save($sessionData)
  {
    $sessionData->setSessionId($this->genSessionId());
    $sessionData->setTsUpdated($this->getTimeStamp());
    $this->_em->persist($sessionData);
    $this->_em->flush();
  }
  public function search($search)
  {
    $names = array('account-signin');

    $em = $this->_em;
    $qb = $em->createQueryBuilder();
    $qb->addSelect('data');
    $qb->from('\Session\SessionDataItem','data');
    $qb->andWhere($qb->expr()->in('data.name',$names));
    $qb->addOrderBy('data.ts_updated','DESC');

    $query = $qb->getQuery();
    $items = $query->getResult();
    foreach($items as $item)
    {
      $item->loadData();
    }
    return $items;
  }
}
?>
