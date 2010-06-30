<?php 
  $title = 'Zayso - Referee Signup';
  
  // Additional files being tested by this action
  $jsFilesx = array
  (
    'Referee/RefereeSignup'
  );
?>
<script type="text/javascript">

Ext.ns('Zayso');

function doit()
{
  Ext.Direct.addProvider(Zayso.Direct.API());

  Zayso.app = new Zayso.App();
  
  Zayso.app.execute();
  
  var user = Zayso.app.user;
  
  // Really need to wait for the load
  user.on('load', function()
  {
    console.log('User ' + user.member.id + ' ' + user.member.name);
  });
  user.load(31);
  
  // This should listen to the user info store
  var grid = new Zayso.Referee.Signup.Grid
  ({
    eventId: 7789
  });
  
  // Why does this not work?
  //var gridx = Ext.getCmp('referee-signup-grid');
  //console.log(gridx);
  
  var win = new Ext.Window
  ({
    height  : 350,
    width   : 550,
    border  : false,
    layout  : 'fit',
    items   : grid
    /*
    [
      { 
        xtype: 'Zayso.Referee.Signup.Grid' 
      }
    ]*/
  });
  //win.show();
  //console.log('Calling getCmp');
  //var grid = Ext.getCmp('referee-signup-grid');
  //console.log(grid);
  
  //grid.setEventId(7789);
  
  win.show();
}
Ext.onReady(doit);
</script>
 