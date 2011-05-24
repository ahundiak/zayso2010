<?php

namespace OSSO2012\Project;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="OSSO2012\Project\ProjectRepo")
 * @Table(name="osso2012.project")
 */
class ProjectItem
{
  /**
   * @Id
   * @Column(type="integer",name="id")
   * @GeneratedValue
   */
  private $id;


  public function getId()     { return $this->id;     }
  public function getType()   { return $this->type;   }
  public function getField()  { return $this->field;  }
  public function getClass()  { return $this->class;  }
  public function getStatus() { return $this->status; }

  public function getDtBeg() { return $this->dtBeg; }
  public function getDtEnd() { return $this->dtEnd; }

  public function setType  ($value) { $this->type   = $value; }
  public function setField ($value) { $this->field  = $value; }
  public function setClass ($value) { $this->class  = $value; }
  public function setStatus($value) { $this->status = $value; }

  public function setDtBeg($value) { $this->dtBeg = $value; }
  public function setDtEnd($value) { $this->dtEnd = $value; }

}

?>
