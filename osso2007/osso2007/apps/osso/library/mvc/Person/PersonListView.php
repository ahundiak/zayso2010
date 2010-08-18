<?php
class PersonListView extends Proj_View
{
    function init()
    {
        parent::init();
        
        $this->tplTitle   = 'List Person';
        $this->tplContent = 'PersonListTpl';
    }
    function process($data)
    {
        $models = $this->context->models;
        
        $this->personListData = $data;
                       
        $this->unitPickList       = $models->UnitModel      ->getPickList();
        $this->yearPickList       = $models->YearModel      ->getPickList();    
        $this->volTypePickList    = $models->VolTypeModel   ->getPickList();
        $this->seasonTypePickList = $models->SeasonTypeModel->getPickList();
        
        /* Now need to query the people */
        $flag = FALSE;
        if ($data->lname)     $flag = TRUE;
        if ($data->fname)     $flag = TRUE;
        if ($data->personId)  $flag = TRUE;
        if ($data->volTypeId) $flag = TRUE;
        
        if (!$flag) $personItems = array();
        else {
            $data->wantx = TRUE;
            $personItems = $models->PersonVolModel->search($data);
        }
        $this->personItems = $personItems;

        // Hack in the eayso stuff
        $personIds = array();
        foreach($this->personItems as $item)
        {
          $personIds[$item->id] = $item->id;
        }
        $directPerson = new Osso2007_Person_PersonDirect($this->context);
        $result = $directPerson->getCerts(array('person_id' => $personIds));
        $this->certs = $result->rows;
        
        $this->repoCert = new Eayso_Reg_Cert_RegCertRepo($this->context);

        /* Render it */        
        return $this->renderx();
    }
    function displayPersonName($person)
    {
        // $name = $person->lastName . ', ' . $person->firstName;
        return $this->escape($person->namex);
    }
    function displayCerts($person)
    {
      $id = $person->id;
      if (!isset($this->certs[$id])) return 'Not in eayso';
      $item = $this->certs[$id];

      $lines = array();

      $fname = $item['eayso_fname'];
      $nname = $item['eayso_nname'];
      $lname = $item['eayso_lname'];

      if ($nname) $name = $fname . ' (' . $nname . ') ' . $lname;
      else        $name = $fname . ' ' .$lname;

      $lines[] = $this->escape($name);
      
      $line = 'MY' . $item['eayso_reg_year'] . ' ' . $item['eayso_aysoid'];
      $lines[] = $line;

      foreach($item['certs'] as $cert)
      {
        $line = $this->repoCert->getDesc($cert['cert_type']);
        $lines[] = $this->escape($line);
      }

      return implode("<br \>\n",$lines);
    }
    function displayVolList($vols)
    {
        // $db = $this->context->db;
        $cnt = 0;
        $max = 6;
        $html = NULL;
        $vols = array_values($vols);
        for($i = count($vols) - 1; $i >= 0; $i--)
        {
          $vol    = $vols[$i];
          $unit   = $vol->unitKey;        //UnitModel::getKey       ($db,$vol->unitId);
          $year   = $vol->year;           //YearModel::getDesc      ($db,$vol->regYearId);
          $season = $vol->seasonTypeDesc; // SeasonTypeModel::getDesc($db,$vol->seasonTypeId);
          $div    = $vol->divisionDesc;   // DivisionModel::getDesc  ($db,$vol->divisionId);
          $job    = $vol->volTypeDesc;    // VolTypeModel::getDesc   ($db,$vol->volTypeId);

          if ($cnt <= $max && $vol->volTypeId != 27) // Zayso admin
          {
            if ($html) $html .= "<br />\n";
            $html .= "{$unit} {$year} {$season} {$job}";
            if ($div) $html .= " {$div}";
            $cnt++;
          }
        }
        return $html;
    }
}
?>
