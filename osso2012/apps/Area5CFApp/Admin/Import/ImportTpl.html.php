<?php
  $data = $this->tplData;
?>
<form method="post" enctype="multipart/form-data" action="admin-import">
<table border="1">
<tr><th colspan="2">Import Information</th></tr>
<tr>
  <td>Import File</td>
  <td>
    <input type="file" name="import_file" size="80"/>
  </td>
</tr>
<tr>
  <td>Processed File</td>
  <td>
    <input type="text" name="import_file_proc" readonly="readonly" size="40" value="processed"/>
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
