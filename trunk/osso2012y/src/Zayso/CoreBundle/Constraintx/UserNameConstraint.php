<?php
namespace Zayso\CoreBundle\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UserNameConstraint extends Constraint
{
    public $messageInUse    = 'User name "%string%" is already in use: choose another name.';
    public $messageNotBlank = 'User name "%string%" cannot be blank.';
    
    public function validatedBy()
    {
        return 'user_name';
    }
}

?>
