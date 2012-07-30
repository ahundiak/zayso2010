<?php
namespace Zayso\CoreBundle\Manager;

use Zayso\CoreBundle\Entity\Account;

class AccountManager extends BaseManager
{
    public function newAccount() { return new Account; }
}

?>
