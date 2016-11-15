// Get correct requirejs context configuration
var bebopui_requirejs = requirejs.config({
  context: "bebop-ui"
});

// Run our code in context
bebopui_requirejs([
  'jquery',
  'bebop-ui--listView'
],
function ($, ListView) {

  // When DOM is ready
  $(function() {

    // Generate lists
    $.each($('[bebop-list--el="container"]'), function(index, item) {
      new ListView({el: item});
    });    
  });
});