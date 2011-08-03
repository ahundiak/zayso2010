<?php

namespace Session;

use Doctrine\ORM\EntityRepository;

class SessionRepo extends EntityRepository
{
  // Really the only customized thing here
  protected $cookieName = 'Zayso2012';

  protected $sid   = null;
  protected $items = array();
  protected $ts    = null;

  protected function getTimeStamp()
  {
    if (!$this->ts) $this->ts = date('YmdHis');
    return $this->ts;
  }
  public function genSessionId()
  {
    if ($this->sid) return $this->sid;

    $name = $this->getCookieName();

    if (isset($_COOKIE[$name])) {
      $this->sid = $_COOKIE[$name];
      return $this->sid;
    }
    $sid = md5(uniqid());

    setcookie($name,$sid,mktime(0, 0, 0, 12, 31, 2015));

    $this->sid = $sid;

    return $this->sid;
  }
  public function setSessionId($sid)
  {
    $this->sid = $sid;
  }
  public function getCookieName()      { return $this->cookieName; }
  public function setCookieName($name) { $this->cookieName = $name; }
  public function clearCookie()
  {
    $this->sid = null;
    setcookie($this->getCookieName(),'',time() - 36000);
  }
  public function load($name)
  {
    if (isset($this->items[$name])) return $this->items[$name];

    $sid = $this->genSessionId();
    $app = $this->getCookieName();

    $search = array
    (
      'app' => $app,
      'sid' => $sid,
      'cat' => $name
    );

    $sessionData = $this->findOneby($search);

    if (!$sessionData)
    {
      $sessionData = new SessionDataItem();
      $sessionData->app = $app;
      $sessionData->cat = $name;
      $sessionData->tsCreated = $this->getTimeStamp();
      $sessionData->tsUpdated = $this->getTimeStamp();
    }
    $this->items[$name] = $sessionData;

    return $sessionData;
  }
  public function save($sessionData)
  {
    $sessionData->sid       = $this->genSessionId();
    $sessionData->tsUpdated = $this->getTimeStamp();
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
