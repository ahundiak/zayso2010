Ext.ns('Zayso');

/* -----------------------------------------------------------
 * Main Application
 */
Zayso.App = function(config)
{
  config = config || {};
  
  Ext.apply(this, config);
  
  this.addEvents('something');
  
  this.init();
};
Ext.extend(Zayso.App, Ext.util.Observable, 
{
  viewport: null,
  
  init: function()
  {
    console.log('Zayso.App.init');  
  },
  log: function()
  {
    // console.log('App Log ' + this.name);
  },
  execute: function(args)
  {
    // Since the user must exist and gets used everwhere then
    // Make one, one question: should the user be a Observer? How much will the user change without a refresh?
    this.user = new Zayso.User();
    
    this.user.on('load',function()
    {
      console.log('User loaded ' + this.user.member.name);
      
      if (this.viewport == null) 
      {
        this.viewport = new Zayso.Viewport();
        this.viewport.show();
      }
    },
    this);
    
    // Need the syntax to check if args exists
    this.user.load(args.memberId);
  },
  getAgeStore: function()
  {
    if (this.ageStore == null) this.ageStore = new Zayso.Store.Age();
    
    return this.ageStore;
  },
  getYearStore: function()
  {
    if (this.yearStore == null) this.yearStore = new Zayso.Store.Year();
    
    return this.yearStore;
  },
  getSeasonStore: function()
  {
    if (this.seasonStore == null) this.seasonStore = new Zayso.Store.Season();
    
    return this.seasonStore;
  },
  getRegionStore: function()
  {
    if (this.regionStore == null) 
    {
      this.regionStore = new Zayso.Store.Region();
      /*
      this.regionStore.on('load',function(store)
      {
        console.log('Region Store Loaded ' + store.getTotalCount());
        record = this.getAt(0);
        console.log('Record 0 ' + record.get('id') + ' ' + record.get('value'));
      });
      this.regionStore.on('exception',function(store,type,action)
      {
        console.log('Store exception ' + type + ' ' + action);
      });*/
      this.regionStore.load();
    }
    return this.regionStore;
  }
});
