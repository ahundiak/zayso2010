<?php 
  $refs = $this->refs;
  $colCount = count($refs) + 2; 
?>
<form method="post" action="<?php echo $this->link('ref_avail_signup'); ?>">
  <?php foreach($refs as $ref) { ?>
    <input 
      type="hidden" 
      name="ref_avail_person_id[<?php echo $ref->personId; ?>]"
      value="<?php echo $ref->personId; ?>" />
    <input 
      type="hidden" 
      name="ref_avail_group_id[<?php echo $ref->personId; ?>]"
      value="<?php echo $ref->groupId; ?>" />
    <input 
      type="hidden" 
      name="ref_avail_id[<?php echo $ref->personId; ?>]"
      value="<?php echo $ref->id; ?>" />
    <input 
      type="hidden" 
      name="ref_avail_fname[<?php echo $ref->personId; ?>]"
      value="<?php echo $ref->fname; ?>" />
    <input 
      type="hidden" 
      name="ref_avail_lname[<?php echo $ref->personId; ?>]"
      value="<?php echo $ref->lname; ?>" />
    <input 
      type="hidden" 
      name="ref_avail_nname[<?php echo $ref->personId; ?>]"
      value="<?php echo $ref->nname; ?>" />

  <?php } ?>
<table border="1">
<tr><th colspan="<?php echo $colCount; ?>">Referee Availability</th></tr>
<tr>
  <td colspan="2"></td>
  <?php foreach($refs as $ref) { ?>
    <td><?php echo $ref->name; ?></td>
  <?php } ?>
 </tr>
 
 <tr>
  <td rowspan="5">Referee<br />Certification</td>
  <td>AYSOID</td>
  <?php foreach($refs as $ref) { ?>
    <td><?php echo $ref->aysoid; ?></td>
  <?php } ?>
 </tr>
 <tr>
  <td>Region</td>
  <?php foreach($refs as $ref) { ?>
    <td><?php echo $ref->region; ?></td>
  <?php } ?>
 </tr>
 <tr>
  <td>Season</td>
  <?php foreach($refs as $ref) { ?>
    <td><?php echo $ref->season; ?></td>
  <?php } ?>
 </tr>
 <tr>
  <td>Safe Haven</td>
  <?php foreach($refs as $ref) { ?>
    <td><?php echo $ref->safeHaven; ?></td>
  <?php } ?>
 </tr>
 <tr>
  <td>Ref Badge</td>
  <?php foreach($refs as $ref) { ?>
    <td><?php echo $ref->refBadge; ?></td>
  <?php } ?>
 </tr>
 
 <!-- Contact Information -->
 <!-- Add region bymber, season, age -->
<tr> 
 <tr>
  <td rowspan="5">Contact<br />Information</td>
  <td>Phone Home</td>
  <?php foreach($refs as $ref) { ?>
    <td><input 
      type="text" size="20" maxlength="20"
      name="ref_avail_phone_home[<?php echo $ref->personId; ?>]"
      value="<?php echo $ref->phoneHome; ?>" />
    </td>
  <?php } ?>
 </tr>
<tr>
<td>Phone Work</td>
<?php foreach($refs as $ref) { ?>
    <td><input 
      type="text" size="20" maxlength="20"
      name="ref_avail_phone_work[<?php echo $ref->personId; ?>]"
      value="<?php echo $ref->phoneWork; ?>" />
    </td>
  <?php } ?>
 </tr>
 <tr>
<td>Phone Cell</td>
<?php foreach($refs as $ref) { ?>
    <td><input 
      type="text" size="20" maxlength="20"
      name="ref_avail_phone_cell[<?php echo $ref->personId; ?>]"
      value="<?php echo $ref->phoneCell; ?>" />
    </td>
  <?php } ?>
 </tr>
<tr>
<td>Email Home</td>
<?php foreach($refs as $ref) { ?>
    <td><input 
      type="text" size="20" maxlength="40"
      name="ref_avail_email_home[<?php echo $ref->personId; ?>]"
      value="<?php echo $ref->emailHome; ?>" />
    </td>
  <?php } ?>
 </tr>
<tr>
<td>Email Work</td>
<?php foreach($refs as $ref) { ?>
    <td><input 
      type="text" size="20" maxlength="40"
      name="ref_avail_email_work[<?php echo $ref->personId; ?>]"
      value="<?php echo $ref->emailWork; ?>" />
    </td>
 <?php } ?>
 </tr>
