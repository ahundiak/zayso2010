<?php

class Osso2007_Div_DivRepo
{
  protected $context;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  protected $divisionPickList = array
  (
     '1' => 'U06B', '2' => 'U06G', '3' => 'U06C',
     '4' => 'U08B', '5' => 'U08G', '6' => 'U08C',
     '7' => 'U10B', '8' => 'U10G', '9' => 'U10C',
    '10' => 'U12B','11' => 'U12G','12' => 'U12C',
    '13' => 'U14B','14' => 'U14G','15' => 'U14C',
    '16' => 'U16B','17' => 'U16G','18' => 'U16C',
    '19' => 'U19B','20' => 'U19G','21' => 'U19C',
    '22' => 'U05B','23' => 'U05G','24' => 'U05C',
  );
  function getDivisionPickList() { return $this->divisionPickList; }
    
  function getDivisionDesc($divisionId)
  {
    if (isset($this->divisionPickList[$divisionId])) return $this->divisionPickList[$divisionId];
    return NULL;
  }
  protected $agePickList = array
  (
     '5' => 'U05',
     '6' => 'U06',
//   '7' => 'U07',
     '8' => 'U08',
//   '9' => 'U09',
    '10' => 'U10',
//  '11' => 'U11',
    '12' => 'U12',
//  '13' => 'U13',
    '14' => 'U14',
//  '15' => 'U15',
    '16' => 'U16',
//  '17' => 'U17',
//  '18' => 'U18',
    '19' => 'U19',
  );
  public function getAgePickList() { return $this->agePickList; }
    
  protected  $ageDiv = array
  (
     '5' => '22',  // U05
     '6' =>  '1',  // U06
     '7' =>  '4',  // U08
     '8' =>  '4',
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
        
    if ($age1 > $age2)
    {
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
  public function getDivisionIdForKey($key)
  {
    return array_search($key,$this->divisionPickList);
  }
  public function getIdForKey($key)
  {
    return array_search($key,$this->divisionPickList);
  }
}
?>
