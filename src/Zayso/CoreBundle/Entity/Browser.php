<?php

namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="browser")
 */
class Browser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string",name="id")
     */
    protected $id;

    public function setId($id) { $this->id = $id; }
    public function getId()    { return $this->id; }
}