/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
Ext.onReady(function()
{
  console.log('Test is ready');

  // Find an element and set it's content
  var el = Ext.get("replace");
  el.setStyle('color', 'red');
  el.insertFirst({ tag: 'p', html: 'This was inserted first'});

  el.replaceWith({ tag: 'p', html: 'Replaced the div'});

  //el.appendChild('<span>Appended child</span>');

});

