Ext.ns('Zayso.Referee.Signup');

Zayso.Referee.Signup.Grid = Ext.extend(Ext.grid.EditorGridPanel, 
{
  eventId: 0,
  
  initComponent: function()
  {
  // List of positions 
  var eventPersonTypeCombo = new Ext.form.ComboBox 
  ({
    xtype         : 'combo',
    mode          : 'local',
    triggerAction : 'all',

    displayField  : 'type_desc',
    valueField    : 'type_id',
    store         :  Zayso.Store.EventPersonType(),
    emptyText     : 'Add new position',
    title         : 'Select position to add'
  });
  eventPersonTypeCombo.on('select',function(combo,record,index)
  {
    // console.log('Select New Position ' + record.get('type_key'));
    var grid  = this;
    var store = grid.getStore();
    
    // New position record
    var id = Ext.id();
    
    var position = new store.recordType
    ({
      event_person_id : id,
      event_id        : grid.eventId,
   
      person_id       : 0,
      person_fname    : '',
      person_lname    : '',
    
      position_id     : record.get('type_id'),
      position_key    : record.get('type_key'),
      position_desc   : record.get('type_desc')
    });
    store.addSorted(position);
        
    // Assume row = index in the store
    var row = store.findExact('event_person_id',id);
    grid.startEditing(row,4);
        
    // Clear out the selection
    combo.reset();
  },this);
  
  // Referee Names combo
  var refereeNamesStore = new Ext.data.DirectStore
  ({
    api :
    {
      read: Zayso.Direct.Referee_SignupCombo.read
    },
    root            : 'records',
    totalProperty   : 'totalCount',
    idProperty      : 'person_id',
    fields          : 
    [
      'person_id','person_fname','person_lname',
      {
        name:   'person_full_name',
        convert: function(value,record)
        {
          var id = record.person_id;
          if ((id < 1) || (id == null)) return 'Not Assigned';
          
          var fname = record.person_fname;
          var lname = record.person_lname;
          if (fname && lname) return fname + ' X ' + lname;
          if (lname) return 'X ' + lname;
          if (fname) return 'X ' + fname;
          return 'Not Assigned';
        }
      },
      {
        name:   'person_full_namex',
        convert: function(value,record)
        {
          var id = record.person_id;
          if ((id < 1) || (id == null)) return 'Not Assigned';
          
          var fname = record.person_fname;
          var lname = record.person_lname;
          if (fname && lname) return lname + ', ' + fname;
          if (lname) return lname;
          if (fname) return fname;
          return 'Not Assigned';
        }
      }
    ],
    // Needs to be set
    baseParams: { member_id : 27 },
    autoLoad : true
  });
  
  var refereeNamesCombo = new Ext.form.ComboBox
  ({
    xtype         : 'combo',
    triggerAction : 'all',
  
    displayField  : 'person_full_name',
    valueField    : 'person_id',
    store         :  refereeNamesStore,
    tpl           : 
      '<tpl for=".">' + 
        '<div ext:qtip="{person_id}" class="x-combo-list-item">' + 
          '{person_full_namex}' + 
        '</div>' + 
      '</tpl>'
  });

  // Master grid store
  var recordFields = 
  [
    { name : 'event_person_id', mapping : 'event_person_id' },
    { name : 'event_id',        mapping : 'event_id'        },
   
    { name : 'person_id',       mapping : 'person_id'    },
    { name : 'person_fname',    mapping : 'person_fname' },
    { name : 'person_lname',    mapping : 'person_lname' },
    
    { name : 'position_id',     mapping : 'position_id'   },
    { name : 'position_key',    mapping : 'position_key'  },
    { name : 'position_desc',   mapping : 'position_desc' }
  ];
  var writer = new Ext.data.JsonWriter
  ({
    encode: false,  // Very important, defaults to true
    writeAllFields : true
  });
  var reader = new Ext.data.JsonReader
  ({
    root            : 'records',
    idProperty      : 'event_person_id',
    totalProperty   : 'totalCount',
    successProperty : 'success',
    messageProperty : 'message',
    
    fields          : recordFields
  });
  var proxy = new Ext.data.DirectProxy
  ({
    api :
    {
      read:   Zayso.Direct.Event_EventPerson.read,
      create: Zayso.Direct.Event_EventPerson.create,
      update: Zayso.Direct.Event_EventPerson.update
    },
    paramsAsHash: true
  });
  var gridStore = new Ext.data.DirectStore
  ({
    // These all come from Store
    storeId     : 'referee-signup-grid-store',
    baseParams  : { event_id : this.eventId },
    autoLoad    : false,
    autoSave    : false,
    
    remoteSort  : false,
    sortInfo    : { field : 'position_id', direction : 'ASC'},
    
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
  
  if (this.eventId) gridStore.load();
  
  // Master grid itself
  var textFieldEditor = new Ext.form.TextField();
  
  var columnModel = 
  [
    {
      header    : 'Position',
      dataIndex : 'position_desc',
      width     : 150
    },
    {
      header    : 'ID',
      dataIndex : 'person_id',
      width     : 50
    },
    {
      header    : 'Last Name',
      dataIndex : 'person_lname',
      editor    : textFieldEditor
    },
    {
      header    : 'First Name',
      dataIndex : 'person_fname',
      editor    : textFieldEditor
    },
    {
      header    : 'Referee Name',
      dataIndex : 'person_id',
      width     : 150,
      editor    : refereeNamesCombo,
      renderer  : function(value,metadata,record,rowIndex,colIndex,store)
      {
        var id = record.get('person_id');
        
        if ((id < 1) || (id == null)) return 'Double-click to signup';
        
        var fname = record.get('person_fname');
        var lname = record.get('person_lname');
        
        if (fname && lname) return fname + ' ' + lname;
        if (fname) return fname;
        if (lname) return lname;
        
        return 'Missing name for ' + id;
      }
    }
  ];

  var gridToolbar = 
  {
    xtype       : 'toolbar',
    items : 
    [
      '-',
      {
        text : 'Refresh',
        handler : function () { gridStore.load(); }
      },
      {
        // If save is successful then might want to always follow with a load
        text : 'Save Changes',
        handler : function () { gridStore.save(); }
      },
      '-',
      {
        text : 'Reject Changes',
        handler : function () { gridStore.rejectChanges(); }
      },
      '-',
      eventPersonTypeCombo,
      '-'
    ]
  };
  
  var grid = 
  {
    id          : 'referee-signup-grid',
  //xtype       : 'editorgrid',
    title       : 'Referee Assignments',
    autoHeight  : true,
  //width       : 500,
  //height      : 400,
    
    frame       : true,
    style       : 'margin: 5px;',
    
    store       : gridStore,
    columns     : columnModel,
    loadMask    : true,
    
    bbar: gridToolbar,
    
    clicksToEdit: 2,
    viewConfig  : { forceFit : true }
  };
    
  // apply config and init
  Ext.apply(this, Ext.apply(this.initialConfig, grid));

  Zayso.Referee.Signup.Grid.superclass.initComponent.apply(this);
  
  // Listeners, cannot use the listeners config option here
  this.on('afteredit', function(event) 
  {
    // console.log('afteredit ' + event.field + ' ' + event.value + ' ' + event.record.get('person_id')); 
    if (event.field == 'person_id')
    {
      // console.log('afteredit ' + event.field + ' ' + event.value + ' ' + event.record.get('person_id'));
      // console.log('after edit ' + refereeNamesCombo);
          
      var combo  = refereeNamesCombo; // Yea for closures
      var store  = combo.getStore();
      var record = store.getAt(store.find(event.field,event.value));
          
      if (!record) 
      {
        // This is actually ok
        // console.log('Combo store record not found, value ' + event.value);
      }
      else
      {
        event.record.set('person_fname',record.get('person_fname'));
        event.record.set('person_lname',record.get('person_lname'));
      }    
    }
  });
  this.on('beforeedit',function(event)
  {
    var app_person_id = 464;
        
    var person_id = event.record.get('person_id');
        
    // Edit your own records
    if (app_person_id == person_id) return;
        
    // Edit records with no official
    if (person_id < 1) return;
        
    // Maybe grab the refereeComboList and allow to edit if in the list
    // Need to make sure list is triggered
    // User can also have some admin overrides
        
    // Editing not allowed
    // event.cancel = true;
  });

  // End of initComponent
  // console.log('Referee-Signup-Grid Component initted');
  },
  setEventId: function(eventId)
  {
    this.eventId = eventId;
    
    var store = this.getStore();
    
    store.setBaseParam('event_id',eventId);
    store.load();
  }
});
Ext.reg('Zayso.Referee.Signup.Grid', Zayso.Referee.Signup.Grid);
