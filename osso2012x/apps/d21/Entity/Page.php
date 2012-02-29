<?php
namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="page")
 */
class Page
{
    /**
     * @ORM\Id 
     * @ORM\Column(type="integer", name="p_id")
     * @ORM\GeneratedValue
     */
    private $id;
    
    /**
     * @ORM\OneToMany(targetEntity="Entity\PageBasket", mappedBy="page")
     */
    protected $pageBaskets;

    public function __construct()
    {
        $this->pageBaskets = new ArrayCollection();
    }
    public function getId()          { return $this->id; }
    public function getPageBaskets() { return $this->pageBaskets; }
}
?>
