<?php $data = $this->phyTeamListData; ?>

<form method="post" action="<?php echo $this->link('phy_team_list'); ?>">
<table border="1">
<tr><th colspan="4">Search and List Physical Teams</th></tr>
<tr>
    <td>
        <select name="phy_team_year_id">
            <option value="0">All Years</option>
            <?php echo $this->formOptions($this->yearPickList,$data->yearId); ?>" />
        </select>
    </td>
    <td>
        <select name="phy_team_season_type_id">
            <option value="0">All Seasons</option>
            <?php echo $this->formOptions($this->seasonTypePickList,$data->seasonTypeId); ?>" />
        </select>
    </td>
    <td>
        <select name="phy_team_unit_id">
            <option value="0">All Regions</option>
            <?php echo $this->formOptions($this->unitPickList,$data->unitId); ?>" />
        </select>
    </td>
    <td>
        <select name="phy_team_division_id">
            <option value="0">Division</option>
            <?php echo $this->formOptions($this->divisionPickList,$data->divisionId); ?>" />
        </select>
    </td>
</tr>
<tr>
    <td colspan="3">
        <input type="submit" name="phy_team_submit_create" value="Create" />
        <input type="text"   name="phy_team_num_to_create" value="" size = "4" />
        Physical Teams.
    </td>
    <td><input type="submit" name="phy_team_submit_search" value="Search" /></td>
</tr>
</table>
</form>
<br />
<form method="post" action="<?php echo $this->link('phy_team_list'); ?>">
<table border="1">
<tr><th colspan="7">Physical Team Listing</th></tr>
<tr>
    <td>Year</td>
    <td>Season</td.
    <td>Team Key</td>
    <td>Name</td>
    <td>Colors</td>
    <td>Head Coach</td>
    <td>Delete</td>
</tr>
<?php foreach($this->phyTeams as $phyTeam) { $phyTeamId = $phyTeam->id; ?>
<tr>
    <td><?php echo $this->escape($phyTeam->year);           ?></td>
    <td><?php echo $this->escape($phyTeam->seasonTypeDesc); ?></td>
    <td><?php echo $this->href  ($this->escape($phyTeam->key),'phy_team_edit',$phyTeamId); ?></td>
    <td><?php echo $this->escape($phyTeam->name);   ?></td>
    <td><?php echo $this->escape($phyTeam->colors); ?></td>
    <td><?php echo $this->href  ($this->escape($phyTeam->coachHead->namex),'person_edit',$phyTeam->coachHead->personId); ?></td>
    <td><input type="checkbox" name = "phy_team_delete_ids[<?php echo $phyTeamId; ?>]" value="<?php echo $phyTeamId; ?>" /></td>
</tr>
<?php } ?>
<tr>
    <td colspan="6"></td>
    <td>
        <input type="checkbox" name="phy_team_confirm_delete" value="1" />
        <input type="submit"   name="phy_team_submit_delete"  value="Delete" />
    </td>
</tr>
</table>
</form>
