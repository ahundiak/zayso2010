<?php
  $html = $this->context->html;
?>
<h3>Welcome to Zayso</h3>
<p>The section 5 referee system blah blah blah</p>
<form id="schedule-guest" method="post" action="schedule-guest">
  <table class="entry"  style="width: 600px;">
    <tr><th colspan="3">Go directly to schedules without signing in</th></tr>
    <tr>
      <td>Select Region</td>
      <td>
        <select name="schedule_guest_org">
          <option value="0">Select your region</option>
          <?php echo $html->formOptions($this->orgPickList); ?>
        </select>
      </td>
      <td>
        <input type="submit" name="schedule_guest_submit" value="Go To Schedules" />
      </td>
    </tr>
  </table>
</form>
<p>
</p>
<?php echo $this->render('Action/Account/LoginForm.html.php'); ?>



