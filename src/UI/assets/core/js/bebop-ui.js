;(function(window, document, undefined, $, _, Mustache) {

	var Bebop = window.Bebop = window.Bebop || {};

	var Modules = Bebop.Modules = Bebop.Modules || {};

	var Metaboxes = Bebop.Metaboxes = Bebop.Metaboxes || {};

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
		$.each($container.find('[bebop-ui-el--postsearch]'), function(index, item) {
			new Modules.PostSearch({el: item});
		});

		///////////////
		// Metaboxes //
		///////////////

		// Generate Media Source instances
		$.each($container.find('[bebop-metabox="media-source"]'), function(index, item) {
			new Metaboxes.MediaSource({el: item});
		});
	};

	// PostSearchSelect
	Modules.PostSearch = function(options) {

		var el = options.el || null;
		
		if (el) {

			var $el               = $(el),
			    config            = JSON.parse($el.attr('bebop-ui-el--postsearch')),
			    resultTemplate    = _.template(config.templates.result),
			    selectionTemplate = _.template(config.templates.selection);

			var select2_config = {
				placeholder: config.placeholder,
				allowClear: true,
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

			  	return resultTemplate(item);
			  },
			  templateSelection: function (item) {
		      return selectionTemplate(item);
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

				    	$el.trigger('change');
				    	$el.select2(select2_config).trigger('change');
				    }
					});
				}
			});

			if ($el.find('option[selected]').length > 0)
				$el.trigger('init');
		}			
	};

	///////////////
	// Metaboxes //
	///////////////

	// Media Source 
	Metaboxes.MediaSource = function(options) {

		var el = options.el || null;
		
		if (el) {

			var $el                = $(el),
					$selector          = $el.find('[bebop-metabox--media-source="selector"]'),
					$sources_container = $el.find('[bebop-metabox--media-source="sources-container"]'),
			    $sources           = $sources_container.find('[bebop-metabox--media-source]');

			$selector.on('change', function(event) {

				var selected = $(this).val();

				$.each($sources, function(index, item) {

					var $item = $(item);

					if ($item.attr('bebop-metabox--media-source') == selected) {

						$item.parents('.bebop-ui-mod').show();
					}

					else {

						$item.parents('.bebop-ui-mod').hide();
					}
				});

				if (!$sources_container.is(":visible"))
					$sources_container.show();
			});

			// Trigger initial 
			$selector.trigger('change');
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