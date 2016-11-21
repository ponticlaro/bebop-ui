// Get Underscorejs from WordPress
if (typeof _ === 'function')
  define('underscore', function () { return _; });

// Get jQuery from WordPress
if (typeof jQuery === 'function')
  define('jquery', function () { return jQuery; });

// Get Backbonejs from WordPress
if (typeof Backbone === 'object')
  define('backbone', function () { return Backbone; });

// Configure Requirejs
var bebop_ui_requirejs = requirejs.config({
  context: "bebop-ui",
  baseUrl: '/_bebop/static/ui/js',
  paths: {

    // Main
    'bebop': 'Bebop?noext',

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
    'mustache': 'vendor/mustache.min?noext',
    'select2': 'vendor/select2.full.min?noext'
  } 
});

// Run our code in context
bebop_ui_requirejs([
  'jquery',
  'bebop'
],
function ($, Bebop) {

  // On DOM ready
  $(function() {

    // Initialize Bebop UI modules
    Bebop.Modules.init();
  });
});