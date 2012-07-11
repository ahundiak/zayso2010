<?php
namespace Entity;

/**
 * @Entity
 * @Table(name="comments")
 * @HasLifecycleCallbacks
 */
class Comments extends Action {

    /**
     * @Id @Column(name="id", type="bigint",length=15)
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @Column(name="discovery_id", type="integer", nullable=true) */
    protected $discovery_id;

    /** @Column(name="comment", type="string", length=255, nullable=true) */
    protected $comment;

    /** @Column(name="comment_date", type="datetime", columnDefinition="datetime", nullable=true) */
    protected $comment_date;

    /**
     *  ManyToOne(targetEntity="Members",inversedBy="comments", cascade={"persist"})
     *  JoinColumn(name="userid", referencedColumnName="id")
     */
    protected $author;


    public function __construct() {
        $this->action_date = $this->comment_date = new \DateTime("now");
        $this->comment_date = new \DateTime("now");
    }
    public function setComment($comment) { $this->comment = $comment; }
    public function getComment()         { return $this->comment; }
}
?>
