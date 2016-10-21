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
    Bebop.List.addFormAction('bebop-ui-action--addAssociatedType', function(event) {

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

    // Sections
    Bebop.List.addFormAction('bebop-ui-action--addSection', function(event) {

      var $selector = $(event.currentTarget).parents('[bebop-list--formelementid]').find('[bebop-list--formElId="selector"]');
          id        = $selector.val();

      if (!id) {
        alert('You need to select an option from the dropdown');
      }

      else {

        this.addNewitem({
          'type': id,
          'type_title': $selector.find('option[value='+ id +']').text(),
          'view': 'edit'
        });
      }

    });

    // Multi Source Media
    Bebop.List.addFormAction('bebop-ui-action--addMediaFromTargetSource', function(event) {

      var $selector = $(event.currentTarget).parents('[bebop-list--formelementid]').find('[bebop-list--formElId="selector"]');
          id        = $selector.val();

      if (!id || id == -1) {
        alert('You need to select a media source');
      }

      else {

        if (id == 'internal') {

          this.addNewInternalMediaitem();
        }

        else {

          this.addNewitem({
            'source_id': id,
            'source_name': $selector.find('option[value='+ id +']').text(),
            'view': 'browse'
          });     
        }
      }

    });
	});

})(window, document, undefined, jQuery || $);