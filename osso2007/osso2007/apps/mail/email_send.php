<?php
require_once './config.php';
require_once 'Zend/Mail.php';

ini_set('max_execution_time','300');

class EmailList
{
	function send()
	{
		$emails = file($this->to);
		if (!$emails) die("Could not open {$this->to}\n");
		
		foreach($emails as $email)
		{
			$email = trim($email);
			if ($email) {
				
				$mail = new Zend_Mail();
				$mail->setBodyText($this->body);
				$mail->setFrom($this->from,'Art Hundiak');
				$mail->addTo($email,$email);
				$mail->setSubject($this->subj);
				$mail->send();
				echo "Sent to $email\n";
			}
		}
	}
	function load($fileName)
	{
		$file = file($fileName);
		if (!$file) die("Could not open $fileName\n");
		
		$this->from = str_replace('From: ','',trim($file[0]));
		$this->to   = str_replace('To:   ','',trim($file[1]));
		$this->subj = str_replace('Subj: ','',trim($file[2]));

		$cnt = count($file);
		$body = '';
		for($i = 4; $i < $cnt; $i++) {
			$body .= $file[$i];
		}
		$this->body = $body;
	}
}
$list = new EmailList();
$list->load('msgs/Spring2007a.txt');
$list->send();
?>
