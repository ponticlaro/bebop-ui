// Configure Requirejs
var bebopui_requirejs = requirejs.config({
  context: "bebop-ui",
  baseUrl: '/_bebop/static/ui/js',
  paths: {

    // Main Files
    'bebop-ui--main': 'bebop-ui--main?noext',
    'bebop-ui--media': 'bebop-ui--media?noext',
    'bebop-ui--list': 'bebop-ui--list?noext',
    'bebop-ui--multilist': 'bebop-ui--multilist?noext',

    // Modules
    'bebop-ui-mod--post-search': 'modules/PostSearch?noext',
    'bebop-ui-mod--media-source-metabox': 'modules/MediaSourceMetabox?noext',

    // Views
    'bebop-ui--mediaView': 'views/Media?noext',
    'bebop-ui--listView': 'views/List?noext',
    'bebop-ui--listItemView': 'views/ListItem?noext',
    'bebop-ui--multilistView': 'views/MultiList?noext',

    // Models
    'bebop-ui--listItemModel': 'models/ListItemModel?noext',

    // Collections
    'bebop-ui--listCollection': 'collections/ListCollection?noext',

    // Vendor
    'underscore': 'vendor/underscore.min?noext',
    'backbone': 'vendor/backbone.min?noext',
    'jquery': 'vendor/jquery-2.2.4.min?noext',
    'mustache': 'vendor/mustache.min?noext',
    'select2': 'vendor/select2.full.min?noext',
    'jquery.ba-throttle-debounce': 'vendor/jquery.ba-throttle-debounce.min?noext',
    'jquery-ui.tabs': 'vendor/jquery-ui-tabs.min?noext',
    'jquery-ui.sortable': 'vendor/jquery-ui-sortable.min?noext',
  }
});

// Run our code in context
bebopui_requirejs(['bebop-ui--main']);