<?php
class Osso_Account_AccountDirect extends Osso_Base_BaseDirect
{
  public function getUserData($params)
  {
    $search = array('id' => $params['id']);
    $sql  = 'SELECT * FROM user_data_view WHERE id = :id ORDER BY cert_source;';
    $rows = $this->db->fetchRows($sql,$search);

    if (count($rows) < 1)
    {
      return array('success' => false);
    }
    $row = $rows[0];
    $data = array();

    // These are constant
    $fields = array(
      'id','fname','lname',
      'account_id','account_user_name',
      'org_id','org_key','org_desc',
      'person_idx','person_id','person_dob'
    );
    foreach($fields as $field) { $data[$field] = $row[$field]; }

    $data['certs'] = array();
    foreach($rows as $row)
    {
      if ($row['cert_cat'] > 0) $data['certs'][$row['cert_cat']] = $row['cert_type'];
    }
    return array('success' => true, 'data' => $data);
  }
  public function authenticate($params)
  {
    $db = $this->db;

    $search = array
    (
        'user_name' =>     $params['account_name'],
        'user_pass' => md5($params['account_pass']),
    );
    $sql = 'SELECT account_person_id AS user_id FROM account WHERE user_name = :user_name AND user_pass = :user_pass';
    $row = $db->fetchRow($sql,$search);
    if ($row)
    {
      $results = array(
        'success' => true,
        'id'      => $row['user_id'],
      );
      return $results;
    }

    // See if have a valid user name
    $data = $db->find('account','user_name',$params['account_name']);
    if ($data === FALSE)
    {
      $data['account_name'] = $params['account_name'];
      $data['account_name_exists'] = false;
      $msg = 'User login failed, Invalid account name';
    }
    else
    {
      $data['account_name'] = $data['user_name'];
      $data['account_name_exists'] = true;
      
      unset($data['user_pass']);
      unset($data['user_name']);
      
      $msg = 'User login failed, Invalid password';
    }
    $data['msg'] = $msg;
    
    // Build failed results
    $results = array
    (
      'success' => false,
      'msg'     => $msg,
      'data'    => $data
    );
    return $results;
  }
}

?>
