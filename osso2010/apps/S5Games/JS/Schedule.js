Ext.ns('Zayso.S5Games.Schedule');

Zayso.S5Games.Schedule.Search = Ext.extend(Ext.form.FormPanel,
{
  initComponent: function()
  {
    var dayGroup = new Ext.form.CheckboxGroup
    ({
      fieldLabel: 'Days',
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
    var theForm = this;
    var searchButton =
    {
      xtype:   'button',
      type:    'submit',
      name:    'search',
      text:    'Search',
      tooltip: 'Press to update the schedule listing',
      handler: function(button)
      {
        console.log('Search pressed');
        var values = theForm.getForm().getValues();
        console.log(values);
        theForm.store.load({params: values});
      }
    };
    var col1 =
    {
      xtype: 'container',
      layout: 'form',
//      width: 350,
      //columnWidth: .5,
      items: [dayGroup,siteGroup,divGroup]
    };
    var col2 =
    {
      xtype: 'container',
      layout: 'form',
      //columnWidth: .4,
      items:
      [
        { xtype: 'textfield', width: 200, name : 'coaches',  fieldLabel : 'Coaches'  , tooltip: 'Used to filter teams'},
        { xtype: 'textfield', width: 200, name : 'referees', fieldLabel : 'Referees' },
        { xtype: 'textfield', width: 200, name : 'brackets', fieldLabel : 'Brackets' },
        searchButton
      ]
    };
    var formConfig =
    {
      id          : 's5games-schedule-search',
      autoHeight  : true,
//      width       : 650,
      //height      : 100,

      //frame       : true,
      style       : 'margin: 5px',
      labelWidth  : 40,

      layout: 'column',
      items: [col1,col2]
    };
    // apply config and init
    Ext.apply(this, Ext.apply(this.initialConfig, formConfig));

    Zayso.S5Games.Schedule.Search.superclass.initComponent.apply(this);

    //var values = this.getForm().getValues();
    //console.log(values);
  }
});
Ext.reg('Zayso.S5Games.Schedule.Search', Zayso.S5Games.Schedule.Search);

Zayso.S5Games.Schedule.Grid = Ext.extend(Ext.grid.GridPanel,
{
  initComponent: function()
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
    var gridStore = new Ext.data.DirectStore
    ({
      // These all come from Store
      storeId     : 's5games-schedule-grid-store',
    //baseParams  : { event_id : this.eventId },
      autoLoad    : false,
      autoSave    : false,

      remoteSort  : false,
    //sortInfo    : { field : 'position_id', direction : 'ASC'},

      writer      : writer,
      reader      : reader,
      proxy       : proxy
    });
    gridStore.on('exception',function()
    {
      // If the server elects to not update a record then an exception is tossed
      // console.log('DirectStore Exception Caught');
      // console.info(arguments);
    },this);

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
    // Create the search form and load the store
    var searchForm = new Zayso.S5Games.Schedule.Search({store: gridStore});

    gridStore.load();

    var gridTopToolbar = 
    {
      xtype       : 'toolbar',
      autoHeight  : true,
      items       : searchForm
    };
    // Now configure the grid itself
    var gridConfig =
    {
      id          : 's5games-schedule-grid',
      title       : 'Game and Referee Schedule 2009',
      autoHeight  : true,
    //width       : 500,
    //height      : 400,

    //frame       : true,
      style       : 'margin: 5px;',

      store       : gridStore,
      columns     : columnModel,
      loadMask    : true,

      tbar: gridTopToolbar

    //clicksToEdit: 2,
      // viewConfig  : { forceFit : true }
    };
    // apply config and init
    Ext.apply(this, Ext.apply(this.initialConfig, gridConfig));

    Zayso.S5Games.Schedule.Grid.superclass.initComponent.apply(this);
  }
});
Ext.reg('Zayso.S5Games.Schedule.Grid', Zayso.S5Games.Schedule.Grid);