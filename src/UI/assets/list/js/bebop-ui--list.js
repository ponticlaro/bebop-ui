;(function(window, document, undefined, $) {

	// When DOM is ready
	$(function() {

		// Generate lists
		$.each($('[bebop-list--el="container"]'), function(index, item) {
			new Bebop.List({el: item});
		});

		////////////////
		// UI Modules //
		////////////////

		// Associated Types
    Bebop.List.addFormAction('addAssociatedType', function(event) {

      console.log('EVENT: addAssociatedType');

      var $selector = $(event.currentTarget).parents('[bebop-list--formelementid]').find('[bebop-list--formElId="selector"]');
          id        = $selector.val();

      if (!id && id != -1) {

        alert('You need to select an item');
      }

      else {

        this.addNewitem({
          'id':   id,
          'view': 'browse'
        });

        $selector.val('').trigger('change');
      }
    });

	});

})(window, document, undefined, jQuery || $);