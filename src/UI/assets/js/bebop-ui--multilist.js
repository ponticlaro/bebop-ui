// Get correct requirejs context configuration
var bebopui_requirejs = requirejs.config({
  context: "bebop-ui"
});

// Run our code in context
bebopui_requirejs([
  'jquery',
  'bebop-ui--multilistView'
],
function ($, MultilistView) {

  // When DOM is ready
  $(function() {

    // Generate lists
    $.each($('[bebop-multilist--el="container"]'), function(index, item) {
      new MultilistView({el: item});
    });
  });
});