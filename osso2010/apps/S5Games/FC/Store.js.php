<?php
/* ----------------------------------------------
 * Just a little test to see how store filtering works
 * Basically calling filter by will run through each record and apply any filtering criteria
 * getCount and getAt will then use the filtered/selected records
 *
 * So I can pull in all 200 game records then filter locally without hitting the server again
 */
  $title = 'S5Games - Store';

  // Additional files being tested by this action
  $jsFilesx = array
  (

  );
?>
<script type="text/javascript">

Ext.ns('Zayso');

function doit()
{
  console.log('Store testing');

  var data =
  [
    ['U10B','U10B01'],
    ['U10B','U10B02'],
    ['U10B','U10B03'],
    ['U10G','U10G01'],
    ['U10G','U10G02'],
  ];
  var store = new Ext.data.ArrayStore
  ({
    // store configs
    autoDestroy: true,
    data: data,
    storeId: 'myStore',
    // reader configs
    idIndex: 1,
    fields: [
       'div','team'
    ]
  });
  console.log('Store Initial Count ' + store.getCount());
  store.filterBy(function (record,id)
  {
    // console.log('Record ' + record.get('div') + ' ' + id);
    if (record.get('div') == 'U10G') return true;
    return false;
  });
  console.log('Store U10G Count ' + store.getCount());

  var item = store.getAt(1);
  console.log('Item ' + item.get('team'));

  store.filterBy(function (record,id)
  {
    // console.log('Record ' + record.get('div') + ' ' + id);
    if (record.get('div') == 'U10B') return true;
    return false;
  });
  console.log('Store U10B Count ' + store.getCount());

  var item = store.getAt(1);
  console.log('Item ' + item.get('team'));
  
}
Ext.onReady(doit);
</script>