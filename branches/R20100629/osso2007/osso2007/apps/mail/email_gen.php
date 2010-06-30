<?php
require_once './config.php';
require_once 'Spreadsheet/Excel/Reader.php';
require_once 'Spreadsheet/Excel/Reader/Util.php';

// Generates the master list of emails
function isValidInetAddress($data, $strict = false) 
{ 
  $regex = $strict? 
      '/^([.0-9a-z_-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i' : 
       '/^([*+!.&#$¦\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i' 
  ;
  $matches = NULL; 
  if (preg_match($regex, trim($data), $matches)) { 
    return array($matches[1], $matches[2]); 
  } else { 
    return false; 
  } 
}
class doit
{
	static function AddPlayerEmails($emails,$sheetName)
	{
		$cells = ExcelReaderUtil::getSheetCells('data/PlayerDataEmails2007.xls',$sheetName);

		/* Map the indexes indexes */
		$indexNameLast = 0;
		$indexEmail1   = 0;
		$indexEmail2   = 0;
		$indexEmail3   = 0;
		foreach($cells[1] as $key => $index) {
			switch($index) {
      			case 'Player Last Name'        : $indexNameLast = $key; break;
      			case 'Player Email'            : $indexEmail1   = $key; break;
      			case 'Primary Parent e-mail'   : $indexEmail2   = $key; break;
      			case 'Secondary Parent e-mail' : $indexEmail3   = $key; break;
			}
		}
		//echo "$indexNameLast $indexEmail1\n";

		foreach($cells as $key => $data)
		{
			$email1 = trim(strtolower(ExcelReaderUtil::getCellValue($data,$indexEmail1)));
			$email2 = trim(strtolower(ExcelReaderUtil::getCellValue($data,$indexEmail2)));
			$email3 = trim(strtolower(ExcelReaderUtil::getCellValue($data,$indexEmail3)));
	
			if ($key != 1) {
				if (($email1) && (!isset($emails[$email1]))) $emails[$email1] = $email1;
				if (($email2) && (!isset($emails[$email2]))) $emails[$email2] = $email2;
				if (($email3) && (!isset($emails[$email3]))) $emails[$email3] = $email3;
			}
		}
		return $emails;
	}
	static function AddListEmails($emails,$sheetName)
	{
		$indexes = array(1);
		
		$cells = ExcelReaderUtil::getSheetCells('data/ListEmails2007.xls',$sheetName);
		
		$first = TRUE;
		foreach($cells as $key => $data)
		{
			if ($first) $first = FALSE;
			else {
				foreach($indexes as $index) {
					
					$email = trim(strtolower(ExcelReaderUtil::getCellValue($data,$index)));
					$email = str_replace(' at ','@',$email);
					if (($email) && (!isset($emails[$email]))) $emails[$email] = $email;
				}
			}
		}
		return $emails;
	}
	static function AddVolEmails($emails,$sheetName)
	{
		$cells = ExcelReaderUtil::getSheetCells('data/Volunteers2006.xls',$sheetName);

		/* Map the indexes indexes */
		$indexNameLast = 0;
		$indexEmail1   = 0;
		$indexEmail2   = 0;
		$indexEmail3   = 0;
		foreach($cells[1] as $key => $index) {
			switch($index) {
      			case 'Email': $indexEmail1   = $key; break;
			}
		}
		// echo "$indexNameLast $indexEmail1\n";

		foreach($cells as $key => $data)
		{
			$email1 = trim(strtolower(ExcelReaderUtil::getCellValue($data,$indexEmail1)));
			$email2 = NULL;
			$email3 = NULL;
			
			if ($key != 1) {
				if (($email1) && (!isset($emails[$email1]))) $emails[$email1] = $email1;
				if (($email2) && (!isset($emails[$email2]))) $emails[$email2] = $email2;
				if (($email3) && (!isset($emails[$email3]))) $emails[$email3] = $email3;
			}
		}
		return $emails;
	}
}

$emails = array();
//$emails = doit::AddPlayerEmails($emails,'PlayerData20070131');


//$emails = doit::AddListEmails($emails,'refs');
//$emails = doit::AddListEmails($emails,'coaches');
$emails = doit::AddListEmails($emails,'public');

//$emails = doit::AddVolEmails($emails,'YouthPreReg');
//$emails = doit::AddVolEmails($emails,'AdultPreReg');
//$emails = doit::AddVolEmails($emails,'DavidsList');

/* Store in file */
asort(&$emails);

//$emailCnt = count($emails);


$fp = fopen('data/AllEmails2007.txt','wt');
$emailCnt = 0;
foreach($emails as $email)
{
	if (isValidInetAddress($email)) {
		fprintf($fp,"%s\n",$email);
        $emailCnt++;
    }
	else {
		echo "Rejected $email\n";
	}
}
fclose($fp);
echo "Final count $emailCnt\n";
?>
