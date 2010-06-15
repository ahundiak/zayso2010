Ext.ns('Zayso.Eayso.Vols');

Zayso.Eayso.Vols.Panel = Ext.extend(Ext.Panel,
{
  initComponent: function()
  {
    var store  = new Zayso.Eayso.Vols.Store();
    var search = new Zayso.Eayso.Vols.Search();

    search.getForm().on('actioncomplete',function(form)
    {
      var values = form.getValues();
      store.load({params: values});
    })
    search.load();

    var grid = new Zayso.Eayso.Vols.Grid({store: store});

    // Now configure the grid itself
    var panelConfig =
    {
      id          : 'eayso-vols-panel',
      title       : 'Eayso Volunteers',
      //width       : 200,
      //height      : 100,
      //autoHeight  : true,

      //layout      : 'vbox',
      frame       : true,
      style       : 'margin: 5px;',

      store       : store,
      search      : search,
      grid        : grid,

      items       : [search,grid]
    };

    // apply config and init
    Ext.apply(this, Ext.apply(this.initialConfig, panelConfig));

    Zayso.Eayso.Vols.Panel.superclass.initComponent.apply(this);
  }
});
Ext.reg('Zayso.Eayso.Vols.Panel', Zayso.Eayso.Vols.Panel);

Zayso.Eayso.Vols.Store = Ext.extend(Ext.data.DirectStore,
{
  constructor: function()
  {
    // Master grid store
    var recordFields =
    [
      { name : 'aysoid',        mapping : 'aysoid'  },
      { name : 'region',        mapping : 'region'  },
      { name : 'lname',         mapping : 'lname'   },
      { name : 'fname',         mapping : 'fname'   },
      { name : 'nname',         mapping : 'nname'   },
      { name : 'gender',        mapping : 'gender'  },
      { name : 'dob',           mapping : 'dob'     },
      { name : 'email',         mapping : 'email'   },
      { name : 'phone_home',    mapping : 'phone_home'},
      { name : 'phone_work',    mapping : 'phone_work'},
      { name : 'phone_cell',    mapping : 'phone_cell'},
      { name : 'certs',         mapping : 'certs'     },
      { name : 'mem_year',      mapping : 'mem_year'  }
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
        read:    Zayso.Direct.Eayso.Vols_Search.read,
        create:  Zayso.Direct.Eayso.Vols_Search.create,
        destroy: Zayso.Direct.Eayso.Vols_Search.destroy,
        update:  Zayso.Direct.Eayso.Vols_Search.update
      },
      paramsAsHash: true
    });
    var storeConfig =
    {
      // These all come from Store
      storeId     : 'eayso-vols-grid-store',
      autoLoad    : false,
      autoSave    : false,

      remoteSort  : false,
    //sortInfo    : { field : 'position_id', direction : 'ASC'},

      writer      : writer,
      reader      : reader,
      proxy       : proxy
    };
    Zayso.Eayso.Vols.Store.superclass.constructor.call(this, storeConfig);
  }
});

