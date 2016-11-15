define([
  'jquery'
],
function ($) {

  return function(options) {

    var el = options.el || null;
    
    if (el) {

      var $el                = $(el),
          $selector          = $el.find('[bebop-metabox--media-source="selector"]'),
          $sources_container = $el.find('[bebop-metabox--media-source="sources-container"]'),
          $sources           = $sources_container.find('[bebop-metabox--media-source]');

      $selector.on('change', function(event) {

        var selected = $(this).val();

        $.each($sources, function(index, item) {

          var $item = $(item);

          if ($item.attr('bebop-metabox--media-source') == selected) {

            $item.parents('.bebop-ui-mod').show();
          }

          else {

            $item.parents('.bebop-ui-mod').hide();
          }
        });

        if (!$sources_container.is(":visible"))
          $sources_container.show();
      });

      // Trigger initial 
      $selector.trigger('change');
    }
  };
});