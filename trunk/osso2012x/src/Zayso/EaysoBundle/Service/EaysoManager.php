<?php
/* ========================================================================
 * The basic idea is to encapsulate as much of this stuff as possible in a service
 */
namespace Zayso\EaysoBundle\Service;

use Zayso\EaysoBundle\Component\Debug;

class EaysoManager
{
    protected $em = null;
    
    protected function getEntityManager() { return $this->em; }

    public function __construct($em)
    {
        $this->em = $em;
    }
}
?>
