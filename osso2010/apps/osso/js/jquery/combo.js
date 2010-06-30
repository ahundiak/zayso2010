/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function()
{
  // Simple local source
  var availableTags =
  [
    "c++", "java", "php", "coldfusion", "javascript", "asp", "ruby", "python",
    "c", "scala", "groovy", "haskell", "perl"
  ];
  $("#tags").autocomplete(
  {
    source: availableTags
  });

  // Simple remote source
  function log(message)
  {
    $("<div/>").text(message).prependTo("#log");
    $("#log").attr("scrollTop", 0);
  }
  $("#birds").autocomplete(
  {
    source: "search.php",
    minLength: 2,
    select: function(event, ui)
    {
      log(ui.item ? ("Selected: " + ui.item.value + " aka " + ui.item.id) : "Nothing selected, input was " + this.value);
    }
  });
});

