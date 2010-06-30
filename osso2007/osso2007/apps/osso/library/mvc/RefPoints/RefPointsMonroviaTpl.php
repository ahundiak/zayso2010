<h3>Monrovia Referee Points Information</h3>
<?php $referees = $this->referees; ?>
<?php if (count($referees) < 1) { ?>
<p>No active Monrovia referees for this account.  Contact your Regional referee Administrator if you feel this is an error.
</p>
<?php } else {  ?>

<table border="1">
<tr><th colspan="2">Referee Points Sumary</th></tr>
<tr>
  <th>Name</th>
  <th>Regular Season<br />Game Count</th>
</tr>
<?php foreach ($referees as $referee) { ?>
<tr>
  <td><?php echo $this->escape($referee->fname . ' ' .  $referee->lname); ?></td>
  <td style="text-align: center"><?php echo $referee->gameCnt;      ?></td>
</tr>
<?php } ?>
</table>
<br />
<form method="post" action="<?php echo $this->link('ref_points_monrovia'); ?>">
<table border="1">
<tr><th colspan="4">Referee Team Representation</th></tr>
<tr>
  <th>Referee Name</th>
  <th>Team Description</th>
  <th>Season<br />Priority</th>
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
    <input type="checkbox" name="ref_team_deletes[<?php echo $id; ?>]" value="<?php echo $id; ?>" />
  </td>
</tr>
<?php } ?>
<tr>
<td colspan="3">
<td><input type="submit" name="ref_teams_submit_update" value="Update" />
</tr>
</table>
</form>
<br />

<?php $data = $this->tplData; ?>
<form method="post" action="<?php echo $this->link('ref_points_monrovia'); ?>">
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
<h3>Instructions</h3>
<p>Use the Represent team buttom to select the team to represent.  
For purposes of the point system, a referee is only allowed to represent one team.
However the system will allow you to pick up to three teams.  
If your first team aleady has a referee then your representation will shift to the second team.
</p>
<p>The summary table at the top shows how many games you have done so far.  
All you need is three to satisfy the points requirement.
We would of course like you to do more.
<?php } ?>