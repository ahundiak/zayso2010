<?php $item = $this->accountItem; ?>

<form method="post" action="<?php echo $this->link('account_edit'); ?>"> 
<table border="1">
<tr>
    <th colspan="2">
        <?php 
            if ($item->id) echo "Edit Account: {$item->user}";
            else           echo "Create New Account";
        ?>
    </th>
</tr>
<tr>
    <td>ID</td>
    <td><?php echo $item->id; ?></td>
    <input type="hidden" name="account_id" value="<?php echo $item->id; ?>" />
</tr>
<tr>
    <td>User</td>
    <td><input type="text" name="account_user" size="40" value="<?php echo $item->user; ?>" /></td>
</tr>
<tr>
    <td>Name</td>
    <td><input type="text" name="account_name" size="40" value="<?php echo $item->name; ?>" /></td>
</tr>
<tr>
    <td>Password 1</td>
    <td><input type="text" name="account_pass1" size="20" value="" /></td>
</tr>
<tr>
    <td>Password 2</td>
    <td><input type="text" name="account_pass2" size="20" value="" /></td>
</tr>
<tr>
    <td>Email</td>
    <td><input type="text" name="account_email" size="40" value="<?php echo $item->email; ?>" /></td>
</tr>
<tr>
    <td>Status</td>
    <td><input type="text" name="account_status" size="4" value="<?php echo $item->status; ?>" /></td>
</tr>
<tr>
    <td><?php echo $this->href('Account List','account_list'); ?></td>
    <td><?php echo $this->formUDC('account',$item->id); ?></td>
</tr>
</table>
</form>
<br />
<?php echo $this->render('MemberListTpl'); ?>
