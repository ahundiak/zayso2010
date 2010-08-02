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
        array(
            'region' => 'R0894 Monrovia',
            'status' => 'Open',
            'sites'  => array(
                array(
                  'site' => 'WCA - West Minister Christian Academy, Mt Zion',
                  'status' => 'Open',
                  'fields' => array(
                      array('name' => 'WCA U10 East'),
                      array('name' => 'WCA U12 West')
                  )
                ),
                array(
                  'site' => 'Pineview Baptist Church',
                  'status' => 'Open',
                  'fields' => array(
                      array('name' => 'Main Field')
                  )
                ),
            )
        ),
        array(
            'region' => 'R0498 Madison',
            'status' => 'Open',
            'sites'  => array(
                array(
                  'site' => 'Dublin Park',
                  'status' => 'Open',
                  'fields' => array(
                      array('name' => 'Dublin 1 (Malcom)'),
                      array('name' => 'Dublin 2'),
                      array('name' => 'Dublin 3'),
                      array('name' => 'Dublin 4'),
                  )
                ),
                array(
                  'site' => 'Palmer Park',
                  'status' => 'Open',
                  'fields' => array(
                      array('name' => 'Palmer Lower'),
                      array('name' => 'Palmer Middle'),
                      array('name' => 'Palmer Upper'),
                      array('name' => 'Palmer Forfet'),
                      array('name' => 'Palmer International'),
                  )
                ),
            )
        ),
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
