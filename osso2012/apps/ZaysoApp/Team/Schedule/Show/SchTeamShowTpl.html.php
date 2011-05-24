<?php ?>
<div><p>Show Schedule Teams</p></div>
<div><a href="team-schedule-show">Schedule Teams Listing</a></div>
<div>
<form method="post" enctype="multipart/form-data" action="team-schedule-show">
<table border="1" style="width: 700px;">
<tr><th colspan="2">Generate Weekly Referee Availability Report</th></tr>
<tr>
  <td style="width: 125px;">Sport</td>
  <td style="width: 500px;">
    <select name="sport">
      <option value="0">Sport</option>
      <option value="1">Soccer</option>
      <option value="2" selected="selected">Baseball</option>
      <option value="3">Football</option>
    </select>
  </td>
</tr>
<tr>
  <td style="width: 125px;">CSV File</td>
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
</div>