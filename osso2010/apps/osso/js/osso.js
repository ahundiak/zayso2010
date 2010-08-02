// osso namespace
window.osso = {};

osso.updateMenu = function()
{
  // Get latest user information
  $.ajax(
  {
    cache:    false,
    dataType: 'json',
    url:      'actionx/user-menu',
    success: function(result)
    {
      var items = result.items;
      var tabs$ = $('#tabs');
      tabs$.tabs('destroy');
      tabs$.tabs(
      { cache: true,
        ajaxOptions: { cache: false }
      });
      for(i = 0; i < items.length; i++)
      {
        var item = items[i];
        tabs$.tabs('add',item.url,item.label);
      }
      tabs$.tabs('select',0);
    }
  });
}
osso.updateBanner = function()
{
  // Get latest user information
  $.ajax(
  {
    cache:    false,
    dataType: 'json',
    url:      'actionx/user-info',
    success: function(result)
    {
      // alert(result.user.info1);
      var tpl =
        '<span style="margin-left: 5px;">' + result.user.info1 + '</span><br /> ' +
        '<span style="margin-left: 5px;">' + result.user.info2 + '</span>';
      $('#layout-banner-user-info').html(tpl);
    }
  });
}
