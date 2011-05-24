<?php
namespace AYSO\Player;

use Cerad\Debug;

class PlayerImport extends \Cerad\Import
{
  protected $readerClassName = 'AYSO\Player\PlayerImportReader';

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
    $id = $data['id'];
    if (!$id) return;

    $region = $data['region'];
    if (!$region) return;

    // dob
    $dob = $data['dob'];
    $temp = explode('/',$dob);
    if (count($temp) == 3)
    {
      $dob = sprintf('%04d%02d%02d',$temp[2],$temp[0],$temp[1]);
    }
    // Process it
    $em = $this->services->em;
    $this->count->total++;

    // Existing player
    $existing = true;
    $player = $em->find('AYSO\Player\PlayerItem',$id);
    if (!$player)
    {
      $player = new \AYSO\Player\PlayerItem();
      $player->setId($id);
      $this->count->inserted++;
      $existing = false;
    }
    $player->setRegionId  ($region);
    $player->setFirstName ($data['fname']);
    $player->setLastName  ($data['lname']);
    $player->setNickName  ($data['nname']);
    $player->setMiddleName($data['mname']);
    $player->setSuffix    ($data['suffix']);
    $player->setPhoneHome ($data['phone_home']);
    $player->setEmail     ($data['email']);
    $player->setDOB       ($dob);
    $player->setGender    ($data['gender']);

    // Fine for existing elements
    $em->persist($player);

    // TODO Be kind of nice to see if element is actually being updated
    $changes = $em->getUnitOfWork()->getEntityChangeSet($player);
    if (count($changes)) $this->count->updated++;
    
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
