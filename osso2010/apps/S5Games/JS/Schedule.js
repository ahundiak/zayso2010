Ext.ns('Zayso.S5Games.Schedule');

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
    gridStore.load();

    var myCheckboxGroup = new Ext.form.CheckboxGroup
    ({
      id:         'myGroup',
      xtype:      'checkboxgroup',
      fieldLabel: 'Day',
      itemCls:    'x-check-group-alt',
      // Put all controls in a single column with width 100%
      columns:    3,
      width: 120,
      items:
      [
        {boxLabel: 'Fri', name: 'schedule-day-fri'},
        {boxLabel: 'Sat', name: 'schedule-day-sat', checked: true},
        {boxLabel: 'Sun', name: 'schedule-day-sun'}
      ]
    });
    myCheckboxGroup.on('change',function(group,checkeds)
    {
      var i;

      console.log("Days Changed " + checkeds.length);
      for(i = 0; i < checkeds.length; i++)
      {
        var checked = checkeds[i];
        console.log('Checked ' + checked.name)
      }
    });
    var gridTopToolbar = 
    {
      xtype       : 'toolbar',
    //layout      : 'form',
      items :
      [
        '-',
        {
          xtype : 'tbtext',
          text  : 'Days: '

        },
        myCheckboxGroup,
        '-'
      ]
    };
    // Now configure the grid itself
    var gridConfig =
    {
      id          : 's5games-schedule-grid',
      title       : 'Game and Referee Schedule 2009',
      autoHeight  : true,
    //width       : 500,
    //height      : 400,

      frame       : true,
      style       : 'margin: 5px;',

      store       : gridStore,
      columns     : columnModel,
      loadMask    : true,

      tbar: gridTopToolbar,

    //clicksToEdit: 2,
      viewConfig  : { forceFit : true }
    };
    // apply config and init
    Ext.apply(this, Ext.apply(this.initialConfig, gridConfig));

    Zayso.S5Games.Schedule.Grid.superclass.initComponent.apply(this);
  }
});
Ext.reg('Zayso.S5Games.Schedule.Grid', Zayso.S5Games.Schedule.Grid);