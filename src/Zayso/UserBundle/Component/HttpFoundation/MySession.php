<?php
namespace Zayso\UserBundle\Component\HttpFoundation;

use Symfony\Component\HttpFoundation\Session;

class MySession extends Session
{
    public function getDirect($name) { return $this->storage->getDirect($name); }
    
    public function setDirect($name,$data) { return $this->storage->setDirect($name,$data); }
}
?>
