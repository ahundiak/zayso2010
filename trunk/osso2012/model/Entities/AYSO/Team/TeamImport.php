<?php
namespace AYSO\Team;

use Cerad\Debug;

class TeamImport extends \Cerad\Import
{
  protected $readerClassName = 'AYSO\Team\TeamImportReader';

  protected $batchCount =   0;
  protected $batchSize  = 100;

  protected function processEnd()
  {
    if ($this->batchCount)
    {
      $em = $this->services->em;
      $em->flush();
      $em->clear();
    }
  }
  public function processRowData($data)
  {
    // Data checks
    $id = $data['id']; if (!$id) return;

    $regionId = $data['region_id']; if (!$regionId) return;

    // Figure out the gender fromn desig
    $gender = 'U';
    $pos = 100;
    $desig = strtoupper($data['desig']);
    $genders = array('B','G','C');
    foreach($genders as $gen)
    {
      $posx = strstr($desig,$gen);
      if (($posx !== false) && ($posx < $pos))
      {
        $gender = $gen;
        $pos = $posx;
      }
    }
    // Process it
    $em = $this->services->em;
    $this->count->total++;

    // Existing team
    $team = $em->find('AYSO\Team\TeamItem',$id);
    if (!$team)
    {
      $team = new \AYSO\Team\TeamItem();
      $team->setId     ($id);
      $team->setMemYear(2010);
      $team->setProgram('Fall');
      $this->count->inserted++;
    }
    $team->setRegionId($regionId);
    $team->setDesig   ($data['desig']);
    $team->setDivision($data['division']);
    $team->setGender  ($gender);
    $team->setName    ($data['name']);
    $team->setColors  ($data['colors']);

    // Fine for existing elements
    $em->persist($team);
    
    // Post batch if necessary
    $this->batchCount++;

    if (($this->batchCount % $this->batchSize) == 0)
    {
      $em->flush();
      $em->clear(); // Detaches all objects from Doctrine!
      $this->batchCount = 0;
    }
  }
}
?>
