<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Osso2007_Div_DivDirect extends Osso_Base_BaseDirect
{
  protected $tblName = 'osso2007.division';
  protected $idName  = 'division_id';

  protected $ignoreDupKey = true;

  protected $divs = array
  (
    'U5'   => array('id' => 24, 'age' =>  5, 'sex' => 'C'),

    'U5C'  => array('id' => 24, 'age' =>  5, 'sex' => 'C'),
    'U5B'  => array('id' => 22, 'age' =>  5, 'sex' => 'B'),
    'U5G'  => array('id' => 23, 'age' =>  5, 'sex' => 'G'),

    'U05C' => array('id' => 24, 'age' =>  5, 'sex' => 'C'),
    'U05B' => array('id' => 22, 'age' =>  5, 'sex' => 'B'),
    'U05G' => array('id' => 23, 'age' =>  5, 'sex' => 'G'),

    'U6C'  => array('id' =>  3, 'age' =>  6, 'sex' => 'C'),
    'U6B'  => array('id' =>  1, 'age' =>  6, 'sex' => 'B'),
    'U6G'  => array('id' =>  2, 'age' =>  6, 'sex' => 'G'),

    'U06C' => array('id' =>  3, 'age' =>  6, 'sex' => 'C'),
    'U06B' => array('id' =>  1, 'age' =>  6, 'sex' => 'B'),
    'U06G' => array('id' =>  2, 'age' =>  6, 'sex' => 'G'),

    'U7C'  => array('id' => 27, 'age' =>  7, 'sex' => 'C'),
    'U7B'  => array('id' => 25, 'age' =>  7, 'sex' => 'B'),
    'U7G'  => array('id' => 26, 'age' =>  7, 'sex' => 'G'),

    'U07C' => array('id' => 27, 'age' =>  7, 'sex' => 'C'),
    'U07B' => array('id' => 25, 'age' =>  7, 'sex' => 'B'),
    'U07G' => array('id' => 26, 'age' =>  7, 'sex' => 'G'),

    'U8C'  => array('id' =>  6, 'age' =>  8, 'sex' => 'C'),
    'U8B'  => array('id' =>  4, 'age' =>  8, 'sex' => 'B'),
    'U8G'  => array('id' =>  5, 'age' =>  8, 'sex' => 'G'),

    'U08C' => array('id' =>  6, 'age' =>  8, 'sex' => 'C'),
    'U08B' => array('id' =>  4, 'age' =>  8, 'sex' => 'B'),
    'U08G' => array('id' =>  5, 'age' =>  8, 'sex' => 'G'),

    'U10C' => array('id' =>  7, 'age' => 10, 'sex' => 'B'),
    'U10B' => array('id' =>  7, 'age' => 10, 'sex' => 'B'),
    'U10G' => array('id' =>  8, 'age' => 10, 'sex' => 'G'),

    'U12C' => array('id' => 10, 'age' => 12, 'sex' => 'B'),
    'U12B' => array('id' => 10, 'age' => 12, 'sex' => 'B'),
    'U12G' => array('id' => 11, 'age' => 12, 'sex' => 'G'),

    'U14C' => array('id' => 13, 'age' => 14, 'sex' => 'B'),
    'U14B' => array('id' => 13, 'age' => 14, 'sex' => 'B'),
    'U14G' => array('id' => 14, 'age' => 14, 'sex' => 'G'),

    'U16C' => array('id' => 16, 'age' => 16, 'sex' => 'B'),
    'U16B' => array('id' => 16, 'age' => 16, 'sex' => 'B'),
    'U16G' => array('id' => 17, 'age' => 16, 'sex' => 'G'),

    'U19C' => array('id' => 19, 'age' => 19, 'sex' => 'B'),
    'U19B' => array('id' => 19, 'age' => 19, 'sex' => 'B'),
    'U19G' => array('id' => 20, 'age' => 19, 'sex' => 'G'),
  );
  public function getForKey($key)
  {
    $result = $this->newResult();

    //if ($key == 'U6G0') $key = 'U6G';
    //if ($key == 'U6C0') $key = 'U6C';

    //if ($key == 'U8G0') $key = 'U8G';
    //if ($key == 'U8C0') $key = 'U8C';

    if (isset($this->divs[$key]))
    {
      $result->row = $this->divs[$key];
      return $result;
    }
    return $result;
  }
}
?>
