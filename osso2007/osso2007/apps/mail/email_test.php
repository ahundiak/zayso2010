<?php
error_reporting(E_ALL);
require_once './config.php';
require_once 'Zend/Mail.php';

$mail = new Zend_Mail();
$mail->setBodyText('This is the text of the mail.');

//$mail->setFrom('ahundiak@ayso894.org', 'Some Sender');
//$mail->addTo('ahundiak@ingr.com', 'Some Recipient');

$mail->setFrom('ahundiak@ayso894.org', 'Art Hundiak');
$mail->addTo('ahundiak@ingr.com', 'Art Hundiak');

$mail->setSubject('TestSubject');
$mail->send();

$to      = 'ahundiak@ingr.com';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: ahundiak@ayso894.org' . "\r\n" .
   'Reply-To: ahundiak@ayso894.org' . "\r\n" .
   'X-Mailer: PHP/' . phpversion();

//mail($to, $subject, $message, $headers);

?>
