<?php
  $title = 'Eayso - Volunteers';

  // Additional files being tested by this action
  $jsFilesx = array
  (
  );
?>
<script type="text/javascript">

Ext.ns('Zayso');

function doit()
{
  Ext.QuickTips.init();
  Ext.Direct.addProvider(Zayso.Direct.API());
//console.log('Volunteer testing');


//var search = new Zayso.Eayso.Vols.Search();
//search.load();

//var grid   = new Zayso.S5Games.Schedule.Grid();
  var panel   = new Zayso.Eayso.Vols.Panel();

  var win = new Ext.Window
  ({
    height  : 600,
    width   : 999,
    border  : false,
    layout  : 'fit',
    items   : panel
  });
  win.show();
}
Ext.onReady(doit);
</script>