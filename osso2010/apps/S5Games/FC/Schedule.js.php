<?php
/* ----------------------------------------------
 * Just a little test to see how store filtering works
 * Basically calling filter by will run through each record and apply any filtering criteria
 * getCount and getAt will then use the filtered/selected records
 *
 * So I can pull in all 200 game records then filter locally without hitting the server again
 */
  $title = 'S5Games - Schedule';

  // Additional files being tested by this action
  $jsFilesx = array
  (
    'Schedule'
  );
?>
<script type="text/javascript">

Ext.ns('Zayso');

function doit()
{
  Ext.QuickTips.init();
  Ext.Direct.addProvider(Zayso.Direct.API());
  console.log('Schedule testing');


//var search = new Zayso.S5Games.Schedule.Search();
//search.load();

//var grid   = new Zayso.S5Games.Schedule.Grid();
  var panel   = new Zayso.S5Games.Schedule.Panel();

  var win = new Ext.Window
  ({
    height  : 350,
    width   : 700,
    border  : false,
    layout  : 'fit',
    items   : panel
  });
  win.show();
  
}
Ext.onReady(doit);
</script>