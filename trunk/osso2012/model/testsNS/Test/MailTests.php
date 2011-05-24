<?php

namespace Test;

class MailTests extends \PHPUnit_Framework_TestCase
{
  public static function setUpBeforeClass()
  {
    // echo "Setup before class\n";
  }
  public function setUp()
  {
    // echo "Setup\n";
  }
  function test1()
  {
    $to      = 'ahundiak@gmail.com';
    $subject = 'the subject';
    $message = 'hello';
    $headers = 'From: contact@zayso.org' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);

  $this->assertTrue(true);
  }
  function test2()
  {
    $this->assertTrue(true);
  }
}
?>
