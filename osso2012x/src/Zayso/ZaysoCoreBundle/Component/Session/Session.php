<?php
namespace Zayso\ZaysoCoreBundle\Component\Session;

use Symfony\Component\HttpFoundation\Session as BaseSession;

class Session extends BaseSession
{
    public function getDirect($name) { return $this->storage->getDirect($name); }
    
    public function setDirect($name,$data) { return $this->storage->setDirect($name,$data); }
}
?>
