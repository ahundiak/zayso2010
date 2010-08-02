<?php
class Osso_Account_AccountDirect extends Osso_Base_BaseDirect
{
  // Enter with account person id
  public function getAccountPersonData($params)
  {
    $result = $this->newResult();

    $search = array('id' => $params['id']);
    $sql  = <<<EOT
SELECT
  account_person.id     as account_person_id,
  account_person.fname  as account_person_fname,
  account_person.lname  as account_person_lname,
  account_person.org_id as account_person_org_id,

  account.id        AS account_id,
  account.user_name AS account_user_name,
  account.lname     AS account_lname,
  account.hint      AS account_hint,
  account.email     AS account_email,
  account.status    AS account_status

FROM account_person
LEFT JOIN account ON account.id = account_person.account_id

WHERE account_person.id IN (:id)
;
EOT;
    $result->row = $this->db->fetchRow($sql,$search);
    return $result;
  }
  // Enter with account id
  public function getAccountData($params)
  {
    $result = $this->newResult();

    $search = array('id' => $params['id']);

    $sql  = <<<EOT
SELECT
  account.id                AS account_id,
  account.account_person_id AS account_person_primary_id
  account.user_name         AS account_user_name,

  account_person.id         AS account_person_id,
  account_person.fname      AS account_person_fname,
  account_person.lname      AS account_person_lname,
  account_person.org_id     AS account_person_org_id,
  account_person.person_id  AS person_id

FROM account
LEFT JOIN accountPerson ON account_person.account_id = account.accountid

WHERE account.id IN (:id)
;
EOT;

    $rows = $this->db->fetchRows($sql,$search);
  }
  public function getUserData($params)
  {
    $result = $this->newResult();

    $search = array('id' => $params['id']);
    $sql  = 'SELECT * FROM user_data_view_eayso WHERE id = :id ORDER BY cert_cat;';
    $rows = $this->db->fetchRows($sql,$search);

    if (count($rows) < 1)
    {
      $result->error = "Unknown account person id";
      return $result;
    }
    $row = $rows[0];
    $data = array();

    // These are constant
    $fields = array(
      'id','fname','lname',
      'account_id','account_user_name',
      'org_id','org_key','org_desc',
      'person_idx','person_id','dob','sex'
    );
    foreach($fields as $field) { $data[$field] = $row[$field]; }

    $data['certs'] = array();
    $data['reg_year'] = 0;
    foreach($rows as $row)
    {
      if ($row['cert_cat'] > 0)
      {
        $data['certs'][$row['cert_cat']] = array
        (
            'cert_cat'  => $row['cert_cat'],
            'cert_type' => $row['cert_type'],
            'cert_date' => $row['cert_date'],
        );
      }
      
      // Want the highest registration year between osso and eayso
      $regYear = (int)$row['reg_year'];
      if ($regYear && ($regYear > $data['reg_year'])) $data['reg_year'] = $regYear;
    }
    $result->row = $data;
    return $result;
  }
  public function authenticate($params)
  {
    $result = $this->newResult();

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
      $data['account_name'] = $params['account_name'];
      $data['account_name_exists'] = true;
      $result->data = $data;
      
      $result->msg = 'Authenticated: ' . $params['account_name'];
      $result->id  = $row['user_id'];
      return $result;
    }

    // See if have a valid user name
    $data = $db->find('account','user_name',$params['account_name']);
    if (!$data)
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
    $result->error = $msg;
    $result->msg   = $msg;
    $result->data  = $data;

    return $result;
  }
  public function create($params)
  {
    $result = $this->newResult();

    $db = $this->db;

    // Validation
    $userName = $params['user_name'];
    if (!$userName) $result->error = 'Missing or invalid user name';

    $userPass1 = $params['user_pass1'];
    if (!$userPass1) $result->error = 'Missing or invalid password';

    $userPass2 = $params['user_pass2'];
    if ($userPass1 != $userPass2) $result->error = 'Passwords do not match';

    $userPass = md5($userPass1);

    $fname = $params['user_fname'];
    if (!$fname) $result->error = 'First name is required';

    $lname = $params['user_lname'];
    if (!$lname) $result->error = 'Last name is required';

    // See if everything passed
    if (!$result->success)
    {
      return $result;
    }
    // Create account
    $data = array
    (
      'user_name' => $userName,
      'user_pass' => $userPass,
      'lname'     => $lname,
      'hint'      => $params['user_pass_hint'],
      'email'     => $params['user_email'],
    );
    try
    {
      $db->insert('account','id',$data);
    }
    catch (Exception $e)
    {
      $result->error = 'Account name already exists';
      return $result;
    }
    $accountId = $db->lastInsertId();

    // Create account person
    $data = array
    (
      'account_id' => $accountId,
      'fname'      => $fname,
      'lname'      => $lname,
    );
    $db->insert('account_person','id',$data);
    $accountPersonId = $db->lastInsertId();

    // Make it the default
    $data = array('id' => $accountId, 'account_person_id' => $accountPersonId);
    $db->update('account','id',$data);

    // Done
    $result->msg = 'Account Created';
    $result->id  = $accountPersonId;

    return $result;
  }
}

?>
