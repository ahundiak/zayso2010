<?php

namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Zayso\CoreBundle\Component\Debug;

/**
 * @ORM\Entity()
 * @ORM\Table(name="log")
 * @ORM\ChangeTrackingPolicy("NOTIFY") * 
 */
class Log extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     * @ORM\GeneratedValue
    */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id",nullable=true)
     */
    protected $project;
    
    /** 
     * @ORM\Column(type="string",name="dtg",length=20,nullable=false) 
     */
    protected $dtg = '';
    
    /** 
     * Try to avoid using but could be schedule,physical,permanent etc
     * @ORM\Column(type="string",name="type",length=20,nullable=false) 
     */
    protected $type = '';
   
    /** 
     * @ORM\Column(type="string",name="message",length=100,nullable=false) 
     */
    protected $message = '';
    
    public function getDtg      () { return $this->dtg;     }
    public function getType     () { return $this->type;    }
    public function getMessage  () { return $this->message; }
    
    public function setDtg    ($value) { $this->onScalerPropertySet('dtg',     $value); }
    public function setType   ($value) { $this->onScalerPropertySet('type',    $value); }
    public function setMessage($value) { $this->onScalerPropertySet('message', $value); }
   
}
?>
