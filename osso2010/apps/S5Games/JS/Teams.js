Ext.ns('Zayso.S5Games.Teams');

Zayso.S5Games.Teams.Panel = Ext.extend(Ext.Panel,
{
  initComponent: function()
  {
    var store  = new Zayso.S5Games.Schedule.Store();
    var search = new Zayso.S5Games.Schedule.Search();

    search.getForm().on('actioncomplete',function(form)
    {
      var values = form.getValues();
      store.load({params: values});
    })
    search.load();

    var grid = new Zayso.S5Games.Schedule.Grid({store: store});

    // Now configure the grid itself
    var panelConfig =
    {
      id          : 's5games-schedule-panel',
      title       : 'Game and Referee Schedule 2009',
      width       : 500,
      height      : 400,

      latout      : 'vbox',
      frame       : true,
      style       : 'margin: 5px;',

      store       : store,
      search      : search,
      grid        : grid,

      items       : [search,grid]
    };

    // apply config and init
    Ext.apply(this, Ext.apply(this.initialConfig, panelConfig));

    Zayso.S5Games.Schedule.Panel.superclass.initComponent.apply(this);
  }
});
Ext.reg('Zayso.S5Games.Teams.Panel', Zayso.S5Games.Teams.Panel);

Zayso.S5Games.Teams.Store = Ext.extend(Ext.data.DirectStore,
{
  constructor: function()
  {
    // Master grid store
    var recordFields =
    [
      { name : 'id',      mapping : 'id'     },
      { name : 'region',  mapping : 'region' },

      { name : 'div',     mapping : 'div'    },
      { name : 'name',    mapping : 'name'   },
      { name : 'colors',  mapping : 'colors' },

      { name : 'status',  mapping : 'status' },
      { name : 'notes',   mapping : 'notes'  }
    ];
    var writer = new Ext.data.JsonWriter
    ({
      encode: false,  // Very important, defaults to true
      writeAllFields : true
    });
    var reader = new Ext.data.JsonReader
    ({
      root            : 'records',
      idProperty      : 'id',
      totalProperty   : 'totalCount',
      successProperty : 'success',
      messageProperty : 'message',
      fields          : recordFields
    });
    var proxy = new Ext.data.DirectProxy
    ({
      api :
      {
        read:    Zayso.Direct.Teams.read,
        create:  Zayso.Direct.Teams.create,
        destroy: Zayso.Direct.Teams.destroy,
        update:  Zayso.Direct.Teams.update
      },
      paramsAsHash: true
    });
    var storeConfig =
    {
      // These all come from Store
      storeId     : 's5games-teams-grid-store',
      autoLoad    : false,
      autoSave    : false,

      remoteSort  : false,
    //sortInfo    : { field : 'position_id', direction : 'ASC'},

      writer      : writer,
      reader      : reader,
      proxy       : proxy
    };
    Zayso.S5Games.Teams.Store.superclass.constructor.call(this, storeConfig);
  }
});

