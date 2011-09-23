<?php
namespace Zayso\ZaysoBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

//use Symfony\Component\Validator\Mapping\ClassMetadata;
//use Symfony\Component\Validator\Constraints\NotBlank;
//use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints as Assert;

class AccountData
{
    public $memberId = 0;

    /** @Assert\NotBlank() */
    public $accountName;

    /** @Assert\NotBlank() */
    public $accountPass;

    /** @Assert\NotBlank() */
    public $accountPass2;

    /** @Assert\NotBlank() */
    public $accountStatus;

    /** @Assert\Email() */
    public $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^(AYSOV)?\d{8}$/",
     *     message="Must be 8-digit number")
     */
    public $aysoid;

    /**
     * @Assert\True(message = "Passwords do not match")
     */
    public function isPasswordLegal()
    {
        if (!$this->accountPass) return false;
        if ($this->accountPass != $this->accountPass2) return false;
        return true;
    }

}

class AccountManagerTest extends WebTestCase
{
    public function testValidation()
    {
        $kernel = $this->createKernel();
        $kernel->boot();
        $validator = $kernel->getContainer()->get('validator');

        $account = new AccountData();
        $account->accountName   = 'ahundiak';
        $account->accountPass   = 'pass';
        $account->accountPass2  = 'pass';
        $account->accountStatus = 'Acitve';
        $account->email = 'ahunidak@gmail.comxxxx';
        $account->aysoid = 'AYSOV12345678';
      //$account->aysoid = '12345678';
        
        $errors = $validator->validate($account);
        if (count($errors))
        {
            print_r($errors);
        }
        $this->assertEquals(0,count($errors));

        
    }
}


?>