Zayso.Eayso.Vols.Search = Ext.extend(Ext.form.FormPanel,
{
  initComponent: function()
  {
    var searchAysoid =
    {
      xtype:  'panel',
      layout: 'form',
      labelWidth: 50,
      frame: true,
      items: { xtype: 'textfield', width: 80, name : 'aysoid',  fieldLabel : 'AYSOID'}
    };
    var searchRegion =
    {
      xtype:  'panel',
      layout: 'form',
      labelWidth: 45,
      frame: true,
      items: { xtype: 'textfield', width: 40, name : 'region',  fieldLabel : 'Region'}
    };
    var searchLastName =
    {
      xtype:  'panel',
      layout: 'form',
      labelWidth: 65,
      frame: true,
      items: { xtype: 'textfield', width: 150, name : 'lname',  fieldLabel : 'Last Name'}
    };
    var searchFirstName =
    {
      xtype:  'panel',
      layout: 'form',
      labelWidth: 65,
      frame: true,
      items: { xtype: 'textfield', width: 100, name : 'fname',  fieldLabel : 'First Name'}
    };
    var searchButton =
     {
      xtype:   'button',
      type:    'submit',
      name:    'search',
      text:    'Search',
      frame:   true,
      scope:   this,
      tooltip: 'Press to update the schedule listing',
      style:    'padding-left: 10px; padding-top: 8px;',
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
    var formConfig =
    {
      id          : 'eayso-vols-search',
      autoHeight  : true,
//      width       : 650,
      //height      : 100,

      api:
      {
        load:   Zayso.Direct.Eayso.Vols_Search.load,
        submit: Zayso.Direct.Eayso.Vols_Search.submit
      },
      paramsAsHash: true,

      frame       : true,
      style       : 'margin: 5px',

      layout: 'hbox',
      items: [searchAysoid, searchRegion, searchLastName, searchFirstName, searchButton]
    };
    // apply config and init
    Ext.apply(this, Ext.apply(this.initialConfig, formConfig));

    Zayso.Eayso.Vols.Search.superclass.initComponent.apply(this);
  }
});
Ext.reg('Zayso.Eayso.Vols.Search', Zayso.Eayso.Vols.Search);

Zayso.Eayso.Vols.Grid = Ext.extend(Ext.grid.EditorGridPanel,
{
  initComponent: function()
  {
    var editor  = new Ext.form.TextField();
    var editor2 = new Ext.form.TextArea();

    var renderCerts = function(value,metadata,record,rowIndex,colIndex,store)
    {
      var html = '';
      var i;
      for(i = 0; i < value.length; i++)
      {
        var item = value[i];
        if (html) html += '<br />';
        html += item.cert_desc + ' ' + item.cert_date;
      }
      return html;
    }
    var renderGender = function(value,metadata,record,rowIndex,colIndex,store)
    {
      var html = record.get('gender') + record.get('dob').substring(0,4);
      return html;
    }
    var formatPhone = function(phone)
    {
      if (!phone) return null;
      var phonex = phone.substring(0,3) + '.' + phone.substring(3,6) + '.' + phone.substring(6,10);

      return phonex;
    }
    var renderContact = function(value,metadata,record,rowIndex,colIndex,store)
    {
      var items = [];
      if (record.get('email'))      items.push(       record.get('email'));
      if (record.get('phone_home')) items.push('H ' + formatPhone(record.get('phone_home')));
      if (record.get('phone_work')) items.push('W ' + formatPhone(record.get('phone_work')));
      if (record.get('phone_cell')) items.push('C ' + formatPhone(record.get('phone_cell')));

      var html = '';
      var i;
      for(i = 0; i < items.length; i++)
      {
        var item = items[i];
        if (html) html += '<br />';
        html += item;
      }
      return html;

      return html;
    }

    var columnModel =
    [
      {
        header    : 'AYSOID',
        dataIndex : 'aysoid',
        editor    : editor,
        width     : 60
      },
      {
        header    : 'Region',
        dataIndex : 'region',
        width     : 50
      },
      {
        header    : 'MY',
        dataIndex : 'mem_year',
        width     : 50
      },
      {
        header    : 'Last Name',
        dataIndex : 'lname',
        editor    : editor,
        width     : 120
      },
      {
        header    : 'First Name',
        dataIndex : 'fname',
        width     : 100
      },
      {
        header    : 'Nick Name',
        dataIndex : 'nname',
        width     : 80
      },
      {
        header    : 'Gender',
        dataIndex : 'gender',
        width     : 50,
        renderer  : renderGender
      },
      {
        header    : 'Contact Info',
        dataIndex : 'contacts',
        width     : 200,
        editor    : editor,
        renderer  : renderContact
      },
      {
        header    : 'Certifications',
        dataIndex : 'certs',
        width     : 200,
        renderer  : renderCerts
      }
    ];
    // Now configure the grid itself
    var sm = new Ext.grid.CellSelectionModel();

    var gridConfig =
    {
      id          : 'eayso-vols-grid',
    //autoHeight  : true,
    //width       : 500,
      height      : 400,

      frame       : true,
      style       : 'margin: 5px;',

      columns     : columnModel,
      selModel    : sm,
      loadMask    : true

    };
    // apply config and init
    Ext.apply(this, Ext.apply(this.initialConfig, gridConfig));

    Zayso.Eayso.Vols.Grid.superclass.initComponent.apply(this);
  }
});
Ext.reg('Zayso.Eayso.Vols.Grid', Zayso.Eayso.Vols.Grid);