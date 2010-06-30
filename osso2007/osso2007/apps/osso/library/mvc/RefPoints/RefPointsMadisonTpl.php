<h3>Madison Referee Points Information</h3>
<?php $referees = $this->referees; ?>
<?php if (count($referees) < 1) { ?>
<p>No active Madison referees for this account.  Contact your Regional referee Administrator if you feel this is an error.
</p>
<?php } else {  ?>

<table border="1">
<tr><th colspan="5">Referee Points Sumary</th></tr>
<tr>
  <th>Name</th>
  <th>Regular Season<br />Points Pending</th>
  <th>Regular Season<br />Points Processed</th>
  <th>Regional Tournament<br />Points Pending</th>
  <th>Regional Tournament<br />Points Processed</th>
</tr>
<?php foreach ($referees as $referee) { ?>
<tr>
  <td><?php echo $this->escape($referee->fname . ' ' .  $referee->lname); ?></td>
  <td style="text-align: center"><?php echo $referee->pending;      ?></td>
  <td style="text-align: center"><?php echo $referee->processed;    ?></td>
  <td style="text-align: center"><?php echo $referee->pending_rt;   ?></td>
  <td style="text-align: center"><?php echo $referee->processed_rt; ?></td>
</tr>
<?php } ?>
</table>
<br />
<form method="post" action="<?php echo $this->link('ref_points_madison'); ?>">
<table border="1">
<tr><th colspan="7">Referee Team Representation</th></tr>
<tr>
  <th>Referee Name</th>
  <th>Team Description</th>
  <th>Season<br />Priority</th>
  <th>Season<br />Max Points</th>
  <th>Tournament<br />Priority</th>
  <th>Tournament<br />Max Points</th>
  <th>Remove</th>
</tr>
<?php foreach ($this->refereeTeams as $team) { $id = $team['phy_team_referee_id']; ?>
<tr>
<input type="hidden" name="ref_team_ids[<?php echo $id; ?>]" value="<?php echo $id; ?>" />
  <td><?php echo $this->escape($team['referee_name']); ?></td>
  <td><?php echo $this->escape($team['team_desc']); ?></td>
  <td style="text-align: center">
    <select name="ref_team_pri_regulars[<?php echo $id; ?>] ?>">
      <?php echo $this->formOptions($this->refereeTeamPriorityPickList,$team['pri_regular']); ?> />
    </select>
  </td>
  <td style="text-align: center">
    <select name="ref_team_max_regulars[<?php echo $id; ?>] ?>">
      <?php echo $this->formOptions($this->refereeTeamMaxRegularPicklist,$team['max_regular']); ?> />
    </select>
  </td>
  <td style="text-align: center">
    <select name="ref_team_pri_tourns[<?php echo $id; ?>] ?>">
      <?php echo $this->formOptions($this->refereeTeamPriorityPickList,$team['pri_tourn']); ?> />
    </select>
  </td>
  <td style="text-align: center">
    <select name="ref_team_max_tourns[<?php echo $id; ?>] ?>">
      <?php echo $this->formOptions($this->refereeTeamMaxTournPicklist,$team['max_tourn']); ?> />
    </select>
  </td>
  <td style="text-align: center">
    <input type="checkbox" name="ref_team_deletes[<?php echo $id; ?>]" value="<?php echo $id; ?>" />
  </td>
</tr>
<?php } ?>
<tr>
<td colspan="6">
<td><input type="submit" name="ref_teams_submit_update" value="Update" />
</tr>
</table>
</form>
<br />

<?php $data = $this->tplData; ?>
<form method="post" action="<?php echo $this->link('ref_points_madison'); ?>">
<table border="1">
<tr><th colspan="4">Select Team To Represent</th></tr>
<tr>
<?php /* ?>
    <td>
        <select name="ref_points_add_year_id">
            <option value="0">All Years</option>
            <?php echo $this->formOptions($this->yearPickList,$data->yearId); ?>" />
        </select>
    </td>
<?php */ ?>
    <input type="hidden" name="ref_points_add_year_id" value="<?php echo $data->yearId; ?>" />
<?php /* ?>
    <td>
        <select name="ref_points_add_season_type_id">
            <option value="0">All Seasons</option>
            <?php echo $this->formOptions($this->seasonTypePickList,$data->seasonTypeId); ?>" />
        </select>
    </td>
<?php */ ?>
    <input type="hidden" name="ref_points_add_season_type_id" value="<?php echo $data->seasonTypeId; ?>" />
    
    <td>
        <select name="ref_points_add_referee_id">
            <?php echo $this->formOptions($this->refereePickList,$data->refereeId); ?> />
        </select>
    </td>
    
    <td>
        <select name="ref_points_add_unit_id">
            <option value="0">All Regions</option>
            <?php echo $this->formOptions($this->unitPickList,$data->unitId); ?> />
        </select>
    </td>

 <?php /* ?>
    <td>
        <select name="ref_points_add_division_id">
            <option value="0">Division</option>
            <?php echo $this->formOptions($this->divisionPickList,$data->divisionId); ?>" />
        </select>
    </td>
<?php */ ?>
    <input type="hidden" name="ref_points_add_division_id" value="<?php echo $data->divisionId; ?>" />
    <td>
        <select name="ref_points_add_team_id">
            <option value="0">Select Team</option>
            <?php echo $this->formOptions($this->phyTeamPickList,0); ?>" />
        </select>
    </td>
    <td><input type="submit" name="ref_points_add_submit" value="Represent Team" /></td>
</tr>
</table>
</form>
<br />
<?php } ?>