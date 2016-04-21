;(function(window, document, undefined, $) {

	var Bebop = window.Bebop = window.Bebop || {};

	Bebop = (function() {

		return {
			
		};

	})();

	// On DOM ready
	$(function() {

		$('[bebop-list--el="container"]').on('focusin', '[bebop-ui--el="datepicker"]', function() {

			$(this).datepicker({ 
				dateFormat: "M dd, yy"
			});
		});

		////////////////
		// UI Modules //
		////////////////

		// Searchable Select 
		// $('[bebop-ui-searchable-select]').select2({
		// 	placeholder: "Select an item",
		// 	//allowClear: true
		// });
	});

})(window, document, undefined, jQuery || $);