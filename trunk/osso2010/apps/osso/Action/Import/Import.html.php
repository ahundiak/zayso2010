<br />
<table border="1" class="form">
<form id="import-form" action="?la=import" method="post" enctype="multipart/form-data" >
  <tr><th colspan="2">Import File</th></tr>
  <tr>
    <td>Source</td>
    <td><select name="source">
      <?php echo $this->context->html->formOptions($this->sourceOptions); ?>
    </select>
    </td>
  </tr>
  <tr>
    <td>Import File</td>
    <td><input type="file" name="file" size="80" /></td>
  </tr>
  <tr>
    <td>Imported File</td>
    <td><?php echo $this->importFileName; ?></td>
  </tr>
  <tr>
    <td>Imported Class</td>
    <td><?php echo $this->importClassName; ?></td>
  </tr>
  <tr>
    <td>Imported Results</td>
    <td><?php echo $this->importResults; ?></td>
  </tr>
  <tr>
    <td>Errors</td>
    <td style="color: red">
      <?php
        if ($this->importErrors) echo implode('<br />',$this->importErrors);
        else echo '&nbsp;'; //  $this->context->html->escape(' ');
     ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="right"><input type="submit" value="Import" /></td>
  </tr>
</form>
</table>