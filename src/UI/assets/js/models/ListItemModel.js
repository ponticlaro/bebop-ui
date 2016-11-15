define([
  'backbone'
],
function (Backbone) {

  var ListItemModel = Backbone.Model.extend({ 
    
    idAttribute: "_id",

    defaults: {
      view: 'browse'
    },

    sync: function () {
      return false; 
    }
  });

  return ListItemModel;
});