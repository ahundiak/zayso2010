<?php 
  $title = 'Zayso - User Sign In';
  
  // Additional files being tested by this action
  $jsFilesx = array
  (
    'User/UserSignIn'
  );
?>
<script type="text/javascript">

Ext.ns('Zayso');
/*
Ext.override(Ext.form.Action.DirectSubmit,
{
      run : function(){
        var o = this.options;
        if(o.clientValidation === false || this.form.isValid()){
            // tag on any additional params to be posted in the
            // form scope
            this.success.params = this.getParams();
            
            //this.form.api.submit(this.form.el.dom, this.success, this);
            this.form.api.submit(this.form.getValues(), this.success, this);
            
        }else if (o.clientValidation !== false){ // client validation failed
            this.failureType = Ext.form.Action.CLIENT_INVALID;
            this.form.afterAction(this, false);
        }
    }  
});
*/
function doit()
{
  Ext.QuickTips.init();
  Ext.Direct.addProvider(Zayso.Direct.API());
  
  form = new Zayso.User.SignIn.Form(); 
  form.load();
  
  // And show it
  var win = new Ext.Window
  ({
    width   : 300,
    height  : 150,
    border  : false,
    layout  : 'fit',
    items   : form
  });
  win.show();
}
Ext.onReady(doit);

function appStart()
{
  Zayso.app = new Zayso.App();
  
  Zayso.app.execute();
  
  var user = Zayso.app.user;
  
  // Really need to wait for the load
  user.on('load', function()
  {
    console.log('User ' + user.member.id + ' ' + user.member.name);
  });
  user.load(31);
}
</script>
 