<?php
class PhoneMap extends BaseMap
{
    protected $map = array(
        'id'          => 'phone_id',
        'phoneTypeId' => 'phone_type_id',
        'personId'    => 'person_id',
        'areaCode'    => 'area_code',
        'num'         => 'num',
        'ext'         => 'ext',
    );
    protected $mapx = array(
        'phoneTypeDesc' => 'phone_type_desc',
    );
}
class PhoneTable extends BaseTable
{
    protected $tblName = 'phone';
    protected $keyName = 'phone_id';

    protected $mapClassName = 'PhoneMap';    
}
class PhoneItem extends BaseItem
{
    protected $mapClassName = 'PhoneMap';
    
    public function __get($name)
    {
        switch($name) {
            case 'number':
                if (!$this->num) return '';
                
                $number = $this->num;
                
                if ($this->ext) $number .= ' ' . $this->ext;
                
                return "({$this->areaCode}) {$this->num}";
        }
        return parent::__get($name);
    }  
}
class PhoneModel extends BaseModel
{
    protected   $mapClassName = 'PhoneMap';    
    protected  $itemClassName = 'PhoneItem';
    protected $tableClassName = 'PhoneTable';
    
    function search($search)
    {
        $select = new Proj_Db_Select($this->db);
        
        $this->fromAll($select,'phone');
        if ($search->wantx) {
            $this->context->models->PhoneTypeModel->joinPhoneTypeDesc($select,'phone');
        }    
        if ($search->phoneId)  $select->where('phone.phone_id  IN (?)',$search->phoneId);
        if ($search->personId) $select->where('phone.person_id IN (?)',$search->personId);
        
        $select->order('phone.phone_type_id');
        
        $rows  = $this->db->fetchAll($select); //Zend::dump($rows); die();
        $items = array();
        foreach($rows as $row)
        {
            $item = $this->create($row,'phone');
            $items[$item->id] = $item;
        }
        return $items;
    }
    function joinPhonePersonForType($select,$alias,$right,$typeId)
    {        
        $select->joinLeft(
            "phone AS $alias",
            "$alias.person_id = $right.person_id AND $alias.phone_type_id = $typeId",
            $this->table->getAliasedColumnNames($alias)
        );
    }
}

class PhoneValidate
{
    
static function validate($initial, $allowAlpha=0)
{
    $sp='-'; //defines spacer
    
    //global $localAreaCode;
    //global $localCountryCode;

    $initial=trim($initial);
    $buffer = $initial;

    //allows alpha translation, this should be hard coded to be faster
    if($allowAlpha) {
        $keypad = array(
            'A'=>2,'B'=>2,'C'=>2,'D'=>3,'E'=>3,'F'=>3,'G'=>4,'H'=>4,
            'I'=>4,'J'=>5,'K'=>5,'L'=>5,'M'=>6,'N'=>6,'O'=>6,'P'=>7,'Q'=>7,
            'R'=>7,'S'=>7,'T'=>8,'U'=>8,'V'=>8,'W'=>9,'X'=>9,'Y'=>9,'Z'=>9
        );
        for($i=65;$i<=90;$i++){
            $buffer=preg_replace('/'.chr($i).'+/i',$keypad[chr($i)],$buffer);
        }
    }

    $replace=array(':',' ',',',')','(',"'",'"','-','.','/','~','//','+');
    $buffer = str_replace($replace,'',$buffer);

    //isolate the numeric string
    #1 + 7 digits is OK
    #7 digits are OK as long as localAreaCode is set
    #10 digits are OK as long as first three between 200 and 999
    #longer strings are OK as long as first three are 011 or ....

    if(preg_match('/^011[0-9]{6,}/',$buffer,$match)){
        //international number -- i'm requiring at least 6 digits afterward
        $b['error']='International number parsing not developed yet';
        return $b;
    }
    else if(preg_match('/^1?[2-9]{1}[0-9]{2}[2-9][0-9]{6}/',$buffer,$match)){
        $b['status']='US or Canada phone syntax';
        // 1 + 10 digits
        //echo $match[0] . '--';
        $kill=strlen($match[0]);
        $onePlus = preg_replace('/^1/','',$match[0]);

        //basic components of phone number
        $b['raw']=$initial;
        $b['country']=1;
        $b['area']=substr($onePlus,0,3);
        $b['prefix']=substr($onePlus,3,3);
        $b['body']=substr($onePlus,6,4);
        $b['phone']=$b['prefix'].$sp.$b['body'];
        $b['raw_phone']=$b['prefix'].$b['body'];
        $b['remainder']='';
        
        //find remainder of string if present
        $ct = 0;
        $digit = 0;
        for($i=1;$i<=strlen($initial);$i++){
            $alphaAlso=($allowAlpha?"a-z":"");
            $ct++; //initially 1
            $char=substr($initial,$ct-1,1);
            if(preg_match("/[0-9$alphaAlso]/",$char)){
                $digit++;
            }
            if($digit==$kill){
                $mainStringCt=$ct;
                break;
            }
        }
        if($x=trim(substr($initial,$mainStringCt,strlen($initial)-$mainStringCt))){
            $b['remainder']=$x;
            //get the extension from this string
            if(preg_match('/(e)*x((\.)|(t\.)|(tension))*\s*[0-9]+/i',$x,$a)){
                $b['extension']=preg_replace('/(e)*x((\.)|(t\.)|(tension))*\s*/i','',$a[0]);
            }
            if(preg_match('/[0-9]+/',$x,$a)){
                $b['possible_extension']=$a[0];
            }
        }
        return $b;
    }
    else{
        $b['error']='Not a valid phone number';
        return $b;
    }
}
}
?>