<?php
namespace Zayso\CoreBundle\Constraint;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormValidatorInterface;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UserNameValidator extends ConstraintValidator
{
    public function validate($userName, Constraint $constraint)
    {   
        $userName = trim($userName);
        
        if (!$userName)
        {
            $this->context->addViolation($constraint->messageNotBlank, array('%string%' => $userName));
            return;
        }
        $userNameOriginal = $this->sc->getToken()->getUser()->getUserName();
        
        // Only validate a change
        if ($userNameOriginal && ($userNameOriginal == $userName)) return;
        
        // Actualy query
        $conn = $this->manager->getConnection();

        $sql = 'SELECT id FROM account WHERE user_name = :userName';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('userName' => $userName));
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (isset($row['id']))
        {
            $this->context->addViolation($constraint->messageInUse, array('%string%' => $userName));
            return;
        }
    }
    public function __construct($manager,$sc)
    {
        $this->manager = $manager;
        $this->sc      = $sc;
    }
    public function validatex(FormInterface $form)
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
