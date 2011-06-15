<?php
  $search = $this->data->search;
?>
<form name="account_search" method="post" action="account-list" >

<table border="1" width="800">
<tr><th colspan="3">Account Search Form</th></tr>
<tr>
  <td>
    User Name:
    <input type="text" name="account_search_uname" size="20"
       value="<?php echo $this->escape($search->uname); ?>" />
  </td>
  <td>
    Last Name:
    <input type="text" name="account_search_lname" size="20"
       value="<?php echo $this->escape($search->lname); ?>" />
  </td>
  <td>
    AYSOID:
    <input type="text" name="account_search_aysoid" size="20"
       value="<?php echo $this->escape($search->aysoid); ?>" />
  </td>
</tr>
<tr>
  <td>.</td>
  <td>
    <select name="account_search_filter">
      <?php echo $this->formOptions($this->data->filterPickList,$search->filter); ?>
    </select>
  </td>
  <td align="right">
    <a href="account-list/csv">Spreadsheet</a>
    <input type="submit" name="account_search_submit" value="Search"/>
  </td>
</tr>
</table>
</form>
<br />
