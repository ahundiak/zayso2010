<?php
namespace Cerad;

class CountListener
{
  public $updated = 0;
  public $deleted = 0;
  public $inserted = 0;

  public function __construct($em)
  {
    $em->getEventManager()->addEventListener(
      array(
        \Doctrine\ORM\Events::postUpdate,
        \Doctrine\ORM\Events::postRemove,
        \Doctrine\ORM\Events::postPersist,
      ),
      $this);
  }
  public function postUpdate(\Doctrine\ORM\Event\LifecycleEventArgs $e)
  {
    $this->updated++;
  }
  public function postRemove(\Doctrine\ORM\Event\LifecycleEventArgs $e)
  {
    $this->deleted++;
  }
  public function postPersist(\Doctrine\ORM\Event\LifecycleEventArgs $e)
  {
    $this->inserted++;
  }
}
?>
