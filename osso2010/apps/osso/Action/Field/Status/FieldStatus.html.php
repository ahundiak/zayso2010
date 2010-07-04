<form id="person-edit-form" method="post" action="?la=person-edit">
  <table border ="1">
    <tr><th colspan="4">Edit Person</th></tr>
    <?php foreach($this->rows as $row) { echo $this->renderRow($row); } ?>
    <tr>
      <td>
        <input type="submit"   name="person_edit_submit_delete" value="Delete" />
        <input type="checkbox" name="person_edit_confirm_delete" />
      </td>
      <td>
        <input type="submit" name="person_edit_submit_copy" value="Move Checked Left <<" />
      </td>
      <td></td>
      <td align="right">
        <select name="person_edit_select_process">
          <option>Update Person</option>
          <option>Verify eAYSO ID</option>
          <option>Email Person</option>
          <option>Delete Person</option>
      </select>
        <input type="submit" name="person_edit_submit_process" value="Process Person" />
      </td>
    </tr>
  </table>
</form>
