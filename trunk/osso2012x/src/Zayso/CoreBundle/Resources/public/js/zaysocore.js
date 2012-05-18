
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
        
  //var group = ':checkbox[name^="' + nameRoot + '"]';
    var group = 'input[type=checkbox][name^="' + nameRoot + '"]';
    
    // attr return undefined if not set, 'checked' if it is
    var checked = $(this).attr('checked') ? true : false;
    
    //if (checked) checked = true;
    //else         checked = false;
        
    //alert('Changed ' + $(group).length + ' ' + $(this).attr('checked'));
        
    $(group).attr('checked', checked);
        
    //alert('Changed ' + $(group).length + ' ' + $(this).attr('checked'));
};
Zayso.dateGen = function(e)
{
    var nameChanged = $(this).attr('name'); // "refSchedSearchData[date1][month]";
        
    var nameRoot = nameChanged.substring(0,nameChanged.lastIndexOf('['));
    
    var year  = $('select[name="' + nameRoot + '[year]"]').val();
    var month = $('select[name="' + nameRoot + '[month]"]').val();
    var day   = $('select[name="' + nameRoot + '[day]"]').val();
    
    var desc  = $('input[name="' + nameRoot + '[desc]"]');
   
    var date  = new Date(year,month-1,day);
    
    // Replace using datepicker if decide to always include it
    desc.val(date.toDateString('D M d yy'));
    
    //alert('Year ' + date);
    
}
/* ==================================================
 * This gets triggered (by datepicker) when the desc field has changed
 * this = desc field
 * Sets the year/month/day elements
 * 
 * Not real pretty but seems to get the job done
 */
Zayso.dateChanged = function(dateText) // 20120109
{
    // Just in case
    if (dateText.length != 8) return;
    
    var nameChanged = $(this).attr('name'); // "refSchedSearchData[date1][month]";
    
    var nameRoot = nameChanged.substring(0,nameChanged.lastIndexOf('['));
    
    var day   = dateText.substring( 6, 8);
    var year  = dateText.substring( 0, 4);
    var month = dateText.substring( 4, 6);

    // console.log(nameRoot + ' ' + dateText + ' ' + year);
    
    $('select[name="' + nameRoot +   '[day]"]').val(day);
    $('select[name="' + nameRoot +  '[year]"]').val(year);
    $('select[name="' + nameRoot + '[month]"]').val(month);
    return;
    /*
    // indexOf may or may not be present
    // monthIndex = months.indexOf(month);
    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    var monthIndex = jQuery.inArray(month,months);
    
    if (monthIndex >= 0) 
    {
        monthIndex++;
        if (monthIndex < 10) month = '0' + monthIndex;
        else                 month = monthIndex;
        $('select[name="' + nameRoot + '[month]"]').val(month);
    }
     

    //alert('Date Changed ' + nameChanged + ' ' + day);
    return;
    
    var nameRoot = nameChanged.substring(0,nameChanged.lastIndexOf('['));
    
    var year  = $('select[name="' + nameRoot + '[year]"]').val();
    var month = $('select[name="' + nameRoot + '[month]"]').val();
    var day   = $('select[name="' + nameRoot + '[day]"]').val();
    
    var desc  = $('input[name="' + nameRoot + '[desc]"]');
   
    var date  = new Date(year,month-1,day);
    
    // Replace using datepicker if decide to always include it
    desc.val(date.toDateString('D M d yy'));
    
    //alert('Year ' + date);*/
    
}



