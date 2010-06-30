/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */

Ext.onReady(function()
{
  Ext.QuickTips.init();

  var msg = function(title, msg)
  {
    Ext.Msg.show
    ({
      title:    title,
      msg:      msg,
      minWidth: 200,
      modal:    true,
      icon:     Ext.Msg.INFO,
      buttons:  Ext.Msg.OK
    });
  };

  var fp = new Ext.FormPanel
  ({
    renderTo:   'fi-form',
    fileUpload: true,
    width:      500,
    frame:      true,
    title:      'Referee Availability Form',
    autoHeight: true,
    bodyStyle: 'padding: 10px 10px 0 10px;',
    labelWidth: 50,
    defaults:
    {
      anchor:     '95%',
      allowBlank: false,
      msgTarget:  'side'
    },
    items: 
    [
    /*{
      name:  'name',
      value: 'xxx',
      xtype: 'textfield',
      fieldLabel: 'File'
    },*/
    {
      xtype:      'fileuploadfield',
      id:         'form-file',
      emptyText:  'Select referee availability csv file',
      fieldLabel: 'Data',
      name:       'data-path',
      buttonText: 'Browse',
      buttonCfg:
      {
        iconCls: 'upload-icon'
      }
    }],
    buttons:
    [{
      text: 'Upload',
      handler: function()
      {
        if(fp.getForm().isValid())
        {
          fp.getForm().submit
          ({
            url: 'ref-avail.php',
	    waitMsg: 'Uploading your data...',
	    success: function(fp, o)
            {
              msg('Success', 'Processed file ' + o.result.orgName + ' ' + o.result.tmpName + ' on the server');
              var task = new Ext.util.DelayedTask(function()
              {
                window.open('ref-availx.php');
              });
              task.delay(1000);
	    }
	  });
        }
      }
    },
    {
      text: 'Reset',
      handler: function()
      {
        fp.getForm().reset();
      }
    }]  // Buttons
  });  // fp =
});  // Ext.onReady
