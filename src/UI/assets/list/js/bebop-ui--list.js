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

    // Page Sections
    Bebop.List.addFormAction('bebop-ui-action--addPageSection', function(event) {

      var $selector = $(event.currentTarget).parents('[bebop-list--formelementid]').find('[bebop-list--formElId="selector"]');
          id        = $selector.val();

      if (!id || id == -1) {
        alert('You need to select a page section type');
      }

      else {

        this.addNewitem({
          'type': id,
          'type_title': $selector.find('option[value='+ id +']').text(),
          'view': 'edit'
        });

        $selector.val(-1);
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

        this.addNewitem({
          'source_id': id,
          'source_name': $selector.find('option[value='+ id +']').text(),
          'view': 'edit'
        });
      }

    });
	});

})(window, document, undefined, jQuery || $);