<?php 
    $phyTeam   = $this->phyTeam;
    $phyTeamId = $phyTeam->id;
?>
<form method="post" action="<?php echo $this->link('phy_team_edit'); ?>"> 
<table border="1">
<tr><th colspan="2">Edit Physical Team</th></tr>
<tr>
    <td>ID</td>
    <td><?php echo $phyTeamId; ?></td>
    <input type="hidden" name="phy_team_id" value="<?php echo $phyTeamId; ?>" />
</tr>
<tr>
    <td>Year</td>
    <td><?php echo $phyTeam->year; ?></td>
</tr>
<tr>
    <td>Season</td>
    <td><?php echo $phyTeam->seasonTypeDesc; ?></td>
</tr>
<tr>
    <td>Region</td>
    <td><?php echo $phyTeam->unitDesc; ?></td>
</tr>
<tr>
    <td>Division</td>
    <td><?php echo $phyTeam->divisionDesc; ?></td>
</tr>
<tr>
    <td>Seq Number</td>
    <td><input type="text" name="phy_team_seq_num" value="<?php echo $phyTeam->divisionSeqNum; ?>" /></td>
</tr>
<tr>
    <td>Team Name</td>
    <td><input type="text" name="phy_team_name" value="<?php echo $phyTeam->name; ?>" /></td>
</tr>
<tr>
    <td>Team Colors</td>
    <td><input type="text" name="phy_team_colors" value="<?php echo $phyTeam->colors; ?>" /></td>
</tr>
<?php foreach($this->phyTeamPersons as $item) { $itemId = $item->id; ?>
<tr>
    <input type="hidden" name="phy_team_person_ids[<?php echo $itemId; ?>]" value="<?php echo $itemId; ?>" />
    <input type="hidden" name="phy_team_person_vol_type_ids[<?php echo $itemId; ?>]" value="<?php echo $item->volTypeId; ?>" />
    <td><?php echo $item->volTypeDesc; ?></td>
    <td>
        <select name="phy_team_person_person_ids[<?php echo $itemId; ?>]">
            <option value="0">Not Assigned</option>
            <?php echo $this->formOptions($item->personPickList,$item->personId); ?>
        </select>
    </td>
</tr>
<?php } ?>
<tr>
    <td><?php echo $this->href('Team List','phy_team_list'); ?></td>
    <td><input type="submit" name="phy_team_submit_update" value="Update" />
</tr>
</table>
</form>

