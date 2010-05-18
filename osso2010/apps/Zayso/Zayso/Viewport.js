Ext.ns('Zayso');

Zayso.Viewport = Ext.extend(Ext.Viewport, 
{
  initComponent: function()
  {
    this.northPanel  = new Zayso.Viewport.North.Panel ();
    this.centerPanel = new Zayso.Viewport.Center.Panel();
    
    var config =
    {
      layout: 'border',

      items: 
      [
        this.northPanel,
        this.centerPanel
      ]
    };
    // apply config
    Ext.apply(this, Ext.apply(this.initialConfig, config));

    Zayso.Viewport.superclass.initComponent.apply(this);
  }
});

/* ----------------------------------------------------
 * Viewport North Panel
 */
Ext.ns('Zayso.Viewport.North');

Zayso.Viewport.North.Panel = Ext.extend(Ext.Panel, 
{
  initComponent: function()
  {
    this.topToolBar = new Zayso.Viewport.North.TopToolBar();
    
    var config =
    {
      region: 'north',
	    height: 50,
	    // layout: 'fit',
      tbar:  this.topToolBar,
      html: 'The North Panel'
    };
    // apply config
    Ext.apply(this, Ext.apply(this.initialConfig, config));

    Zayso.Viewport.North.Panel.superclass.initComponent.apply(this);
  }
});

/* ----------------------------------------------------
 * Viewport North Panel Top Toolbar
 */
Zayso.Viewport.North.TopToolBar = Ext.extend(Ext.Toolbar, 
{
  initComponent: function()
  {
    this.userNameTextItem = new Ext.Toolbar.TextItem({text: 'User Name'});
    
    var config =
    {
      //width:  600,
      height: 25,
      items: [
      {
        xtype: 'button', // default for Toolbars, same as 'tbbutton'
        text:  'Button',
        handler: function(b,e)
        {
            console.log('Button pressed ' + app.userStore.getTotalCount());
            app.userStore.on('load',
            function(store)
            {
              console.log('Button Loaded ' + store.getTotalCount());
            },
            this);
        }
      },{
        xtype: 'splitbutton', // same as 'tbsplitbutton'
        text: 'Split Button'
      },
        // begin using the right-justified button container
        '->',
        this.userNameTextItem,
        '-',
      {
        xtype: 'button',
        text:  'Preferences'
      },{
        xtype: 'tbseparator'
      },{
        xtype: 'button',
        text:  'Help'
      },{
        xtype: 'tbseparator'
      },{
        xtype: 'button',
        text:  'Logout'
      }]
    };

    // apply config
    Ext.apply(this, Ext.apply(this.initialConfig, config));

    Zayso.Viewport.North.TopToolBar.superclass.initComponent.apply(this);
    
    // Update the user information
    var user = Zayso.app.user;
    
    this.updateUserInfo(user);

    user.on('load',this.updateUserInfo,this);
  },
  updateUserInfo: function(user)
  {
    this.userNameTextItem.setText(user.member.name);
  }
});

/* ----------------------------------------------------
 * Viewport Center Panel
 */
Ext.ns('Zayso.Viewport.Center');

Zayso.Viewport.Center.Panel = Ext.extend(Ext.TabPanel, 
{
  initComponent: function()
  {
    var config =
    {
      region    : 'center',
      activeTab : 3,
      items: [
      {
        xtype:  'panel',
        title:  'Home',
        html:   'Home Panel'
      },{
        xtype:  'panel',
        title:  'My Schedule',
        html:   'My Schedule Panel'
      },{
        xtype:  'panel',
        title:  'Team Schedules',
        html:   'Team Schedules Panel'
      },{
        //xtype:  'Zayso.Referee.Schedule.Panel',
        title:  'Referee Schedule'
      },{
        xtype:  'panel',
        title:  'Admin',
        html:   'Admin'
      }]
    };
    // apply config
    Ext.apply(this, Ext.apply(this.initialConfig, config));

    Zayso.Viewport.Center.Panel.superclass.initComponent.apply(this);
  }
});
