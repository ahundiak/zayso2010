<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">
<html>
<head>
  <title>ZAYSO Arbiter Tools</title>
  <base href="/arbiter/index.php" />
</head>
<body>
<h3>Referee Availability Report</h3>

<form method="post" enctype="multipart/form-data" action="ref_avail">
<table border="1">
<tr><th colspan="2">Generate Referee Availability Report</th></tr>
<tr>
  <td style="width: 100px;">CSV File</td>
  <td style="width: 500px;">
    <input type="file" name="ref_avail_file" size="80"/>
  </td>
</tr>
<tr>
  <td>Processed File</td>
  <td>
    <input type="text" name="ref_avail_filex" readonly="readonly" size="40" value="<?php echo 'Placeholder'; ?>"/>
  </td>
</tr>
<tr>
  <td>.</td>
  <td align="center"><input type="submit" name="ref_avail_submit" value="Generate" />
</tr>
</table>
</form>

</body>
</html>
