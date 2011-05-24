<?php

namespace OSSO\User;

/**
 * @Entity
 * @Table(name="ossov.user_view")
 */
class UserItem
{
    /**
     * @Id
     * @Column(type="integer",name="member_id")
     * @GeneratedValue
     */
    private $id;

    /** @Column(type="string",name="account_uname") */
    private $account_uname;

    /** @Column(type="string",name="account_lname") */
    private $account_lname;

    public function getId()     { return $this->id; }
    public function getAccountName()  { return $this->account_uname; }
}

?>
