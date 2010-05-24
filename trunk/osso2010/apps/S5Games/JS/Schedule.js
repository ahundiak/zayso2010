Ext.ns('Zayso.S5Games.Schedule');

Zayso.S5Games.Schedule.Panel = Ext.extend(Ext.Panel,
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
Ext.reg('Zayso.S5Games.Schedule.Panel', Zayso.S5Games.Schedule.Panel);

Zayso.S5Games.Schedule.Store = Ext.extend(Ext.data.DirectStore,
{
  constructor: function()
  {
    // Master grid store
    var recordFields =
    [
      { name : 'game_id',     mapping : 'game_id' },
      { name : 'game_num',    mapping : 'game_num'        },

      { name : 'date',        mapping : 'date'    },
      { name : 'time',        mapping : 'time' },
      { name : 'field',       mapping : 'field' },

      { name : 'div',         mapping : 'div'        },
      { name : 'team_home',   mapping : 'team_home'  },
      { name : 'team_away',   mapping : 'team_away'  }
    ];
    var writer = new Ext.data.JsonWriter
    ({
      encode: false,  // Very important, defaults to true
      writeAllFields : true
    });
    var reader = new Ext.data.JsonReader
    ({
      root            : 'records',
      idProperty      : 'game_id',
      totalProperty   : 'totalCount',
      successProperty : 'success',
      messageProperty : 'message',
      fields          : recordFields
    });
    var proxy = new Ext.data.DirectProxy
    ({
      api :
      {
        read:    Zayso.Direct.Schedule.read,
        create:  Zayso.Direct.Schedule.create,
        destroy: Zayso.Direct.Schedule.destroy,
        update:  Zayso.Direct.Schedule.update
      },
      paramsAsHash: true
    });
    var storeConfig =
    {
      // These all come from Store
      storeId     : 's5games-schedule-grid-store',
      autoLoad    : false,
      autoSave    : false,

      remoteSort  : false,
    //sortInfo    : { field : 'position_id', direction : 'ASC'},

      writer      : writer,
      reader      : reader,
      proxy       : proxy
    };
    Zayso.S5Games.Schedule.Store.superclass.constructor.call(this, storeConfig);
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

Zayso.S5Games.Schedule.Grid = Ext.extend(Ext.grid.GridPanel,
{
  initComponent: function()
  {
    // Master grid store
    var gridStore = new Zayso.S5Games.Schedule.Store();

    var columnModel =
    [
      {
        header    : 'Game',
        dataIndex : 'game_num',
        width     : 60
      },
      {
        header    : 'Date',
        dataIndex : 'date',
        width     : 100
      },
      {
        header    : 'Time',
        dataIndex : 'time',
        width     : 100
      },
      {
        header    : 'Field',
        dataIndex : 'field',
        width     : 100
      },
      {
        header    : 'Div',
        dataIndex : 'div',
        width     : 60
      },
      {
        header    : 'Home Team',
        dataIndex : 'team_home',
        width     : 150,
        renderer  : renderTeam
      },
      {
        header    : 'Away Team',
        dataIndex : 'team_away',
        width     : 150,
        renderer  : renderTeam
      }
    ];
    var renderTeam = function(value,metadata,record,rowIndex,colIndex,store)
    {
      return value;
    }
    // Now configure the grid itself
    var gridConfig =
    {
      id          : 's5games-schedule-grid',
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

    Zayso.S5Games.Schedule.Grid.superclass.initComponent.apply(this);
  }
});
Ext.reg('Zayso.S5Games.Schedule.Grid', Zayso.S5Games.Schedule.Grid);