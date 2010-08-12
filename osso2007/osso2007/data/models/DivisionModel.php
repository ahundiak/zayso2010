<?php
class DivisionMap extends BaseMap
{
    protected $map = array(
        'id'       => 'division_id',
        'sort'     => 'sortx',
        'descPick' => 'desc_pick',
        'descLong' => 'desc_long',
    );
}
class DivisionTable extends BaseTable
{
    protected $tblName = 'division';
    protected $keyName = 'division_id';
    
    protected $mapClassName = 'DivisionMap';
}
class DivisionItem extends BaseItem
{
    protected $mapClassName = 'DivisionMap';
}
class DivisionModel extends BaseModel
{
    protected   $mapClassName = 'DivisionMap';
    protected  $itemClassName = 'DivisionItem';
    protected $tableClassName = 'DivisionTable';
        
    protected $divisionPickList = array(
			 '1' => 'U06B', '2' => 'U06G', '3' => 'U06C',
			 '4' => 'U08B', '5' => 'U08G', '6' => 'U08C',
			 '7' => 'U10B', '8' => 'U10G', '9' => 'U10C',
			'10' => 'U12B','11' => 'U12G','12' => 'U12C',
			'13' => 'U14B','14' => 'U14G','15' => 'U14C',
			'16' => 'U16B','17' => 'U16G','18' => 'U16C',
			'19' => 'U19B','20' => 'U19G','21' => 'U19C',
            '22' => 'U05B','23' => 'U05G','24' => 'U05C',
            '25' => 'U07B','26' => 'U07G','27' => 'U07C',
    );
    function getDivisionPickList() { return $this->divisionPickList; }
    
    public function getId($desc) {
    	return array_search($desc,$this->divisionPickList);
    }
    
    function getDivisionDesc($divisionId)
    {
        if (isset($this->divisionPickList[$divisionId])) return $this->divisionPickList[$divisionId];
        return NULL;
    }
    protected $agePickList = array(
             '5' => 'U05',
             '6' => 'U06',
             '7' => 'U07',
             '8' => 'U08',
//           '9' => 'U09',
            '10' => 'U10',
//          '11' => 'U11',
            '12' => 'U12',
//          '13' => 'U13',
            '14' => 'U14',
//          '15' => 'U15',
            '16' => 'U16',
//          '17' => 'U17',
//          '18' => 'U18',
            '19' => 'U19',
    );
    public function getAgePickList() { return $this->agePickList; }
    
    protected  $ageDiv = array(
             '5' => '22',  // U05
             '6' =>  '1',  // U06
             '7' => '25',  // U07
             '8' =>  '4',  // U08
             '9' =>  '7',  // U10
            '10' =>  '7',
            '11' => '10',  // U12
            '12' => '10', 
            '13' => '13',  // U14
            '14' => '13',
            '15' => '16',  // U16
            '16' => '16',
            '17' => '19',  // U19
            '18' => '19',
            '19' => '19',
    );
    public function getDivisionIdsForAgeGroup($age,$boy,$girl,$coed)
    {
        $divs = array();
        
        if (!isset($this->ageDiv[$age])) return $divs;
        
        $div = $this->ageDiv[$age];
        
        if ($boy)  $divs['key' . $div . 'B'] = $div + 0;
        if ($girl) $divs['key' . $div . 'G'] = $div + 1;
        if ($coed) $divs['key' . $div . 'C'] = $div + 2;
        
        return $divs;
    }
    public function getDivisionIdsForAgeRange($age1,$age2,$boy,$girl,$coed)
    {
        $divs = array();
        
        if ($age1 > $age2) {
            $tmp  = $age1;
            $age1 = $age2;
            $age2 = $tmp;
        }
        for($age = $age1; $age <= $age2; $age++)
        {
           $divs = array_merge($divs,$this->getDivisionIdsForAgeGroup($age,$boy,$girl,$coed));
        } 
        return $divs;
    }
    public function getDivisionIdsForU10AndUp()
    {
        return array(7,8,9,10,11,12,13,14,15,16,17,18,19,20,21);
    }
    public function getDivisionIdForKey($key)
    {
        return array_search($key,$this->divisionPickList);
    }
    function joinDivisionDesc($select,$right,$rightKey='division_id')
    {
        $left = $right . '_division';
        
        $select->joinLeft(
            "division AS {$left}",
            "{$left}.division_id = {$right}.{$rightKey}",
            array(
                "{$left}.desc_pick AS {$left}_desc",
            )
        );
    }
}
?>
