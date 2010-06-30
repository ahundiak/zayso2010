Ext.ns('Zayso');

/* ------------------------------------------------------------
 * Using composition
 * Better object design but needs to be an observer and intercept store events
 * Try extending from observable and use relayEvents
 */
Zayso.User = function(config)
{
  config = config || {};
  
  Ext.apply(this, config);
  
  this.addEvents('load');
  
  this.init();
};
Ext.extend(Zayso.User, Ext.util.Observable, 
{
  init: function()
  {
  // Make sure got some data before load completes
  /*
  this.member =
  {
    id    : -1,
    fname : '',
    lname : '',
    name  : 'Guest'
  }; */
  var recordFields =
  [
    'member_id',
    'person_id',
    'account_id',
    'region_id',
    'region_key',
    'region_desc',
    'member_fname',
    'member_lname',
    'member_name',
    'account_name',
    'person_fname',
    'person_lname',
    'person_aysoid'
  ];
  this.infoStore = new Ext.data.DirectStore
  ({
    api : { read: Zayso.Direct.User_UserInfo.read },
 
    root            : 'records',
    idProperty      : 'member_id',
    totalProperty   : 'totalCount',
    successProperty : 'success',
    messageProperty : 'message',
    fields          :  recordFields,
    
    autoLoad : false
  });
  
  this.infoStore.on('load',function(store)
  {
    // console.log('User store loaded');
    record = store.getAt(0);
    if (record == undefined) 
    {
      // Something seriously wrong
      console.log('User store loaded - No Record');
      return;
    }
    // Keep it real simpe
    this.member =
    {
      id    : record.get('member_id'),
      fname : record.get('member_fname'),
      lname : record.get('member_lname'),
      name  : record.get('member_name')
    };
    this.account =
    {
      id    : record.get('account_id'),
      name  : record.get('account_name')
    };
    this.person = 
    {
      id      : record.get('person_id'),
      fname   : record.get('person_fname'),
      lname   : record.get('person_lname'),
      aysoid  : record.get('person_aysoid')
    };
    this.region =
    {
      id    : record.get('region_id'),
      key   : record.get('region_key'),
      desc  : record.get('region_desc')
    };
  
    // Tell everyone
    this.fireEvent('load',this);
  },this);
  
  // Load if have an id
  // if (this.member.id) this.load(this.member.id);
  
  // End of init()
  },
  load: function(memberId)
  {
    this.infoStore.load({params: {member_id: memberId}});
  }
});
