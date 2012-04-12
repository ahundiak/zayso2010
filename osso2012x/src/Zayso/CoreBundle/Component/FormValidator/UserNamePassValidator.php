<?php
namespace Zayso\CoreBundle\Component\FormValidator;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormValidatorInterface;

class UserNamePassValidator implements FormValidatorInterface
{
    public function __construct($em)
    {
        $this->em = $em;
    }
    protected function loadRowForUserName($userName)
    {
        $sql = <<<EOT
SELECT 
    account.id        AS accountId,
    account.user_name AS userName,
    account.user_pass AS userPass,
    account.status    AS accountStatus,
    member.id         AS memberId,
    member.status     AS memberStatus
FROM account
LEFT JOIN account_person AS member ON member.account_id = account.id AND member.account_relation = 'Primary'
WHERE user_name = :userName
EOT;
        
        $conn = $this->em->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('userName' => $userName));
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row;
    }
    protected function loadRowForEmail($email)
    {
        if (!$email) return null;
        
        $sql = <<<EOT
SELECT 
    account.id            AS accountId,
    account.user_name     AS userName,
    account.user_pass     AS userPass,
    account.status        AS accountStatus,
    account_person.id     AS memberId,
    account_person.status AS memberStatus
    
FROM person

LEFT JOIN account_person ON account_person.person_id = person.id AND account_person.account_relation = 'Primary'
LEFT JOIN account        ON account.id               = account_person.account_id

WHERE person.email = :email
EOT;
        
        $conn = $this->em->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('email' => $email));
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row;
    }
    protected function loadRowForAysoid($aysoid)
    {
        if (!$aysoid) return null;
        
        $sql = <<<EOT
SELECT 
    account.id            AS accountId,
    account.user_name     AS userName,
    account.user_pass     AS userPass,
    account.status        AS accountStatus,
    account_person.id     AS memberId,
    account_person.status AS memberStatus
    
FROM person_registered

LEFT JOIN person         ON person.id = person_registered.person_id
LEFT JOIN account_person ON account_person.person_id = person.id AND account_person.account_relation = 'Primary'
LEFT JOIN account        ON account.id               = account_person.account_id

WHERE person_registered.reg_key = :aysoid
EOT;
        
        $conn = $this->em->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('aysoid' => 'AYSOV' . $aysoid));
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row;
    }
    public function validate(FormInterface $form)
    {
        // Only check if username was changed
        $userName = $form['userName']->getData();
        $userPass = $form['userPass']->getData();

        // Try basic user name
        $row = $this->loadRowForUserName($userName);
        
        // Try email
        if (!isset($row['memberId']))
        {
            $row = $this->loadRowForEmail($userName);
        }
        // Try aysoid
        if (!isset($row['memberId']))
        {
            $row = $this->loadRowForAysoid($userName);
        }
        // Last chance
        if (!isset($row['memberId']))
        {
            // Give up
            $form['userName']->addError(new FormError('User name not found.'));
            return;
        }
        // Probably getting too fancy here but use this for password recovery
        $form->getData()->setId($row['accountId']);
       
        if ($userPass != 'fc546528f8571159556d657f676b2736') // zaysox
        {
            if ($row['userPass'] != $userPass)
            {
                $form['userPass']->addError(new FormError('Invalid password.'));
                return;
            }
        }
        if (!$row['memberId'])
        {
            $form['userName']->addError(new FormError('User name found but no primary account member.'));
            return;
        }
        // Store relevant information, 
        // 12 Apr 2012 these are useless
        if (isset($form['accountId'])) $form['accountId']->setData($row['accountId']);
        if (isset($form['memberId' ])) $form['memberId' ]->setData($row['memberId']);
 
        // In case email or ayso id was used
        $form->getData()->setUserName($row['userName']);
    }
}

?>
