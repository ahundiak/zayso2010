<?php

namespace Zayso\ArbiterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class PcastSociete
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="idsociete") 
     * @ORM\GeneratedValue
     */
    protected $id;

    public function getId()    { return $this->id; }
    
    /**
     * @ORM\ManyToMany(targetEntity="ImUser", mappedBy="societes")
     */
    private $users;

    public function __construct() 
    {
        $this->users = new ArrayCollection();
    }
}