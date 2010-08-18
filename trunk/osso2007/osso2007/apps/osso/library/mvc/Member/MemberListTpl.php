<table border="1">
<tr><th colspan="5">List Account Members</th></tr>
<tr>
    <td style="width:  50px;">Edit</td>
    <td style="width: 100px;">Region</td>
    <td style="width: 150px;">Member Name</td>
    <td style="width: 300px;">eAYSO Information</td>
    <td style="width:  75px;">Account</td>
</tr>
<?php

  $repoCert = new Eayso_Reg_Cert_RegCertRepo($this->context);
  
  foreach($this->membersx as $item)
  {
    $memberId       = $item['member_id'];
    $memberUnitDesc = $item['member_unit_desc'];
    $accountName    = $item['account_name'];

    $memberName     = $item['member_name']  . ' ' . $item['account_name'];
    $personName     = $item['person_fname'] . ' ' . $item['person_lname'];

    if ($item['member_level'] == 1) $memberLevel = 'Primary';
    else                            $memberLevel = 'Secondary';

    if ($item['eayso_aysoid'])
    {
      $lines = array();

      $fname = $item['eayso_fname'];
      $nname = $item['eayso_nname'];
      $lname = $item['eayso_lname'];

      if ($nname) $name = $fname . ' (' . $nname . ') ' . $lname;
      else        $name = $fname . ' ' .$lname;

      // $lines[] = $this->escape($name);

      $line = $name . ' ' . $item['eayso_aysoid'] . ' MY' . $item['eayso_reg_year'];
      $lines[] = $this->escape($line);

      foreach($item['certs'] as $cert)
      {
        $line = $repoCert->getDesc($cert['cert_type']);
        $lines[] = $this->escape($line);
      }
   }
    else {
      $lines = array();
      if (strlen($personName) > 1)
      {
        $lines[] = $this->escape($personName);
      }
      $lines[] = 'Not linked to eayso';
    }
    $eaysoInfo = implode("<br />\n",$lines);

?>
<tr>
    <td><?php echo $this->href('Edit','member_edit',$memberId); ?></td>
    <td><?php echo $this->escape($memberUnitDesc);   ?></td>
    <td><?php echo $this->escape($memberName);       ?></td>
    <td><?php echo               $eaysoInfo;         ?></td>
    <td><?php echo $this->escape($memberLevel);      ?></td>
</tr>
<?php } ?>
<tr>
    <td colspan="7"><?php echo $this->href('Add new member to account','member_edit',0); ?></td>
</tr>
</table>

