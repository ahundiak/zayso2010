<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">
<html>
<head>
  <title>ZAYSO Arbiter Tools</title>
  <base href="<?php echo $this->context->config['web_path'] . 'index.php'; ?>" />
</head>
<body>
<form method="post" enctype="multipart/form-data" action="ref_avail">
<table border="1" style="width: 700px;">
<tr><th colspan="2">Generate Weekly Referee Availability Report</th></tr>
<tr>
  <td style="width: 100px;">CSV File</td>
  <td style="width: 500px;">
    <input type="file" name="ref_avail_file" size="80"/>
  </td>
</tr>
<tr>
  <td>.</td>
  <td align="center"><input type="submit" name="ref_avail_submit" value="Generate" />
</tr>
<tr>
  <td colspan="2">
    Use Arbiter to create a referee availability report for a week or so and save (From Excel)
    as a comma separated (CSV) file.  Click the Browse or Choose File button and pick the .csv file.
    Press Generate and a weekly availability file will passed back.
    Open it up, set the row and column heights to about 30 and schedule away.
  </td>
</table>
</form>

</body>
</html>
