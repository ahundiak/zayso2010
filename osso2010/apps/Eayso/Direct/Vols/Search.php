<?php
class Direct_Vols_Search extends ExtJS_Direct_Base
{
  function load($params)
  {
    $data = array
    (
      //'region'  => 894,
      'lname'   => 'Hundi',
    );
    $results = array
    (
      'success' => true,
      'data'    => $data,
    );
    return $results; // Wonder why wrap does not work for form load?
  }
  function read($params)
  {
    // Cerad_Debug::dump($params);
    $region = $params['region'];
    if ($region) $params['region'] = sprintf('R%04u',$region);

    // Build list to search by
    $search = array();
    if ($params['aysoid']) $search['aysoid'] = $params['aysoid'];
    if ($params['region']) $search['region'] = $params['region'];
    if ($params['lname' ]) $search['lname']  = $params['lname'] . '%';
    if ($params['fname' ]) $search['fname']  = $params['fname'] . '%';
    if ($params['fname' ]) $search['nname']  = $params['fname'] . '%';

    if (count($search) < 1) return $this->wrapResults(array());

    $sql = 'SELECT * FROM reg_main_view_all2 ';

    $wheres = NULL;
    if (isset($search['aysoid'])) $wheres[] = 'reg_num = :aysoid';
    if (isset($search['region'])) $wheres[] = 'org_key = :region';
    if (isset($search['lname' ])) $wheres[] = 'lname LIKE :lname';
    if (isset($search['fname']))  $wheres[] = '(fname LIKE :fname OR nname LIKE :nname)';

    $sql .= ' WHERE ' . implode(' AND ',$wheres);

    $sql .= ' ORDER BY lname, fname, reg_num;';
//die($sql);
    $db   = $this->context->dbEayso;
    $rows = $db->fetchRows($sql,$search);

    if (count($rows) < 1) return  $this->wrapResults($rows);

    $certRepo = new Eayso_Reg_Cert_RegCertRepo();
    
    $vols = array();
    $volCerts = array();
    foreach($rows as $row)
    {
      $regNum = $row['reg_num'];
      if (!isset($vols[$regNum]))
      {
        $vols[$regNum] = array
        (
          'aysoid'   => $row['reg_num'],
          'fname'    => $row['fname'],
          'lname'    => $row['lname'],
          'nname'    => $row['nname'],
          'dob'      => $row['dob'],
          'gender'   => $row['sex'],
          'region'   => $row['org_key'],
          'mem_year' => $row['reg_year'],
          'certs'    => array()
        );
        $volCerts[$regNum] = array();
      }
      $vol = $vols[$regNum];

      // Extract props
      switch($row['prop_type'])
      {
        case 11: $vol['phone_home'] = $row['prop_value']; break;
        case 12: $vol['phone_work'] = $row['prop_value']; break;
        case 13: $vol['phone_cell'] = $row['prop_value']; break;
        case 21: $vol['email'     ] = $row['prop_value']; break;
      }
      // Extract certs
      $certCat = $row['cert_cat'];
      if ($certCat)
      {
        if (!isset($volCerts[$regNum][$certCat]))
        {
          $certType = $row['cert_type'];
          $certDesc = $certRepo->getDesc($certType);

          $volCerts[$regNum][$certCat] = $certType;
          $vol['certs'][] = array
          (
            'cert_desc' => $certDesc,
            'cert_date' => $row['cert_date'],
          );
        }
      }
      // Store
      $vols[$regNum] = $vol;
    }
    return  $this->wrapResults(array_values($vols));

    //$vols    = array();
    $aysoids = array();
    foreach($vols as $vol)
    {
      $aysoids[]= $vol['reg_main_reg_num'];
    }
    $sql = <<<EOT
SELECT aysoid,cert_cat,cert_type,cert_desc,cert_date
FROM eayso_vol_certs
WHERE aysoid IN (:aysoids)
ORDER BY aysoid,cert_cat,cert_type;
EOT;
    $search = array('aysoids' => $aysoids);
    $certs = $db->fetchRows($sql,$search);
    foreach($vols as &$vol)
    {
      foreach($certs as $cert)
      {
        if ($vol['aysoid'] == $cert['aysoid']) $vol['certs'][] = $cert;
      }
    }

    // Done
    return $this->wrapResults($vols);

    $records = array
    (
      array
      (
        'lname'   => 'Hundiak',
        'fname'   => 'Art',
      ),
      array
      (
        'lname'   => 'Hundiak',
        'fname'   => 'Ethan',
      ),
    );
    return $this->wrapResults($records);
  }
}

?>
