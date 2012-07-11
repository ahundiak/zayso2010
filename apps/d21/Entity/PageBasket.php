<?php
namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="page_basket")

 */
class PageBasket
{
    /**
     * @ORM\Id 
     * @ORM\Column(type="integer", name="pb_id")
     * @ORM\GeneratedValue
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Entity\Page")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="p_id")
    */
    private $page;
    
    public function setPage($page)
    {
        $this->page = $page;
    }
    public function getId() { return $this->id; }

}
?>
