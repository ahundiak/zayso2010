<h3>Administrative Commands</h3>
<table border="1">
<tr>
    <td>Search for and edit volunteers</td>
    <td><?php echo $this->href('People','person_list'); ?></td>
</tr>
<tr>
    <td>Create and edit physical teams</td>
    <td><?php echo $this->href('Phy Teams','phy_team_list'); ?></td>
</tr>
<tr>
    <td>Create and edit games, scrimmages and practices</td>
    <td><?php echo $this->href('Events','event_edit'); ?></td>
</tr>
<tr>
    <td>Create and edit schedule teams</td>
    <td><?php echo $this->href('Sch Teams','sch_team_list'); ?></td>
</tr>
<tr>
    <td>Create and edit soccer fields and sites</td>
    <td><?php echo $this->href('Fields','field_site_list'); ?></td>
</tr>

<tr>
    <td>Create and edit regions and soccer clubs</td>
    <td><?php echo $this->href('Organizations','unit_list'); ?></td>
</tr>
<tr>
    <td>Division Schedules</td>
    <td><?php echo $this->href('Division Schedules','sched_div_list'); ?></td>
</tr>
<tr>
    <td>Referee Schedules</td>
    <td><?php echo $this->href('Referee Schedules','sched_ref_list'); ?></td>
</tr>
<tr>
    <td>Import</td>
    <td><?php echo $this->href('Import Information','import_proc'); ?></td>
</tr>
<tr>
    <td>Reports</td>
    <td><?php echo $this->href('Generate Reports','report_proc'); ?></td>
</tr>
<tr>
    <td>Account Management</td>
    <td><?php echo $this->href('Account List','account_list'); ?></td>
</tr>
</table>