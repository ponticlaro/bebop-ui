// Get correct requirejs context configuration
var bebopui_requirejs = requirejs.config({
  context: "bebop-ui"
});

// Run our code in context
bebopui_requirejs([
  'jquery',
  'bebop-ui--mediaView',
],
function ($, MediaView) {

  // When DOM is ready
  $(function() {

    // Generate Media widgets
    $.each($('[bebop-media--el="container"]'), function(index, item) {
      new MediaView({el: item});
    });
  });
});