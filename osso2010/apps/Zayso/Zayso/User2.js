Ext.ns('Zayso');

/* ------------------------------------------------------------
 * Using composition
 * Better object design but needs to be an observer and intercept store events
 * Try extending from observable and use relayEvents
 */
Zayso.User = function(config) 
{
  // Make sure got some data before load completes
  this.member =
  {
    id    : 0,
    fname : '',
    lname : '',
    name  : 'Guest'
  };
  var recordFields =
  [
    'member_id',
    'person_id',
    'region_id',
    'account_id',
    'member_fname',
    'member_lname',
    'account_name',
    'person_fname',
    'person_lname',
    'person_aysoid',
    'region_key',
    'region_desc'
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
      lname : record.get('member_lname')
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
    }
    
    // Clean up any nulls and maybe build a few combined things
    
  },this);

  // Add a function here?
  this.load = function(memberId)
  {
    this.infoStore.load({params: {member_id: memberId}});
  }
  // Load it
  this.load(31);
  
  // this.infoStore.load({params: {member_id: 31}});
};
