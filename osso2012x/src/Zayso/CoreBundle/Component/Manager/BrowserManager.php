<?php
namespace Zayso\CoreBundle\Component\Manager;

use Zayso\CoreBundle\Entity\Browser;

class BrowserManager
{
    protected $em;
    
    public function __construct($em) { $this->em = $em; }
    public function getEntityManager() { return $this->em; }
    
    public function add($browserId)
    {
        $browserId = trim($browserId);
        if (!$browserId) return null;
        
        $em = $this->getEntityManager();
        $browser = $em->find('ZaysoCoreBundle:Browser',$browserId);
        if ($browser) return $browser;
        
        $browser = new Browser();
        $browser->setId($browserId);
        $em->persist($browser);
        $em->flush();
        
        return $browser;
        
    }
    
}
?>
