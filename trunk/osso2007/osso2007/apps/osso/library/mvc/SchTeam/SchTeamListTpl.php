<?php $data = $this->schTeamListData; ?>

<form method="post" action="<?php echo $this->link('sch_team_list'); ?>">
<table border="1">
<tr><th colspan="5">Search and List Schedule Teams</th></tr>
<tr>
    <td>
        <select name="sch_team_year_id">
            <option value="0">All Years</option>
            <?php echo $this->formOptions($this->yearPickList,$data->yearId); ?>" />
        </select>
    </td>
    <td>
        <select name="sch_team_season_type_id">
            <option value="0">All Seasons</option>
            <?php echo $this->formOptions($this->seasonTypePickList,$data->seasonTypeId); ?>" />
        </select>
    </td>
    <td>
        <select name="sch_team_schedule_type_id">
            <option value="0">All Types</option>
            <?php echo $this->formOptions($this->scheduleTypePickList,$data->scheduleTypeId); ?>" />
        </select>
    </td>
    <td>
        <select name="sch_team_unit_id">
            <option value="0">All Regions</option>
            <?php echo $this->formOptions($this->unitPickList,$data->unitId); ?>" />
        </select>
    </td>
    <td>
        <select name="sch_team_division_id">
            <option value="0">Division</option>
            <?php echo $this->formOptions($this->divisionPickList,$data->divisionId); ?>" />
        </select>
    </td>
</tr>
<tr>
    <td colspan="4">
        <input type="submit" name="sch_team_submit_create" value="Create" />
        <input type="text"   name="sch_team_num_to_create" value="" size = "4" />
        Schedule Teams.
    </td>
    <td><input type="submit" name="sch_team_submit_search" value="Search" />
</tr>
</table>
<br />
<table border="1">
<tr><th colspan="10">Schedule Team Listing</th></tr>
<tr>
    <td>Edit</td>
    <td>Year</td>
    <td>Season</td>
    <td>Region</td>
    <td>Div</td>
    <td>Sort</td>
    <td>Type</td>
    <td>Description</td>
    <td>Physical Team</td>
    <td>Delete</td>
</tr>
<?php foreach($this->schTeams as $schTeam) { 
        $schTeamId = $schTeam->id;
        if (!$schTeam->phyTeam->id) $phyTeamDesc = 'Not Assigned';
        else {
            $phyTeam     = $schTeam->phyTeam;
            $phyTeamDesc = $phyTeam->key;
            $coachName   = $phyTeam->coachHead->namex;
            if ($coachName) $phyTeamDesc .= ' ' . $coachName;
            $phyTeamDesc = $this->href($this->escape($phyTeamDesc),'phy_team_edit',$phyTeam->id);
        }
?>
<tr>
    <td><?php echo $this->href('Edit','sch_team_edit',$schTeamId); ?></td>
    <td><?php echo $this->escape($schTeam->year);             ?></td>
    <td><?php echo $this->escape($schTeam->seasonTypeDesc);   ?></td>
    <td><?php echo $this->escape($schTeam->unitDesc);         ?></td>
    <td><?php echo $this->escape($schTeam->divisionDesc);     ?></td>
    <td><?php echo $this->escape($schTeam->sort);             ?></td>
    <td><?php echo $this->escape($schTeam->scheduleTypeDesc); ?></td>
    <td><?php echo $this->escape($schTeam->desc);             ?></td>
    <td><?php echo $phyTeamDesc;      ?></td>
    <td><input type="checkbox" name = "sch_team_delete_ids[<?php echo $schTeamId; ?>]" value="<?php echo $schTeamId; ?>" /></td>
</tr>
<?php } ?>
<tr>
    <td colspan="9"></td>
    <td>
        <input type="checkbox" name="sch_team_confirm_delete" value="1" />
        <input type="submit"   name="sch_team_submit_delete"  value="Delete" />
    </td>
</tr>
</table>
</form>
