<?php 
    $schTeam   = $this->schTeam;
    $schTeamId = $schTeam->id;
?>
<form method="post" action="<?php echo $this->link('sch_team_edit'); ?>"> 
<table border="1">
<tr><th colspan="2">Edit Schedule Team</th></tr>
<tr>
    <td>ID</td>
    <td><?php echo $schTeamId; ?></td>
    <input type="hidden" name="sch_team_id" value="<?php echo $schTeamId; ?>" />
</tr>
<tr>
    <td>Year</td>
    <td><?php echo $schTeam->year; ?></td>
</tr>
<tr>
    <td>Season</td>
    <td><?php echo $schTeam->seasonTypeDesc; ?></td>
</tr>
<tr>
    <td>Region</td>
    <td><?php echo $schTeam->unitDesc; ?></td>
</tr>
<tr>
    <td>Schedule Type</td>
    <td><?php echo $schTeam->scheduleTypeDesc; ?></td>
</tr>
<tr>
    <td>Sort</td>
    <td><input type="text" name="sch_team_sort" value="<?php echo $schTeam->sort; ?>" /></td>
</tr>
<tr>
    <td>Description</td>
    <td><input type="text" name="sch_team_desc" value="<?php echo $schTeam->desc; ?>" /></td>
</tr>
<tr>
    <td>Physical Team</td>
    <td>
        <select name="sch_team_phy_team_id">
            <option value="0">Not Yet Assigned</option>
            <?php echo $this->formOptions($this->phyTeamPickList,$schTeam->phyTeamId); ?>
        </select>
    </td>
</tr>
<tr>
    <td><?php echo $this->href('Schedule Team List','sch_team_list'); ?></td>
    <td><input type="submit" name="sch_team_submit_update" value="Update" />
</tr>
</table>
</form>
