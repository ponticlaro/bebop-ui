;(function(window, document, undefined, $){

	// When DOM is ready...
	$(function(){

		if (typeof Bebop !== 'undefined') {

			if (typeof Bebop.List !== 'undefined') {

				// Exhibition & Artist: Related Product
				Bebop.List.addFormAction('__addBebopUIGalleryItem', function(event) {

					var $selector = $(event.currentTarget).siblings('[bebop-list--formElId="selector"]');
						type      = $selector.val();

					if (!type) {
						alert('You need to select a media type');
					}

					else {

						this.addNewitem({
							'type': type,
							'view': 'edit'
						});
					}
				});
			}
		}
	});

})(window, document, undefined, jQuery || $);