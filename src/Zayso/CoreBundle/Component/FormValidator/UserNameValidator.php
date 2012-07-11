<?php
namespace Zayso\CoreBundle\Component\FormValidator;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormValidatorInterface;

class UserNameValidator implements FormValidatorInterface
{
    public function __construct($em)
    {
        $this->em = $em;
    }
    public function validate(FormInterface $form)
    {
        // Only check if username was changed
        $userName  = $form['userName']->getData();
        if (isset($form['userNamex']))
        {
            $userNamex = $form['userNamex']->getData();
            if ($userName == $userNamex) return;
        }
        // Should probably be a guard against blank data
        if (!$userName)
        {
            $form['userName']->addError(new FormError('Account Name cannot be blank.'));
            return;
        }
        // Actualy query
        $conn = $this->em->getConnection();

        $sql = 'SELECT id FROM account WHERE user_name = :userName';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('userName' => $userName));
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (isset($row['id']))
        {
            // print_r($row); die(count($row));
            $form['userName']->addError(new FormError('Account Name is already being used. Please select another name.'));
        }
    }
}

?>
