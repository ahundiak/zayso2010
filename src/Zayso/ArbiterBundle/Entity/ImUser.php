<?php

namespace Zayso\ArbiterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class ImUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="iduser") 
     * @ORM\GeneratedValue
     */
    protected $id;

    public function getId()    { return $this->id; }
    
    /**
     * @ORM\ManyToMany(targetEntity="PcastSociete", inversedBy="users")
     * @ORM\JoinTable(name="PcastLienusersociete",
     *   joinColumns={@ORM\JoinColumn(name="ImUser_iduser", referencedColumnName="iduser")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="PcastLienusersociete_idsociete", referencedColumnName="idsociete")}
     * )
     */
    private $societes;

    public function getSocietes()
    {
        return $this->societes;
    }

    public function addSociete(PcastSociete $societe)
    {
        $this->societes[] = $societe;
    }
    public function __construct() 
    {
        $this->societes = new ArrayCollection();
    }

}