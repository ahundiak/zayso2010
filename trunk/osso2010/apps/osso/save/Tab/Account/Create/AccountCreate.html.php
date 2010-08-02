<div>
  <form id="account-create-form" action="account.create.php" method="post" class="jqtransform">
    <div class="rowElem">
      <label for="user_name">User Namex</label>
      <input type="text" name="user_name" />
    </div>
    <div class="rowElem">
      <label for="user_pass1">Password</label>
      <input type="password" name="user_pass1" />
    </div>
    <div class="rowElem">
      <input type="submit" value="Create" />
    </div>
  </form>
</div>
<script type="text/javascript">
  
  $("form.jqtransform").jqTransform();

  $('#account-create-form').ajaxForm(
  {
    success: function(response)
    {
      alert(response.msg);
      //console.log(response.msg);
    },
    dataType: 'json'
  });
</script>
