<table border="1">
<tr><th colspan="7">List Account Members</th></tr>
<tr>
    <td>Edit</td>
    <td>Region</td>
    <td>Account Name</td>
    <td>Member Name</td>
    <td>AYSO Name</td>
    <td>Status</td>
    <td>Level</td>
</tr>
<?php foreach($this->members as $member) { ?>
<tr>
    <td><?php echo $this->href('Edit','member_edit',$member->id); ?></td>
    <td><?php echo $this->escape($member->unitDesc);   ?></td>
    <td><?php echo $this->escape($member->account->name); ?></td>
    <td><?php echo $this->escape($member->name);       ?></td>
    <td><?php echo $this->escape($member->personName); ?></td>
    <td><?php echo $this->escape($member->status);     ?></td>
    <td><?php echo $this->escape($member->level);      ?></td>
</tr>
<?php } ?>
<tr>
    <td colspan="7"><?php echo $this->href('Add new member to account','member_edit',0); ?></td>
</tr>
</table>

