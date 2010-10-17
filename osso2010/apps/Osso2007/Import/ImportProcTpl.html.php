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
      <?php echo $this->formOptions($this->yearPickList,$data['year_id']); ?>" />
    </select>
  </td>
</tr>
<tr>
  <td>Season</td>
  <td>
    <select name="import_season_type_id">
      <option value="0">All Seasons</option>
      <?php echo $this->formOptions($this->seasonTypePickList,$data['season_type_id']); ?>" />
    </select>
  </td>
</tr>
<tr>
  <td>Project</td>
  <td>
    <select name="import_project_id">
      <?php echo $this->formOptions($this->projectPickList,$data['project_id']); ?>" />
    </select>
  </td>
</tr>
<tr>
  <td>Region</td>
  <td>
    <select name="import_org_id">
      <option value="0">All Regions</option>
      <?php echo $this->formOptions($this->orgPickList,$data['org_id']); ?>" />
    </select>
  </td>
</tr>
<tr>
  <td>Import File</td>
  <td>
    <input type="file" name="import_file" size="80"/>
  </td>
</tr>
<tr>
  <td>Processed File</td>
  <td>
    <input type="text" name="import_file_proc" readonly="readonly" size="40" value="<?php echo $data['file_name']; ?>"/>
  </td>
</tr>
<tr>
  <td>.</td>
  <td align="center"><input type="submit" name="import_submit" value="Import" />
</tr>
<?php if (isset($data['message'])) { ?>
<tr>
  <td colspan="2"><?php echo $data['message']; ?></td>
</tr>
<?php } ?>
</table>
</form>