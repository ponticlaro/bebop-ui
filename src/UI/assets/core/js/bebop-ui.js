;(function(window, document, undefined, $, _, Mustache) {

	var Bebop = window.Bebop = window.Bebop || {};

	var Modules = Bebop.Modules = Bebop.Modules || {};


	Bebop = (function() {

		return {
			
		};

	})();

	////////////////
	// UI Modules //
	////////////////

	// Searchable Select
	Modules.init = function(options) {
		
		if (!options)
			options = [];
		
		var $container = options.container || $(document);
		
		// Generate Searchable Select instances
		$.each($container.find('[bebop-ui-el--searchableselect]'), function(index, item) {
			new Modules.SearchableSelect({el: item});
		});

	};

	// Searchbox
	Modules.SearchableSelect = function(options) {

		var el = options.el || null;
		
		if (el) {

			var $el    = $(el),
			    config = JSON.parse($el.attr('bebop-ui-el--searchableselect'));

			var select2_config = {
				placeholder: config.placeholder,
			  ajax: {
			    url: config.url,
			    dataType: 'json',
			    delay: 250,
			    data: function (params) {

			    	config.query.s    = params.term;
			    	config.query.page = params.page;

			      return config.query;
			    },
			    processResults: function (data, params) {
	
			      params.page = params.page || 1;

			      // Apply 'id' mapping
			      if (config.mapping.id !== undefined) {

				      data.items = $.map(data.items, function (obj) {
							  obj.id = obj[config.mapping.id]; 
							  return obj;
							});
						}

						// Apply 'text' mapping
			      if (config.mapping.text !== undefined) {

			      	data.items = $.map(data.items, function (obj) {
							  obj.text = obj[config.mapping.text]; 
							  return obj;
							});
			      }

			      return {
			        results: data.items,
			        pagination: {
			          more: data.meta.has_more
			        }
			      };
			    }
			  },
			  escapeMarkup: function (markup) { 
			  	return markup;  // let our custom templates work
			  },
			  templateResult: function (item) {

					if (!item.id && item.text && (item.disabled || item.loading))
			  		return item.text;

			  	return _.template(config.templates.result, item);
			  },
			  templateSelection: function (item) {
		      return _.template(config.templates.selection, item);
		    }
			};

			$el.select2(select2_config).on('init', function(event) {

				var $el = $(this),
				    data = $el.select2('data'),
				    ids  = [];

				$.each(data, function(index, item) {
					if (item.id)
						ids.push(item.id);
				});

				if (ids.length > 0) {

					$.ajax({
					  url: config.url,
					  dataType: 'json',
				    data: {
				    	include: ids.join(',')
				    },
				    success: function(response, status, request) {

				    	if (response.items) {
				    		$.each(response.items, function(index, item) {
				    			
				    			var id   = item[config.mapping.id],
				    			    text = item[config.mapping.text];

				    			$el.find('option[value="'+ id +'"]').text(text);
				    		});
				    	}

				    	$el.select2(select2_config).trigger('change');
				    }
					});
				}
			});

			if ($el.find('option[selected]').length > 0)
				$el.trigger('init');
		}			
	};

	// On DOM ready
	$(function() {

		// Generate datepicker instances
		$('[bebop-list--el="container"]').on('focusin', '[bebop-ui--el="datepicker"]', function() {
			$(this).datepicker({ 
				dateFormat: "M dd, yy"
			});
		});

		Modules.init();
	});

})(window, document, undefined, jQuery || $, _, Mustache);