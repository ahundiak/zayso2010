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
    //
    // Build list to search by
    $search = array();
    if ($params['aysoid']) $search['aysoid'] = $params['aysoid'];
    if ($params['region']) $search['region'] = $params['region'];
    if ($params['lname' ]) $search['lname']  = $params['lname'] . '%';
    if ($params['fname' ]) $search['fname']  = $params['fname'] . '%';
    if ($params['fname' ]) $search['nname']  = $params['fname'] . '%';

    if (count($search) < 1) return $this->wrapResults(array());

    $sql = 'SELECT * FROM eayso_vols ';

    $wheres = NULL;
    if (isset($search['aysoid'])) $wheres[] = 'aysoid = :aysoid';
    if (isset($search['region'])) $wheres[] = 'region = :region';
    if (isset($search['lname' ])) $wheres[] = 'lname LIKE :lname';
    if (isset($search['fname']))  $wheres[] = '(fname LIKE :fname OR nname LIKE :nname)';

    $sql .= ' WHERE ' . implode(' AND ',$wheres);

    $sql .= ' ORDER BY lname,fname,region,aysoid;';
//die($sql);
    $db = $this->context->dbEayso;
    $vols = $db->fetchRows($sql,$search);

    if (count($vols) < 1) return  $this->wrapResults($vols);

    //$vols    = array();
    $aysoids = array();
    foreach($vols as $vol)
    {
      $aysoids[]= $vol['aysoid'];
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