<tr>
  <td rowspan="2">Highest Division<br />To Referee</td>
  <td>Center</td>
  <?php foreach($refs as $ref) { ?>
    <td>
      <select name="ref_avail_div_cr[<?php echo $ref->personId; ?>]" >
        <?php echo $this->formOptions($this->divPickList,$ref->divCR); ?>
      </select>
    </td>
  <?php } ?>
</tr>
<tr>
  <td>Assist</td>
  <?php foreach($refs as $ref) { ?>
    <td>
      <select name="ref_avail_div_ar[<?php echo $ref->personId; ?>]" >
        <?php echo $this->formOptions($this->divPickList,$ref->divAR); ?>
      </select>
    </td>
  <?php } ?>
</tr>

 <tr>
  <td rowspan="3">Teams with<br />child, sibling, etc</td>
  <td>Team 1</td>
  <?php foreach($refs as $ref) { ?>
    <td>
      <select name="ref_avail_team1[<?php echo $ref->personId; ?>]" >
        <?php echo $this->formOptions($ref->teams,$ref->team1); ?>
      </select>
    </td>
  <?php } ?>
 </tr>
 <tr>
  <td>Team 2</td>
  <?php foreach($refs as $ref) { ?>
    <td>
      <select name="ref_avail_team2[<?php echo $ref->personId; ?>]" >
        <?php echo $this->formOptions($ref->teams,$ref->team2); ?>
      </select>
    </td>
  <?php } ?>
 </tr>
 <tr>
  <td>Team 3</td>
  <?php foreach($refs as $ref) { ?>
    <td>
      <select name="ref_avail_team3[<?php echo $ref->personId; ?>]" >
        <?php echo $this->formOptions($ref->teams,$ref->team3); ?>
      </select>
    </td>
  <?php } ?>
 </tr>

 <tr><td colspan="4"></td></tr>
 <tr>
  <td rowspan="2">Area Tournament<br />U10-14, Madison</td>
  <td>Sat, Nov 7th</td>
  <?php foreach($refs as $ref) { ?>
    <td>
      <select name="ref_avail_day1[<?php echo $ref->personId; ?>]" >
        <?php echo $this->formOptions($this->availPickList,$ref->day1); ?>
      </select>
    </td>
  <?php } ?>
 </tr>
 <tr>
  <td>Sun, Nov 8th</td>
  <?php foreach($refs as $ref) { ?>
    <td>
      <select name="ref_avail_day2[<?php echo $ref->personId; ?>]" >
        <?php echo $this->formOptions($this->availPickList,$ref->day2); ?>
      </select>
    </td>
  <?php } ?>
 </tr>
 
 <tr><td colspan="4"></td></tr>
 <tr>
  <td rowspan="2">Area Tournament<br />U16-19, Huntsville</td>
  <td>Sat, Nov 14th</td>
  <?php foreach($refs as $ref) { ?>
    <td>
      <select name="ref_avail_day3[<?php echo $ref->personId; ?>]" >
        <?php echo $this->formOptions($this->availPickList,$ref->day3); ?>
      </select>
    </td>
  <?php } ?>
 </tr>
 <tr>
  <td>Sun, Nov 15th</td>
  <?php foreach($refs as $ref) { ?>
    <td>
      <select name="ref_avail_day4[<?php echo $ref->personId; ?>]" >
        <?php echo $this->formOptions($this->availPickList,$ref->day4); ?>
      </select>
    </td>
  <?php } ?>
 </tr>
 <tr><td colspan="4"></td></tr>
<tr>
  <td rowspan="2">State Tournament<br />U12-19, Cullman</td>
  <td>Sat, Nov 21th</td>
  <?php foreach($refs as $ref) { ?>
    <td>
      <select name="ref_avail_day5[<?php echo $ref->personId; ?>]" >
        <?php echo $this->formOptions($this->availPickList,$ref->day5); ?>
      </select>
    </td>
  <?php } ?>
 </tr>
 <tr>
  <td>Sun, Nov 22th</td>
  <?php foreach($refs as $ref) { ?>
    <td>
      <select name="ref_avail_day6[<?php echo $ref->personId; ?>]" >
        <?php echo $this->formOptions($this->availPickList,$ref->day6); ?>
      </select>
    </td>
  <?php } ?>
 </tr>
 <tr>
   <td colspan="<?php echo $colCount; ?>">
     Enter notes here:<br />
     <textarea name="ref_avail_notes" 
     	rows="4" cols="50"
     	style="text-align: left; width: 500px"
     	><?php echo $ref->notes; ?></textarea>
   </td>
 </tr>
 <tr>
   <td colspan="<?php echo $colCount; ?>" style="text-align: right">
     <input type="submit" name="ref_avail_submit" value="Save" />
   </td>
</tr>
</table>
</form>
