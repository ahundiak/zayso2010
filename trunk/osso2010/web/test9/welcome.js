$(function()
{
  var tabId = '#tabs';
  var accountCreateIndex = -1;

  $('#account-create-link').click(function()
  {
    if (accountCreateIndex < 0)
    {
      $(tabId).tabs('add','account.create.html','Create Account');
      accountCreateIndex = $(tabId).tabs('length') - 1;
    }
    $(tabId).tabs('select',accountCreateIndex);

    return false;
  });
}); 