Zayso.S5Games.Schedule.Search = Ext.extend(Ext.form.FormPanel,
{
  initComponent: function()
  {
    var dayGroup = new Ext.form.CheckboxGroup
    ({
      fieldLabel: 'Days',
      name      : 'days',
    //itemCls:    'x-check-group-alt',

      columns:    3,
      width: 120,
      items:
      [
        {boxLabel: 'Fri', name: 'day-fri', checked: true},
        {boxLabel: 'Sat', name: 'day-sat', checked: true},
        {boxLabel: 'Sun', name: 'day-sun', checked: true}
      ]
    });
    var siteGroup = new Ext.form.CheckboxGroup
    ({
      fieldLabel: 'Sites',
      name      : 'sites',
      columns:    2,
      width: 160,
      items:
      [
        {boxLabel: 'John Hunt', name: 'site-john_hunt', checked: true},
        {boxLabel: 'Merrimack', name: 'site-merrimack', checked: true}
      ]
    });
    var divGroup = new Ext.form.CheckboxGroup
    ({
      fieldLabel: 'Divs',
      name      : 'divs',
      columns:    5,
      width: 250,
      items:
      [
        {boxLabel: 'U10B', name: 'div-u10b', checked: true},
        {boxLabel: 'U12B', name: 'div-u12b', checked: true},
        {boxLabel: 'U14B', name: 'div-u14b', checked: true},
        {boxLabel: 'U16B', name: 'div-u16b', checked: true},
        {boxLabel: 'U19B', name: 'div-u19b', checked: true},

        {boxLabel: 'U10G', name: 'div-u10g', checked: true},
        {boxLabel: 'U12G', name: 'div-u12g', checked: true},
        {boxLabel: 'U14G', name: 'div-u14g', checked: true},
        {boxLabel: 'U16G', name: 'div-u16g', checked: true},
        {boxLabel: 'U19G', name: 'div-u19g', checked: true}
      ]
    });
    var searchButton =
    {
      xtype:   'button',
      type:    'submit',
      name:    'search',
      text:    'Search',
      scope:   this,
      tooltip: 'Press to update the schedule listing',
      style:    'padding-right: 10px',
      handler: function(button)
      {
        // Pretend that the acion was completed
        var form = this.getForm();
        form.fireEvent('actioncomplete',form);
      }
    };
    var excelButton =
    {
      xtype:   'button',
      type:    'submit',
      name:    'excel',
      text:    'Generate Spreadsheet',
      scope:   this,
      tooltip: 'Press to generate printable report',
      style:    'padding-right: 10px',
      handler: function(button)
      {
        // Pretend that the acion was completed
        console.log('Excel');
        var form = this.getForm();
        form.fireEvent('actioncomplete',form);
      }
    };
    var sortBy =
    {
      xtype       : 'combo',
      name        : 'sort_by',
      hiddenName  : 'sort_by',
      fieldLabel  : 'Sort By',

      triggerAction : 'all',
      lazyRender    : true,

      mode  : 'local',
      store : new Ext.data.ArrayStore
      ({
        id: 0,
        fields: [
            'id',
            'displayText'
        ],
        data: [[1, 'Date,Time,Field'], [2, 'Date,Field,Time']]
      }),
      valueField:   'id',
      displayField: 'displayText'
    };
    var col1 =
    {
      xtype: 'panel',
      layout: 'form',
      frame: true,
      labelWidth: 30,
//      width: 350,
      items: [dayGroup,siteGroup,divGroup]
    };
    var col2 =
    {
      xtype: 'panel',
      layout: 'form',
      frame: true,
      labelWidth: 60,
      items:
      [
        sortBy,
        { xtype: 'textfield', width: 200, name : 'coaches',  fieldLabel : 'Coaches'  , tooltip: 'Used to filter teams'},
        { xtype: 'textfield', width: 200, name : 'referees', fieldLabel : 'Referees' },
        { xtype: 'textfield', width: 200, name : 'brackets', fieldLabel : 'Brackets' },
        { xtype: 'container', layout: 'hbox', items: [excelButton,searchButton] }
      ]
    };
    var col3 =
    {
      xtype: 'panel',
      layout: 'form',
      width: 50
    };
    var formConfig =
    {
      id          : 's5games-schedule-search',
      autoHeight  : true,
//      width       : 650,
      //height      : 100,

      api:
      {
        load:   Zayso.Direct.Schedule.load,
        submit: Zayso.Direct.Schedule.submit
      },
      paramsAsHash: true,

      frame       : true,
      style       : 'margin: 5px',

      layout: 'hbox',
      items: [col1,col3,col2]
    };
    // apply config and init
    Ext.apply(this, Ext.apply(this.initialConfig, formConfig));

    Zayso.S5Games.Schedule.Search.superclass.initComponent.apply(this);
  }
});
Ext.reg('Zayso.S5Games.Schedule.Search', Zayso.S5Games.Schedule.Search);

Zayso.S5Games.Teams.Grid = Ext.extend(Ext.grid.EditorGridPanel,
{
  initComponent: function()
  {
    var columnModel =
    [
      {
        header    : 'Team ID',
        dataIndex : 'id',
        width     : 60
      },
      {
        header    : 'Region',
        dataIndex : 'region',
        width     : 60
      },
      {
        header    : 'Division',
        dataIndex : 'div',
        width     : 100
      },
      {
        header    : 'Name',
        dataIndex : 'name',
        width     : 100
      },
      {
        header    : 'Colors',
        dataIndex : 'colors',
        width     : 100
      },
      {
        header    : 'Status',
        dataIndex : 'status',
        width     : 100
      }
    ];
    // Now configure the grid itself
    var gridConfig =
    {
      id          : 's5games-teams-grid',
      autoHeight  : true,
    //width       : 500,
    //height      : 400,

      frame       : true,
      style       : 'margin: 5px;',

      columns     : columnModel,
      loadMask    : true

    };
    // apply config and init
    Ext.apply(this, Ext.apply(this.initialConfig, gridConfig));

    Zayso.S5Games.Teams.Grid.superclass.initComponent.apply(this);
  }
});
Ext.reg('Zayso.S5Games.Teams.Grid', Zayso.S5Games.Teams.Grid);