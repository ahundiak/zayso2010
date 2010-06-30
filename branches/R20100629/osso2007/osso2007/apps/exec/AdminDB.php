<?php
require_once 'PHPUnit2/Framework/TestCase.php';

class AdminDB_Tests extends PHPUnit2_Framework_TestCase
{
	protected $registry;
	
    public function setUp()
    {
    	$this->registry = Zend::registry('context');
	}
    public function tearDown()
    {
    }
    
    public function sestCreateTables()
    {
    	
		require_once 'admin/AccountAdmin.php';
		require_once 'admin/MemberAdmin.php';
		require_once 'admin/PersonAdmin.php';
		require_once 'admin/MasterAdmin.php';
		
    	$db = $this->registry->db;
    	
		AccountAdmin::createTable($db);
		MemberAdmin::createTable($db);

		$personAdmin = new PersonAdmin();
		$personAdmin->createTable($db);
		
		MasterAdmin::createAllTables($db);
		
    	echo "Account and Member Tables created\n";
    }
    public function sestAddAdminAccount()
    {
		require_once 'models/AccountModel.php';
		require_once 'models/MemberModel.php';
		
    	$db = $this->registry->db;
    	
    	$accountTable = new AccountTable($db);
     	$memberTable  = new MemberTable($db);
    	
    	$account = $accountTable->fetchNewRow($db);
    	$account->accountUser = 'ahundiak';
    	$account->accountName = 'Hundiak';
    	$account->accountPass = md5('qwepoi');
    	$account->email       = 'ahundiak@ayso894.org';
    	$account->status      = 1;
    	$account->save();
    	
    	
    	$member = $memberTable->fetchNewRow($db);
    	$member->accountId  = $account->accountId;
		$member->unitId     = 1;
		$member->memberName = 'Art';
		$member->memberPass = NULL;
		$member->personId   = 1;
        $member->level      = 1;
        $member->status     = 1;
    	$member->save();
    	
    	$member = $memberTable->fetchNewRow($db);
    	$member->accountId  = $account->accountId;
		$member->unitId     = 1;
		$member->memberName = 'Ethan';
		$member->memberPass = NULL;
		$member->personId   = 3;
        $member->level      = 2;
        $member->status     = 1;
    	$member->save();
    	
    	$member = $memberTable->fetchNewRow($db);
    	$member->accountId  = $account->accountId;
		$member->unitId     = 1;
		$member->memberName = 'Rebecca';
		$member->memberPass = NULL;
		$member->personId   = 2;
        $member->level      = 2;
        $member->status     = 1;
    	$member->save();
    	
    	$member = $memberTable->fetchNewRow($db);
    	$member->accountId  = $account->accountId;
		$member->unitId     = 1;
		$member->memberName = 'Cassie';
		$member->memberPass = NULL;
		$member->personId   = 0;
        $member->level      = 2;
        $member->status     = 1;
    	$member->save();
    	
    	echo "Account and Member Entries added\n";
    	
    }
    public function sestExport()
    {
    	require_once 'export/ExcelBookWriter.php';
		require_once 'export/ExcelSheet.php';
		
    	require_once 'admin/PersonAdmin.php';
    	require_once 'admin/MasterAdmin.php';
    	
    	$db = $this->registry->db;
    	
    	$book = new ExcelBookWriter('ExportedData.xls');
    	
    	//$personAdmin = new PersonAdmin();
    	//$personAdmin->exportExcel($db,$book);
    	//MasterAdmin::exportExcel($db,$book,'person');
    	//MasterAdmin::exportExcel($db,$book,'phone');
    	
    	MasterAdmin::exportExcelTables($db,$book,
    		array('person','phone','phone_type','email','email_type'));
    	
    	$book->close();
    	
    }
    public function sestImport()
    {
		require_once 'Spreadsheet/Excel/Reader.php';
		require_once 'Spreadsheet/Excel/Reader/Util.php';
		
		require_once 'admin/MasterAdmin.php';
		
    	$db = $this->registry->db;
    	
    	$book = ExcelReaderUtil::loadFile('ExportedData.xls');
    	
//    	MasterAdmin::importExcelTable ($db,$book,'email_type');
    	MasterAdmin::importExcelTables($db,$book);
    }
    public function sestExportReferees()
    {
    	require_once 'export/ExcelBookWriter.php';
		require_once 'export/ExcelSheet.php';
		
    	$db = $this->registry->db;
    	$select = $db->select();
    	
    	$select->distinct(TRUE);
    	$select->from('event_person','person_id');
    	$select->joinLeft('event','event.event_id = event_person.event_id');
    	$select->where('event_person.event_person_type_id IN (10,11,12)');
    	$select->where('event.unit_id        = 1');
    	$select->where('event.reg_year_id    = 5');
    	$select->where('event.season_type_id = 1');
    			
    	$personIds = $db->fetchCol($select);
    	
    	$select = $db->select();
    	$select->from('person',array('person_id','lname','fname'));
    	$select->where('person_id IN (?)',$personIds);
    	$select->order('lname,fname');
    	
    	$items = $db->query($select);
    	
    	$book = new ExcelBookWriter('RefereesFall2005.xls');
    	$sheet = $book->addWorksheet('RefereesFall2005');
		
		$row = 0;
		$sheet->writeString($row,0,'id');
		$sheet->writeString($row,1,'Last Name');
		$sheet->writeString($row,2,'First Name');
    	$row++;
    	foreach($items as $item) {
			$sheet->writeNumber($row,0,$item['person_id']);
			$sheet->writeString($row,1,$item['lname']);
			$sheet->writeString($row,2,$item['fname']);
    		$row++;
    	}
    	$book->close();
    }
    public function testImportReferees()
    {
		require_once 'Spreadsheet/Excel/Reader.php';
		require_once 'Spreadsheet/Excel/Reader/Util.php';
		require_once 'models/VolModel.php';
		
    	$db = $this->registry->db;
    	$volTable = new VolTable($db);
    	
    	$book  = ExcelReaderUtil::loadFile('RefereesFall2005.xls');
    	$items = ExcelReaderUtil::getSheetCells($book,'RefereesFall2005');
    	$count = count($items);
    	for($i = 2; $i< $count; $i++) {
    	
    		$item = $items[$i];
    		
    		/* Build the record */
    		$vol = $volTable->fetchNew();
    		$vol->personId = $item[1];
    		if (!isset($item[4])) $vol->volTypeId = VOL_TYPE_ADULT_REF;
    		else                  $vol->volTypeId = VOL_TYPE_YOUTH_REF;
    		$vol->unitId       = 1;
    		$vol->regYearId    = 6;
    		$vol->seasonTypeId = 1;
    		
    		/* See if already have one */
    		$select = $db->select();
    		$select->from ('vol','vol_id');
    		$select->where('person_id      = ?',$vol->personId);
    		$select->where('vol_type_id    = ?',$vol->volTypeId);
    		$select->where('reg_year_id    = ?',6);
    		$select->where('season_type_id = ?',1);
    		
    		$itemx = $db->fetchCol($select);
    		//Zend::dump($itemx);
    		//Zend::dump($vol);
    		if (count($itemx) < 1) {
    			$vol->save();
    		}
    		//die();
    	}
	
    	
    }
}
