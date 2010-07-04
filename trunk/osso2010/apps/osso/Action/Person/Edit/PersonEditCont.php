<?php
class Action_Person_Edit_PersonEditCont extends Action_Base_BaseCont
{
  protected $context;
  protected $tplTitle = 'OSSO PersonEdit';
  protected $tplName  = 'Action/Person/Edit/PersonEdit.html.php';
  protected $userMustBeLoggedIn = false;

  function executeGet()
  {
    $this->rows = array
    (
      array('key' => 'reg_num', 'type' => 'text', 'label' => 'Reg Type'),
      array('key' => 'reg_num', 'type' => 'text', 'label' => 'Reg ID'),
      array('key' => 'reg_num', 'type' => 'text', 'label' => 'Reg Year'),
      array('key' => 'region',  'type' => 'text', 'label' => 'Region'),
      array('key' => 'sep'),

      array('key' => 'fname',   'type' => 'text', 'label' => 'First Name'),
      array('key' => 'nname',   'type' => 'text', 'label' => 'Nick Name'),
      array('key' => 'lname',   'type' => 'text', 'label' => 'Last Name'),
      array('key' => 'mname',   'type' => 'text', 'label' => 'Middle Name'),
      array('key' => 'sname',   'type' => 'text', 'label' => 'Suffix'),
      array('key' => 'sep'),

      array('key' => 'hphone', 'type' => 'text', 'label' => 'Home Phone'),
      array('key' => 'wphone', 'type' => 'text', 'label' => 'Work Phone'),
      array('key' => 'cphone', 'type' => 'text', 'label' => 'Cell Phone'),
      array('key' => 'hemail', 'type' => 'text', 'label' => 'Home Email'),
      array('key' => 'wemail', 'type' => 'text', 'label' => 'Work Email'),
      array('key' => 'cemail', 'type' => 'text', 'label' => 'Cell Email'),
      array('key' => 'sep'),

      array('key' => 'dob',     'type' => 'text', 'label' => 'DOB'),
      array('key' => 'sex',     'type' => 'text', 'label' => 'Gender'),
      array('key' => 'account', 'type' => 'text', 'label' => 'Account'),
      array('key' => 'sep'),

      array('key' => 'status',    'type' => 'text', 'label' => 'Status'),
      array('key' => 'coach',     'type' => 'text', 'label' => 'Coach'),
      array('key' => 'referee',   'type' => 'text', 'label' => 'Referee'),
      array('key' => 'safe_haven','type' => 'text', 'label' => 'Safe Haven'),
    );

    // User has signed in to get here to go to home page
    $this->renderPage();
  }
  protected function renderRow($row)
  {
    $key    = $row['key'];
    if ($key == 'sep') return "<tr><td colspan=\"4\"</td></tr>\n";


    $label  = $row['label'];
    $nameInput = 'person_edit_input_' . $key;
    $nameCB    = 'person_edit_cb_'    . $key;
    $value1 = '';
    $value2 = '';

    $isChecked = '';

    
    $html = <<< EOT
<tr>
  <td>$label</td>
  <td>
    <input type="text" name="$nameInput" size="40" value="$value1" />
  </td>
  <td>
    <input type="checkbox" name=$nameCB value="" />
  </td>
  <td>
    <input type="text" readonly="readonly" size="40" value="$value2" />
  </td>
</tr>
EOT;
    return $html;
  }
}

?>
