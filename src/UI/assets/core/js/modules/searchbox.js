;(function(window, document, undefined, $) {

	// On DOM ready
	$(function() {

		var $searchableSelects =  $('[bebop-ui-searchbox]');

		if ($searchableSelects.length > 0) {

			console.log('FOUND ELS');

			$.each($searchableSelects, function(index, el) {

				var $el    = $(el), 
				    config = $el.attr('data-config'),
				    value  = $el.val();

				if (config) {

					config = JSON.parse(config);

					console.log(config);

					$.fn.select2.amd.require(
					['select2/data/array', 'select2/utils'],
					function (ArrayData, Utils) {

					  function CustomData ($element, options) {
					    CustomData.__super__.constructor.call(this, $element, options);
					  }

					  Utils.Extend(CustomData, ArrayData);

					  CustomData.prototype.current = function (callback) {
					    
					  	console.log(this);
					    var data = [];
					    var currentVal = this.$element.val();

					    if (!this.$element.prop('multiple')) {
					      currentVal = [currentVal];
					    }

					    for (var v = 0; v < currentVal.length; v++) {
					      
					    	console.log(currentVal);
					      data.push({
					        id: currentVal[v],
					        text: 'AJHDSJD'
					      });
					    }

					    callback(data);
					  };

						$el.select2({
							//dataAdapter: CustomData,
							placeholder: config.placeholder || 'Search here',
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
						      // parse the results into the format expected by Select2
						      // since we are using custom formatting functions we do not need to
						      // alter the remote JSON data, except to indicate that infinite
						      // scrolling can be used
						      params.page = params.page || 1;

						      data.items = $.map(data.items, function (obj) {
									  obj.id = obj.ID; 
									  return obj;
									});
									
									data.items = $.map(data.items, function (obj) {
									  obj.text = obj.post_title || obj.title; 
									  return obj;
									});

						      return {
						        results: data.items,
						        pagination: {
						          more: data.meta.has_more
						        }
						      };
						    }
						  },
						  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
						  // minimumInputLength: 1,
						  templateResult: function (item) {

						  	if (item.text && (item.disabled || item.loading))
						  		return item.text;

						  	return item.title || item.post_title +' <br> '+ item.post_type;
						  },
						  // templateSelection: function (item) {
					   //    return item.title || item.post_title;
					   //  }
						});
					});
				}
			});
		}
	});

})(window, document, undefined, jQuery || $);