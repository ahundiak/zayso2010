<?php $title="Registration Page 1" ?>
<div>
  <p>In order to register as a referee you must have your AYSO volunteer id as well as your AYSO email address as entered in eayso.org.
    An email will be sent to your AYSO email as part of the confirmation process.
    You should also ensure that your cell phone number in eayso is correct.
  </p>
  <table border="1" class="form_table">
    <tr><th colspan="2">AYSO Information</th></tr>
    <tr><td>AYSO ID (8 digits)</td><td><input type="text" name="vi_uname" size="8 "value="" /></tr>
    <tr><td>AYSO Region Number</td><td><input type="text" name="vi_uname" size="4" value="" /></td></tr>
    <tr><td>AYSO Email</td><td><input type="text" name="vi_uname" size="30" value="" /></td></tr>
    <tr><td>AYSO Cell Phone</td><td><input type="text" name="vi_uname" size="16" value="" /></td></tr>
    <tr><td colspan="2"></td></tr>
    <tr><td>First Name</td><td><input type="text" name="vi_uname" size="30" value="" /></td></tr>
    <tr><td>Last Name</td><td><input type="text" name="vi_uname" size="30" value="" /></td></tr>
    <tr><td>Nick Name</td><td><input type="text" name="vi_uname" size="30" value="" /></td></tr>
    <tr><td></td><td><input type="submit" name="" value="Continue" /></td></tr>

    <tr><td colspan="2">AYSO information</td></tr>
  </table>
  <p>
    After pressing continue, nayso will attempt to lookup the volunteer.  If it can then an email will be sent and we tell the user to confirm.
    If the aysoid cannot be found (non section 5) then we tell the user to expect an email in a few days and then allow them to continue.
  </p>
</div>
