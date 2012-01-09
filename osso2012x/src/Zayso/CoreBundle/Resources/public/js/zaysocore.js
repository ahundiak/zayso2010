
Zayso = {};

Zayso.exclaim = function(msg)
{
    alert('A Zayso Alert: ' + msg);
}
Zayso.checkboxAll = function(e)
{
    var nameRoot = $(this).attr('name'); // "refSchedSearchData[ages][All]";
        
    nameRoot = nameRoot.substring(0,nameRoot.lastIndexOf('['));
    
    //alert('Changed ' + nameRoot);
        
        //var group = ':checkbox[name=' + $(this).attr('name') + ']';
        
    var group = ':checkbox[name^="' + nameRoot + '"]';
    
    // attr return undefined if not set, 'checked' if it is
    var checked = $(this).attr('checked') ? true : false;
    
    //if (checked) checked = true;
    //else         checked = false;
        
    //alert('Changed ' + $(group).length + ' ' + $(this).attr('checked'));
        
    $(group).attr('checked', checked);
        
    //alert('Changed ' + $(group).length + ' ' + $(this).attr('checked'));
};



