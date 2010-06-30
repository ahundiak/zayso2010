Ext.ns('Zayso.User.SignIn');

Zayso.User.SignIn.Form = Ext.extend(Ext.form.FormPanel, 
{
  initComponent: function()
  {
  
  // For the handlers
  var theForm = this;
  
  // The toolbar
  var formToolBar = 
  {
    xtype : 'toolbar',
    items : 
    [
      '->',
      ' ',
      {
        text : 'Sign In',
        handler : function () 
        { 
          // console.log('Sign in ');
          // console.log(values);
          
          theForm.getForm().submit
          ({
            success: function(form, action) 
            {
              // Ext.Msg.alert('Success', action.result.msg + ' ' + action.result.member_id);
              Zayso.app.user.load(action.result.member_id);
            },
            failure: function(form, action) 
            {
              switch (action.failureType) 
              {
                case Ext.form.Action.CLIENT_INVALID:
                  Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
                  break;
                case Ext.form.Action.CONNECT_FAILURE:
                  Ext.Msg.alert('Failure', 'Ajax communication failed');
                  break;
                case Ext.form.Action.SERVER_INVALID:
                  Ext.Msg.alert('Failure', action.result.msg);
              }
            }
          }); // End of submit
        }
      }
    ]
  };
  // The form
  var form = 
  {
    xtype: 'form',
    
    // Basic form - proxy
    api: {
      load:   Zayso.Direct.User_UserSignIn.load,
      submit: Zayso.Direct.User_UserSignIn.submit
    },
    paramsAsHash: true,
    
    width       : 300,
  //height      : 200,
    autoScroll  : false,
    style       : 'margin-top: 10px; ',
    bodyStyle   : 'padding: 6px',
    title       : 'User Sign In',
    frame       : true,
    
    labelWidth  : 75,
    defaultType : 'textfield',
    defaults : 
    {
      msgTarget : 'side',
      anchor    : '-20'
    },
    items : 
    [
      {
        name        : 'user_name',
        fieldLabel  : 'User Name',
        allowBlank  :  false,
        value       : 'referee',
        inputType   : 'text'
      },
      {
        name        : 'user_pass',
        fieldLabel  : 'Password',
        allowBlank  :  false,
        inputType   : 'password'
      }
    ],
    bbar: formToolBar
  };
  // apply config and init
  Ext.apply(this, Ext.apply(this.initialConfig, form));

  Zayso.User.SignIn.Form.superclass.initComponent.apply(this);

  } // initComponent
});