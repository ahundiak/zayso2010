<?php
namespace Entity;

/**
 * @Entity
 * @Table(name="actions")
 * @MappedSuperClass
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="action_type", type="string")
 * @DiscriminatorMap({"comments" = "Comments"})
 * @HasLifecycleCallbacks
 */
class Action {

    /**
     * @Id 
     * @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @Column(type="string", length=300, nullable=true) */
    public $name;

    /** @Column(name="action_date", type="datetime", columnDefinition="datetime", nullable=false) */
    protected $action_date;

    /** @PrePersist */
    public function updated() {
        $this->action_date = new \DateTime("now");
    }

}
?>
