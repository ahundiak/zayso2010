<?php
class PersonMap extends BaseMap
{
    protected $map = array(
        'id'     => 'person_id',
        'uname'  => 'uname',
        'upass'  => 'upass',
        'fname'  => 'fname',
        'lname'  => 'lname',
        'mname'  => 'mname',
        'nname'  => 'nname',
        'unitId' => 'unit_id',
        'status' => 'status',
	'aysoid' => 'aysoid',
    );
    protected $mapx = array(
        'unitDesc' => 'unit_desc',
    );
}
class PersonTable extends BaseTable
{
    protected $tblName = 'person';
    protected $keyName = 'person_id';

    protected $mapClassName = 'PersonMap';
}
class PersonItem extends BaseItem
{
    protected $mapClassName = 'PersonMap';
    
    protected $vols   = array();
    protected $emails = array();
    protected $phones = array();
    
    public function addVol($vol)
    {
        $this->vols[$vol->id] = $vol;
    }
    public function addEmail($email)
    {
        $this->emails[$email->emailTypeId] = $email;
    }
    public function addPhone($phone)
    {
        $this->phones[$phone->phoneTypeId] = $phone;
    }
    public function __get($name)
    {
        switch($name) 
        {
            case 'firstName': return $this->fname;
            case 'lastName' : return $this->lname;
            
            case 'fullName' : return $this->getName();
            case 'fullNamex': return $this->getName(TRUE);
            case 'name'     : return $this->getName();
            case 'namex'    : return $this->getName(TRUE);
            
            case 'vols':   return $this->vols;
            case 'emails': return $this->emails;
            case 'phones': return $this->phones;
            
            case 'emailHome':
                if (isset($this->emails[EmailTypeModel::TYPE_HOME])) return $this->emails[EmailTypeModel::TYPE_HOME];
                return NULL;
                
            case 'emailWork':
                if (isset($this->emails[EmailTypeModel::TYPE_WORK])) return $this->emails[EmailTypeModel::TYPE_WORK];
                return NULL;
                
            case 'phoneHome':
                if (!isset($this->phones[PhoneTypeModel::TYPE_HOME])) {
                    $phone = new PhoneItem();
                    $phone->phoneTypeId = PhoneTypeModel::TYPE_HOME;
                    $this->phones[PhoneTypeModel::TYPE_HOME] = $phone;
                }
                return $this->phones[PhoneTypeModel::TYPE_HOME];
                
            case 'phoneWork':
                if (!isset($this->phones[PhoneTypeModel::TYPE_WORK])) {
                    $phone = new PhoneItem();
                    $phone->phoneTypeId = PhoneTypeModel::TYPE_WORK;
                    $this->phones[PhoneTypeModel::TYPE_WORK] = $phone;
                }
                return $this->phones[PhoneTypeModel::TYPE_WORK];
                
            case 'phoneCell':
                if (!isset($this->phones[PhoneTypeModel::TYPE_CELL])) {
                    $phone = new PhoneItem();
                    $phone->phoneTypeId = PhoneTypeModel::TYPE_CELL;
                    $this->phones[PhoneTypeModel::TYPE_CELL] = $phone;
                }
                return $this->phones[PhoneTypeModel::TYPE_CELL];
        }
        return parent::__get($name);
    }
    protected function getName($comma = FALSE)
    {
        $fname = $this->fname;
        $lname = $this->lname;
        if (!$fname && !$lname) return NULL;
        if (!$fname) return $lname;
        if (!$lname) return $fname;
        
        if ($comma) return $lname . ', ' . $fname;
        
        return $fname . ' ' . $lname;
    }
}
class PersonModel extends BaseModel
{
    protected   $mapClassName = 'PersonMap';
    protected  $itemClassName = 'PersonItem';
    protected $tableClassName = 'PersonTable';
    
    public function search($search)
    {
        $wantPhones = FALSE;
        $wantEmails = FALSE;
        
        $models = $this->context->models;
        $select = new Proj_Db_Select($this->db);
        
        $this->fromAll($select,'person');
        
        if ($search->wantx) {
            $models->UnitModel->joinUnitDesc($select,'person');
        }
        if ($search->wantPhones) {
            $wantPhones = TRUE;
            $phoneModel = $models->PhoneModel;
            $phoneModel->joinPhonePersonForType($select,'personphone_home','person',PhoneTypeModel::TYPE_HOME);
            $phoneModel->joinPhonePersonForType($select,'personphone_work','person',PhoneTypeModel::TYPE_WORK);
            $phoneModel->joinPhonePersonForType($select,'personphone_cell','person',PhoneTypeModel::TYPE_CELL);
        }
        if ($search->wantEmails) {
            $wantEmails = TRUE;
            $emailModel = $models->EmailModel;
            $emailModel->joinEmailPersonForType($select,'personemail_home','person',EmailTypeModel::TYPE_HOME);
            $emailModel->joinEmailPersonForType($select,'personemail_work','person',EmailTypeModel::TYPE_WORK);
        }
        if ($search->personId) $select->where('person.person_id IN (?)',$search->personId);
        if ($search->unitId)   $select->where('person.unit_id   IN (?)',$search->unitId);
        if ($search->aysoid)   $select->where('person.aysoid    =   ?', $search->aysoid);
        if ($search->lname) {
            if ($search->exact) $select->where('person.lname   =     ?', $search->lname);
            else                $select->where('person.lname   LIKE  ?', $search->lname . '%');
        }
        if ($search->fname) {
            if ($search->exact) $select->where('person.fname   =     ?', $search->fname);
            else                $select->where('person.fname   LIKE  ?', $search->fname . '%');
        }
        $select->order('person.lname,person.fname,person.person_id');
        $rows = $this->db->fetchAll($select);
        $items = array();
        foreach($rows as $row)
        {
            $item = $this->create($row,'person');
            if ($wantPhones) {
                $item->addPhone($phoneModel->create($row,'personphone_home'));
                $item->addPhone($phoneModel->create($row,'personphone_work'));
                $item->addPhone($phoneModel->create($row,'personphone_cell'));   
            }
            if ($wantEmails) {
                $item->addEmail($emailModel->create($row,'personemail_home'));
                $item->addEmail($emailModel->create($row,'personemail_work'));
            }
            $items[$item->id] = $item;
        }
        return $items;
    }
    function joinPersonName($select,$left,$right,$rightKey='person_id')
    {   
        $select->joinLeft(
            "person AS {$left}",
            "{$left}.person_id = {$right}.{$rightKey}",
            array(
                "{$left}.person_id AS {$left}_person_id",
                "{$left}.fname     AS {$left}_fname",
                "{$left}.lname     AS {$left}_lname",
            )
        );
    }
    function joinPersonDesc($select,$right,$rightKey='person_id')
    {
        $left = $right . '_person';
        
        $select->joinLeft(
            "person AS {$left}",
            "{$left}.person_id = {$right}.{$rightKey}",
            array(
                "{$left}.fname   AS {$left}_fname",
                "{$left}.lname   AS {$left}_lname",
                "{$left}.unit_id AS {$left}_unit_id",
                "{$left}.aysoid  AS {$left}_aysoid",
            )
        );
    }
}
?>
