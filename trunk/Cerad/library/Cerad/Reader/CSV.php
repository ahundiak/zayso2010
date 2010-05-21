<?php
class Cerad_Reader_CSV
{
  protected $map  = array('FirstName' => 'fname','LastName' => 'lname');
  protected $mapx = array();

  protected function processRowHeader($row)
  {
    foreach($this->map as $csvName => $sqlName)
    {
      $csvIndex = array_search($csvName,$row);
      if ($csvIndex === FALSE)
      {
        echo "*** Unable to find {$csvName} in header row.\n";
      }
      else
      {
        $this->mapx[$csvIndex] = $sqlIndex;
      }
    }
  }
}

?>
