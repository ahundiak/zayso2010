<?php
class Osso2007_Project_ProjectImport extends Cerad_Import
{
  protected $readerClassName = 'Osso2007_Project_ProjectImportReader';

  protected function init()
  {
    parent::init();

    $this->directOrg = new Osso2007_Org_OrgDirect($this->context);

    $this->directProject = new Osso2007_Project_ProjectDirect($this->context);

    $this->directProjectOrg = new Osso2007_Project_Org_ProjectOrgDirect($this->context);

  }
  public function getResultMessage()
  {
    $file = basename($this->innFileName);
    $count = $this->count;
    $class = get_class($this);

    $msg = sprintf("%s %s, Total: %u, Inserted: %u, Updated: %u",
      $class, $file,
      $count->total,$count->inserted,$count->updated);
    return $msg;
  }
  public function processRowData($data)
  {   
    // Validation
    if (!$data['id']) return;
    $this->count->total++;

    $regions = $data['regions'];
    unset($data['regions']);

    $this->directProject->insert($data);

    $regions = explode(' ',$regions);
    foreach($regions as $region)
    {
      $regionId = $this->getRegion($region);
      if ($regionId)
      {
        $datax = array(
          'project_id' => $data['id'],
          'org_id'     => $regionId,
          'type_id'    => 1,
          'status'     => 1,
        );
        $this->directProjectOrg->insert($datax);
      }
    }
  }
  protected $regions = array();
  protected function getRegion($region)
  {
    if (!$region) return NULL;

    // Need to find the org_id for the region
    if (!isset($this->regions[$region]))
    {
      $result = $this->directOrg->getOrgForKey($region);

      $org = $result->row;
      if (!$org)
      {
        echo("Could not find region: $region\n"); // Some regions are revoked
        $this->regions[$region] = 0;
        return 0;
      }
      
      $this->regions[$region] = $org['unit_id'];
    }
    return $this->regions[$region];
  }
}
?>
