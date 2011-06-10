<?php
  $data  = $this->data;
  $game  = $data->game;
  $user  = $this->services->user;
  $vol   = $data->vol;
  $posId = $data->posId;
  $assId = $data->assId;
  $status= $data->status;
?>
<form name="referee_signup" method="post" action="schedule-assign" >

<input type="hidden" name="game_id" value="<?php echo $game->id;    ?>" />
<input type="hidden" name="pos_id"  value="<?php echo $posId; ?>" />

<table border="1" width="500">
<tr><th colspan="2">Referee Signup Form</th></tr>
<tr>
  <td width="150">Game Info</td>
  <td width="350"><?php echo "{$game->id}, {$game->date}, {$game->time}, {$game->div}, {$game->field}"; ?></td>
</tr>
<tr>
  <td>Bracket</td>
  <td><?php echo $game->bracket; ?></td>
</tr>
<tr>
  <td>Home Team</td>
  <td><?php echo $this->escape($game->homeTeam); ?></td>
</tr>
<tr>
  <td>Away Team</td>
  <td><?php echo $this->escape($game->awayTeam); ?></td>
</tr>
<tr><td colspan="2"></td></tr>
<?php foreach($game->personsx as $index => $person) {
    if ($person['name'] || ($index < 3)) {
?>
<tr>
  <td><?php echo $person['pos'];  ?></td>
  <td><?php echo $person['name']; ?></td>
</tr>
<?php }} ?>
<tr><td colspan="2"></td></tr>
<tr>
  <td>Referee Position</td>
  <td>
  <?php
    if (!$user->isAdmin()) echo $data->posPickList[$posId];
    else {
  ?>
    <select name="pos_id">
      <?php echo $this->formOptions($data->posPickList,$posId); ?>
    </select>
	<?php } ?>
  </td>
</tr>
<tr>
  <td>Request Assessment</td>
  <td>
    <select name="ass_id">
      <option value="0">None</option>
      <?php echo $this->formOptions($data->assPickList,$assId); ?>
    </select>
  </td>
</tr>
<tr>
  <td>Referee AYSOID</td>
  <td>
    <input type="text" name="aysoid" size="10"
           value="<?php echo $this->escape($vol->aysoid); ?>"
           <?php if (!$user->isAdmin()) { ?>readonly="readonly" <?php } ?>
           />
    <input type="submit" name="referee_verify" value="Verify AYSOID" />
  </td>
</tr>
<tr><td colspan="2"></td></tr>
<tr>
  <td>Referee Region</td>
  <td><?php echo $vol->orgDesc; ?></td>
</tr>
<tr>
  <td>Referee First Name</td>
  <td>
    <?php echo $this->escape($vol->fname); ?>
  </td>
</tr>
<tr>
  <td>Referee Last Name</td>
  <td>
    <?php echo $this->escape($vol->lname); ?>
  </td>
</tr>
<tr>
  <td>Referee Badge</td>
  <td>
    <?php echo $this->escape($vol->certRefereeDesc1); ?>
  </td>
</tr>
<tr><td colspan="2"></td></tr>
<tr>
  <td>Assignment Status</td>
  <td>
    <select name="status">
      <?php echo $this->formOptions($data->statusPickList,$status); ?>
    </select>
  </td>
</tr>
<tr>
  <td><a href="schedule-show">Return to schedule</a></td>
  <td>
    <input
      type="submit"
      name="referee_signup"
      value="Sign Up For Game"
      <?php if (0) { ?>disabled="disabled" <?php } ?>/>
  </td>
</tr>
<?php if ($data->errors) { ?>
<tr>
  <td colspan="2" style="color: red">
    <?php foreach($data->errors as $error)
    {
      echo $this->escape($error) . "\n<br />";
    }
    ?>
  </td>
</tr>
<?php }?>
</table>
</form>
<br />
<div style="width: 600px">
<p>
To sign up for a game, press "Sign Up For Game".
Your name will appear in the middle section of the form indicating that the assignment has been accepted
and is pending approval.  Return to the schedule screen and you will see your name in green.  When the
assignor approves your request your name will turn to black.
</p>
<ul>
  <li>Do not sign up for your own child's games.</li>
  <li>Do not sign up for your own region's games.</li>
  <li>Avoid signing up for games in the same division/bracket that your children play in.</li>
</ul>
<p>
If you really do not want to do the game because your schedule is packed but are signing up anyways because
it is required for the tournament then choose the "Ref only if needed" option from the Assignment Status
column.  Your name will show in red on the schedule and other people will be able to take your assignment.
You are still on the hook to do the game unless your name gets removed from the schedule.
</p><br /><p>
Request to be removed from a game by selecting "Request Removal" from the assignment status then pressing "Sign Up For Game".
It may take a few hours before you are actually removed.  For help, contact 
<a href="mailto:ahundiak@s5games.org">Art Hundiak</a>. Phone: 256.457.5943
</p>
</div>

