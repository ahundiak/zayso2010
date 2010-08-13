<?php
  $data = $this->reportProcData;
?>
<form method="post" action="<?php echo $this->link('report_proc'); ?>">
<table border="1">
<tr><th colspan="2">Generate Reports</th></tr>
<tr>
  <td style="width: 100px; ">Year</td>
  <td style="width: 500px;">
    <select name="report_year_id">
      <option value="0">All Years</option>
      <?php echo $this->formOptions($this->yearPickList,$data->yearId); ?>" />
    </select>
  </td>
</tr>
<tr>
  <td>Season</td>
  <td>
    <select name="report_season_type_id">
      <option value="0">All Seasons</option>
      <?php echo $this->formOptions($this->seasonTypePickList,$data->seasonTypeId); ?>" />
    </select>
  </td>
</tr>
<tr>
  <td>Region</td>
  <td>
    <select name="report_unit_id">
      <option value="0">All Regions</option>
      <?php echo $this->formOptions($this->unitPickList,$data->unitId); ?>" />
    </select>
  </td>
</tr>
<tr>
  <td>Report Type</td>
  <td>
    <select name="report_type_id">
      <option value="0">Select Report Type</option>
      <?php echo $this->formOptions($this->reportTypePickList,$data->reportTypeId); ?>" />
    </select>
  </td>
</tr>
<tr>
  <td>.</td>
  <td align="center"><input type="submit" name="report_submit" value="Generate" />
</tr>
<?php if ($data->message) { ?>
<tr>
  <td colspan="2"><?php echo $data->message; ?></td>
</tr>
<?php } ?>
</table>
</form>

