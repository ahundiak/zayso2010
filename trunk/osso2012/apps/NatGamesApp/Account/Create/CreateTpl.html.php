<?php
  $tplData = $this->tplData;

  $data = $tplData->accountCreateData;
?>
<div>
<form method="post" action="account-create">
<input type="hidden" name="project_id" value="52" />
<table border="1" style="width: 800px;">
<tr><th colspan="3">National Games 2012 Create Account</th></tr>
<tr>
  <td style="width: 125px;">Account Name</td>
  <td style="width: 200px;">
    <input type="text" name="account_create_uname" size="40" value="<?php echo $this->escape($data->uname); ?>"/>
  </td>
  <td style="width: 375px;">
    Required <br />
    Used to sign in to this system.  <br />
  </td>
</tr>
<tr>
  <td style="width: 125px;">Password</td>
  <td style="width: 200px;">
    <input type="password" name="account_create_upass1" size="20" value="<?php echo $this->escape($data->upass1); ?>"/>
  </td>
  <td style="width: 375px;">Required</td>
</tr>
<tr>
  <td style="width: 125px;">Password (repeat)</td>
  <td style="width: 375px;">
    <input type="password" name="account_create_upass2" size="20" value="<?php echo $this->escape($data->upass2); ?>"/>
  </td>
  <td style="width: 200px;">Required</td>
</tr>

<tr><td colspan="3"></td>

<tr>
  <td style="width: 200px;">AYSOID (8 digits)</td>
  <td style="width: 200px;">
    <input type="text" name="account_create_aysoid" size="20" value="<?php echo $this->escape($data->aysoid); ?>"/>
  </td>
  <td style="width: 375px;">
    Required <br />
    8 digit AYSO volunteer number. <br />
    Available from www.eayso.org
  </td>
</tr>
<tr>
  <td style="width: 125px;">AYSO First Name</td>
  <td style="width: 200px;">
    <input type="text" name="account_create_fname" size="20" value="<?php echo $this->escape($data->fname); ?>"/>
  </td>
  <td style="width: 375px;">Legal name, must match eAYSO</td>
</tr>
<tr>
<tr>
  <td style="width: 125px;">AYSO Last Name</td>
  <td style="width: 200px;">
    <input type="text" name="account_create_lname" size="20" value="<?php echo $this->escape($data->lname); ?>"/>
  </td>
  <td style="width: 375px;">Legal name, must match eAYSO</td>
</tr>
<tr>
  <td style="width: 125px;">Nick Name</td>
  <td style="width: 200px;">
    <input type="text" name="account_create_nname" size="20" value="<?php echo $this->escape($data->nname); ?>"/>
  </td>
  <td style="width: 375px;">Will be used for assignments.</td>
</tr>
<tr>
  <td style="width: 125px;">Email</td>
  <td style="width: 200px;">
    <input type="text" name="account_create_email" size="40" value="<?php echo $this->escape($data->email); ?>"/>
  </td>
  <td style="width: 375px;">
    Required<br />
    Needs to match your eAYSO email.
  </td>
</tr>
<tr>
  <td style="width: 125px;">Cell Phone</td>
  <td style="width: 200px;">
    <input type="text" name="account_create_phonec" size="20" value="<?php echo $this->escape($data->phonec); ?>"/>
  </td>
  <td style="width: 375px;">
    Highly recommended.  <br />
    Some text messages may be sent.
  </td>
</tr>
<tr><td colspan="3"></td></tr>
<tr>
  <td style="width: 125px;">AYSO Region Number</td>
  <td style="width: 200px;">
    <input type="text" name="account_create_region" size="8" value="<?php echo $this->escape($data->region); ?>"/>
  </td>
  <td style="width: 375px;"></td>
</tr>
<tr>
  <td style="width: 125px;">AYSO Referee Badge</td>
  <td style="width: 200px;">
    <select name="account_create_ref_badge">
      <?php echo $this->formOptions($tplData->refBadgePickList,$data->refBadge); ?>
    </select>
  </td>
  <td style="width: 375px;">Will be verified from eAYSO</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td align="center"><input type="submit" name="account_create_submit" value="Create Account" />
</tr>
<?php if (count($data->errors)) { ?>
<tr><td colspan="3" style="color: red;">
<?php foreach($data->errors as $error) { ?>
<?php echo $this->escape($error) . '<br />' ?>
<?php } ?>
  </td></tr>
<?php } ?>
</table>
</form>
<br />
</div>
<div>
  <h3>Instructions</h3>
  <p>
    Your AYSOID volunteer number is the key to everything.
    You should be able to look it up on www.eayso.org or ask your regional commissioner.
    Only information from eAYSO will be used for referee assignments.
    Please make every effort to keep your contact information up to date on eAYSO as the tournament approaches.
  </p><p>
    You MUST have a valid email address in eAYSO.  All tournament emails will use your eAYSO email.
    If the one in eAYSO is not up to date then go in and change it.
    A verification email will be sent to your eAYSO email address.
    You won't be able to referee without one.
  </p><p>
    We might use your eAYSO cell phone to send a very limited number of texts for last minute changes.
    We are not going to absolutely require a cell phone number but it is highly recommended.
    Again, update your volunteer form to include a valid cell phone number if necessary.
  </p><p>
    We will use your eAYSO nick name (if one is present).
    So if you don't by by your legal first name then be sure to set your nick name in eAYSO.
  </p><p>
    Membership year (2011 or 2012), age, safe haven certification as well as referee certification will also be pulled from eAYSO.
    If your certification status is not up to date then you will need to work through your region to get it fixed.
  </p><p>
    Once your account is created a verification email will be sent to your eAYSO email.
    This is a manual process so it might take a few days before your receive it.
    Clicking on a link in the email will confirm that our information about you is correct.
 </p><p>
   We will also be asking for more detailed information on your referee intentions.
   Please answer all the questions and keep your information up to date as the tournament draws closer.
 </p><p>
   In general you should only need one account per family.
   Additional referees can be added to your account once it is created.
   The will allow the account holder to sign up the other referees for games without having to switch accounts.
  </p><p>
      Questions can be emailed to: RefAdmin AT NatGames2012.org
 </p>
</div>