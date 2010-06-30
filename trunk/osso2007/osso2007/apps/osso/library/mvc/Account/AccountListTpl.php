<form method="post" action="<?php echo $this->link('account_list'); ?>">
<table border="1">
<tr><th colspan="4">Search Accounts</th></tr>
<tr>
    <td>User:  <input type="text" name="account_user"  size="10" value="<?php echo $this->escape($this->accountUser);  ?>" /></td>
    <td>Name:  <input type="text" name="account_name"  size="10" value="<?php echo $this->escape($this->accountName);  ?>" /></td>
    <td>Email: <input type="text" name="account_email" size="10" value="<?php echo $this->escape($this->accountEmail); ?>" /></td>
    <td><input type="submit" name="account_submit_search" value="Search" />
</tr>
</table>
</form>
<br />
<table border="1">
<tr><th colspan="5">List Accounts</th></tr>
<tr>
    <td>Edit Account</td>
    <td>User</td>
    <td>Name</td>
    <td>Email</td>
    <td>Status</td>
</tr>
<?php foreach($this->accounts as $account) { ?>
<tr>
    <td><?php echo $this->href('Edit Account','account_edit',$account->id); ?></td>
    <td><?php echo $this->escape($account->user);   ?></td>
    <td><?php echo $this->escape($account->name);   ?></td>
    <td><?php echo $this->escape($account->email);  ?></td>
    <td><?php echo $this->escape($account->status); ?></td>
</tr>
<?php } ?>
</table>
