<?php
  $data = $this->importProcData;
?>
<form method="post" enctype="multipart/form-data" action="<?php echo $this->link('import_proc'); ?>">
<table border="1">
<tr><th colspan="2">Import Information</th></tr>
<tr>
  <td style="width: 100px; ">Year</td>
  <td style="width: 500px;">
    <select name="import_year_id">
      <option value="0">All Years</option>
      <?php echo $this->formOptions($this->yearPickList,$data->yearId); ?>" />
    </select>
  </td>
</tr>
<tr>
  <td>Season</td>
  <td>
    <select name="import_season_type_id">
      <option value="0">All Seasons</option>
      <?php echo $this->formOptions($this->seasonTypePickList,$data->seasonTypeId); ?>" />
    </select>
  </td>
</tr>
<tr>
  <td>Region</td>
  <td>
    <select name="import_unit_id">
      <option value="0">All Regions</option>
      <?php echo $this->formOptions($this->unitPickList,$data->unitId); ?>" />
    </select>
  </td>
</tr>
<tr>
  <td>CSV File</td>
  <td>
    <input type="file" name="import_file" size="80"/>
  </td>
</tr>
<tr>
  <td>Processed File</td>
  <td>
    <input type="text" name="import_file_proc" readonly="readonly" value="<?php echo $data->fileName; ?>"/>
  </td>
</tr>
<tr>
  <td>.</td>
  <td align="center"><input type="submit" name="import_submit" value="Import" />
</tr>
<?php if ($data->message) { ?>
<tr>
  <td colspan="2"><?php echo $data->message; ?></td>
</tr>
<?php } ?>
</table>
</form>

