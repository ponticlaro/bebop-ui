define([
  'jquery',
  'bebop-ui-mod--post-search',
  'bebop-ui-mod--media-source-metabox',
  'bebop-ui--mediaView',
  'bebop-ui--listView',
  'bebop-ui--listItemView',
  'bebop-ui--multilistView',
  'bebop-ui--listItemModel',
  'bebop-ui--listCollection',
],
function (
  $, 
  PostSearchModule,
  MediaSourceMetaboxModule,
  MediaView, 
  ListView, 
  ListItemView, 
  MultilistView, 
  ListItemModel, 
  ListCollection) {

  var Bebop = {
    Modules: {}
  };

  // Backward compatibility
  window.Bebop              = Bebop;
  Bebop.Modules.PostSearch  = PostSearchModule;
  Bebop.Modules.MediaSource = MediaSourceMetaboxModule;
  Bebop.Media               = MediaView;
  Bebop.List                = ListView;
  Bebop.List.ItemView       = ListItemView;
  Bebop.List.ItemModel      = ListItemModel;
  Bebop.List.Collection     = ListCollection;
  Bebop.Multilist           = MultilistView;

  ////////////////
  // UI Modules //
  ////////////////

  // Initialize Bebop Modules
  Bebop.Modules.init = function(options) {
    
    if (!options)
      options = [];
    
    var $container = options.container || $(document);
    
    // Generate Searchable Select Module instances
    $.each($container.find('[bebop-ui-el--postsearch]'), function(index, item) {
      new PostSearchModule({el: item});
    });

    // Generate Media Source Metabox instances
    $.each($container.find('[bebop-metabox="media-source"]'), function(index, item) {
      new MediaSourceMetaboxModule({el: item});
    });
  };

  // On DOM ready
  $(function() {

    // Generate Media widgets
    $.each($('[bebop-media--el="container"]'), function(index, item) {
      new MediaView({el: item});
    });

    // Generate lists
    $.each($('[bebop-list--el="container"]'), function(index, item) {
      new ListView({el: item});
    });

    // Generate multilists
    $.each($('[bebop-multilist--el="container"]'), function(index, item) {
      new MultilistView({el: item});
    });

    // Generate datepicker instances
    $('[bebop-list--el="container"]').on('focusin', '[bebop-ui--el="datepicker"]', function() {
      $(this).datepicker({ 
        dateFormat: "M dd, yy"
      });
    });

    Bebop.Modules.init();
  });

  return Bebop;
});