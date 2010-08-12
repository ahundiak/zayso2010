<?php
class Osso2007_Person_PersonSync
{
  protected $context;
  protected $updated = 0;
  protected $total   = 0;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init()
  {
    $this->directPersonReg  = new Osso_Person_Reg_PersonRegDirect($this->context);
    $this->directPerson2007 = new Osso2007_Person_PersonDirect   ($this->context);

  }
  public function getResultMessage()
  {
    $class = get_class($this);

    $msg = sprintf("%s Total: %u, Updated: %u", $class, $this->total, $this->updated);
    return $msg;
  }
  public function process()
  {
    // Get all the people with known good aysoids, really should be a method
    $result = $this->directPersonReg->fetchRows(array('reg_type' => 102));

    foreach($result->rows as $row)
    {
      $personId = $row['person_id'];
      $aysoid   = $row['reg_num'];
      if (!$personId) die('Missing person id for ' . $aysoid);
      if ($personId < 3000)
      {
        $this->total++;
        $result = $this->directPerson2007->fetchRow(array('person_id' => $personId));
        $person = $result->row;
        if (!$person) die('No person 20007 record for ' . $personId);

        if ($person['aysoid'] != $aysoid)
        {
          $this->directPerson2007->update(array('person_id' => $personId, 'aysoid' => $aysoid));
          $this->updated++;
        }
      }
    }
  }
}

?>
