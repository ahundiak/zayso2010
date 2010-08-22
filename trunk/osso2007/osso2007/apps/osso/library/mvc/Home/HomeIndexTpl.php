<h3>Welcome</h3>
<p>
Welcome to the AYSO Section 5 game and referee scheduling system.<br />
Your browser must accept a cookie from this site to view the schedules.<br />
If you are a referee or coach then you will probably want to 
<?php echo $this->href('Create An Account','account_create') ?>. <br />
</p>
<br />
<hr />
<p>
You can view schedules without logging in or without having an account.<br />
Pick your region and press 'Browse Schedules'.
<form method="post" action="<?php echo $this->link('sched_div_list'); ?>"> 
<table border="1">
<tr>
    <td>
        <select name="browse_unit_id">
            <option value="0">Select Region</option>
            <?php echo $this->formOptions($this->unitPickList,$this->homeIndexData->unitId); ?>" />
        </select>
    </td>
    <td>
        <select name="browse_age_id">
            <option value="0">Select Age</option>
            <?php echo $this->formOptions($this->agePickList,$this->homeIndexData->ageId); ?>" />
        </select>
    </td>
    <td><input type="submit" name="browse_schedules" value="Browse Schedules" />
</tr>
</table>
</form>
<hr />
<?php if (!$this->context->user->isAuth) { ?>
<p>
If you already have an account then login here.
</p>
<?php echo $this->render($this->tplLogin); ?>
<?php } ?>
<?php if ($this->context->appServerName == 'local.osso2010.org' && 0) { ?>
<iframe src="http://zayso.rpxnow.com/openid/embed?token_url=http%3A%2F%2Flocal.osso2010.org%2Fosso2007%2Frpx.php"
        scrolling="no"  frameBorder="no"  allowtransparency="true"  style="width:400px;height:240px">
</iframe>
<?php } ?>