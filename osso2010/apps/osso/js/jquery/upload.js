jQuery(document).ready(function()
{
  /* example 1 */
  var button = $('#button1'), interval;
  new AjaxUpload(button,
  {
    action: 'upload-test.php',
    name: 'myfile',
    onSubmit : function(file, ext)
    {
      // change button text, when user selects file
      button.text('Uploading');

      // If you want to allow uploading only 1 file at time,
      // you can disable upload button
      this.disable();

      // Uploading -> Uploading. -> Uploading...
      interval = window.setInterval(function()
      {
        var text = button.text();
        if (text.length < 13) button.text(text + '.');
        else                  button.text('Uploading');
      }, 200);
    },
    onComplete: function(file, response)
    {
      button.text('Upload');

      window.clearInterval(interval);

      // enable upload button
      this.enable();

      // add file to the list
      $('<li></li>').appendTo('#example1 .files').text(file);
    }
  });
  /* example 2 */
  button = $('#form2 .button2');
  var upload = new AjaxUpload(button,
  {
    action:       'upload-test.php',
    name:         'myfile',
    autoSubmit:    false,
    responseType: 'json',
    onChange: function(file, extension)
    {
      // console.log(file + ' ' + extension);
      $('#form2 [name=filename]').val(file);
    },
    onComplete: function(file, response)
    {
      alert('Completed ' + file + ' ' + response.name);
    }
  });
  jQuery('#form2 .submit2').click(function(event)
  {
    event.preventDefault();
    upload.submit();
  });
});
