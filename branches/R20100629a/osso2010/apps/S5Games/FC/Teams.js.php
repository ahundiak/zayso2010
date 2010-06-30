<?php
  $title = 'S5Games - Teams';

  // Additional files being tested by this action
  $jsFilesx = array
  (
    'Teams'
  );
?>
<script type="text/javascript">

Ext.ns('Zayso');

function doit()
{
  Ext.QuickTips.init();
  Ext.Direct.addProvider(Zayso.Direct.API());
  console.log('Teams testing');

  var store = new Zayso.S5Games.Teams.Store();
  store.load();
  var grid  = new Zayso.S5Games.Teams.Grid({store: store});

//var panel   = new Zayso.S5Games.Schedule.Panel();

  var win = new Ext.Window
  ({
    height  : 350,
    width   : 700,
    border  : false,
    layout  : 'fit',
    items   : grid
  });
  win.show();
  
}
Ext.onReady(doit);
</script>