Ext.ns('Zayso.Store');
Ext.ns('Zayso.Combo');

/* =================================================
 * Event Person Type Store
 */
Zayso.Store.EventPersonType = function()
{
  var data = 
  [
  //[10, 'CR',  'Center    Referee'  ],
  //[11, 'AR1', 'Assistant Referee 1'],
  //[12, 'AR2', 'Assistant Referee 2'],
    
    [13, '4TH', '4th Official'],
    [14, 'OBS', 'Observer'    ],
    [15, 'MEN', 'Mentor'      ],
    [16, 'STB', 'Standby'     ]
  ];
  var config = 
  {
    xtype   :  'arraystore',
    data    :   data,
    fields  : ['type_id', 'type_key', 'type_desc'],
    autoLoad: true
  };
  return config;
};

/* =================================================
 * Older code, may or may not keep
 */
/* ================================================= 
 * Regions Store and Combo
 */
Zayso.Store.Region = function() 
{
  config = 
  {
    url:     'ajax.php?action=league-combo',
    root:    'rows',
    id:      'id',
    fields: ['id', 'value']
  };
  Zayso.Store.Region.superclass.constructor.call(this,config);
}
Ext.extend(Zayso.Store.Region,Ext.data.JsonStore,{});

Zayso.Combo.Region = Ext.extend(Ext.form.ComboBox, 
{
  initComponent: function()
  {
    var store = app.getRegionStore();
    
    // Update whenever store is loaded
    store.on('load',function(store)
    {
      this.setValue(this.getValue());
    },this);
    
    var config =
    { 
      store:          store,
      displayField:  'value',
      valueField:    'id',

      typeAhead:      true,
      forceSelection: true,
      triggerAction: 'all',
      emptyText:     'Region',
      selectOnFocus:  true,
      
      mode:          'local',
      width:          200
    };
    // Allow some overrides
    config.value = this.initialConfig.value || app.user.userRegion;

    // apply config
    Ext.apply(this, Ext.apply(this.initialConfig, config));

    Zayso.Combo.Region.superclass.initComponent.apply(this);
    
  }
});
Ext.reg('Zayso.Combo.Region', Zayso.Combo.Region);

/* ================================================= 
 * Seasons Store and Combo
 */
Zayso.Store.Season = function() 
{
  var data = 
  [
    [1, 'Fall'  ],
    [2, 'Winter'],
    [3, 'Spring'],
    [4, 'Summer']
  ];
  config = 
  {
    data:    data,
    id:      'id',
    fields: ['id', 'value'],
    autoLoad: true
  };
  Zayso.Store.Season.superclass.constructor.call(this,config);
}
Ext.extend(Zayso.Store.Season,Ext.data.SimpleStore,{});

Zayso.Combo.Season = Ext.extend(Ext.form.ComboBox, 
{
  initComponent: function()
  {
    var config =
    {
      store:          app.getSeasonStore(),
      displayField:  'value',
      valueField:    'id',

      typeAhead:      true,
      forceSelection: true,
      triggerAction: 'all',
      emptyText:     'Year',
      selectOnFocus:  true,
      
      mode:          'local',
      width:          75
    };
    // Allow some overrides
    config.value = this.initialConfig.value || app.user.userSeason;
    
    // apply config
    Ext.apply(this, Ext.apply(this.initialConfig, config));

    Zayso.Combo.Season.superclass.initComponent.apply(this);
  }
});
Ext.reg('Zayso.Combo.Season', Zayso.Combo.Season);

/* ================================================= 
 * Years Store and Combo
 */
Zayso.Store.Year = function() 
{
  var data = 
  [
    [2012, '2012'],
    [2011, '2011'],
    [2010, '2010'],
    [2009, '2009'],
    [2008, '2008'],
    [2007, '2007'],
    [2006, '2006'],
    [2005, '2005'],
    [2004, '2004'],
    [2003, '2003'],
    [2002, '2002'],
    [2001, '2001']
  ];
  config = 
  {
    data:    data,
    id:      'id',
    fields: ['id', 'value'],
    autoLoad: true
  };
  Zayso.Store.Year.superclass.constructor.call(this,config);
}
Ext.extend(Zayso.Store.Year,Ext.data.SimpleStore,{});

Zayso.Combo.Year = Ext.extend(Ext.form.ComboBox, 
{
  initComponent: function()
  {
    //console.log(this.initialConfig);
    var config =
    {
      store:          app.getYearStore(),
      displayField:  'value',
      valueField:    'id',

      typeAhead:      true,
      forceSelection: true,
      triggerAction: 'all',
      emptyText:     'Year',
      selectOnFocus:  true,
      
      mode:          'local',
      width:          75
    };
    // Allow some overrides
    config.value = this.initialConfig.value || app.user.userYear;
    
    // apply config
    Ext.apply(this, Ext.apply(this.initialConfig, config));

    Zayso.Combo.Year.superclass.initComponent.apply(this);
  }
});
Ext.reg('Zayso.Combo.Year', Zayso.Combo.Year);

/* ================================================= 
 * Age Store and Combo
 */
Zayso.Store.Age = function() 
{
  var data = 
  [
    [ 5, 'U05'],
    [ 6, 'U06'],
    [ 7, 'U07'],
    [ 8, 'U08'],
    [10, 'U10'],
    [12, 'U12'],
    [14, 'U14'],
    [16, 'U16'],
    [19, 'U19']
  ];
  config = 
  {
    data:    data,
    id:      'id',
    fields: ['id', 'value'],
    autoLoad: true
  };
  Zayso.Store.Age.superclass.constructor.call(this,config);
}
Ext.extend(Zayso.Store.Age,Ext.data.SimpleStore,{});

Zayso.Combo.Age = Ext.extend(Ext.form.ComboBox, 
{
  initComponent: function()
  {
    var config =
    {
      store:          app.getAgeStore(),
      displayField:  'value',
      valueField:    'id',

      typeAhead:      true,
      forceSelection: true,
      triggerAction: 'all',
      emptyText:     'Age',
      selectOnFocus:  true,  
      mode:          'local'
    };
    // Allow some overrides
    // config.value = this.initialConfig.value || app.user.userYear;
    if (!this.initialConfig.width) config.width = 50;
    
    
    // apply config
    Ext.apply(this, Ext.apply(this.initialConfig, config));

    Zayso.Combo.Age.superclass.initComponent.apply(this);
  }
});
Ext.reg('Zayso.Combo.Age', Zayso.Combo.Age);
