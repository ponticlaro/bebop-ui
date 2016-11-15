define([
  'backbone',
  'bebop-ui--listItemModel',
],
function (Backbone, ListItemModel) {

  var ListCollection = Backbone.Collection.extend({
    
    model: ListItemModel,
    
    sync: function () {
      return false; 
    }
  });

  return ListCollection;
});