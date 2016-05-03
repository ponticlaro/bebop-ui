<?php

namespace Ponticlaro\Bebop\UI\Helpers\MediaSources;

class GoogleMap extends \Ponticlaro\Bebop\UI\Patterns\MediaSourceAbstract {

  /**
   * Instantiates this class
   * 
   */
  public function __construct(array $config = [])
  {
    parent::__construct();

    $this->config->set($config);
    $this->config->set('id', 'google_map');
    $this->config->set('name', 'Google Map');
    $this->config->set('identifier_field', 'url');
  }

  /**
   * Renders media based on source
   * 
   * @return void
   */
  public function render()
  {
    // TODO: integrate with \Ponticlaro\Bebop\Mvc\Models\Media
  }

  /**
   * Returns UI sections to be used with UI Lists
   * 
   * @param  string $view   UI List item view
   * @param  array  $config User configuration
   * @return array          List of UI sections to be rendered
   */
  public static function getContentListUISections($view, array $config)
  {
    $sections = [];

    if (is_string($view)) {
      switch ($view) {
        case 'browse':
        	
        	$sections[] = [
        		'ui'   => 'rawHtml',
        		'html' => '
        			{{#url}}
                <div bebop-ui-mod-list--media-source-embed>
                  <iframe src="{{url}}" width="120" height="120" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
				   		{{/url}}'
        	];

        	$sections[] = [
        		'ui'   => 'rawHtml',
        		'html' => '
		        	{{^url}}
                <div bebop-ui-mod-list--media-source-icon><span class="bebop-ui-icon-warning"></div>
				        <span class="description">You need to insert a Vimeo video ID</span>
				    	{{/url}}'
        	];

          break;

        case 'reorder':

          $sections[] = [
            'ui'   => 'rawHtml',
            'html' => '
              {{#url}}
                <div bebop-ui-mod-list--media-source-icon><span class="bebop-ui-icon-map"></div>
                <div class="bebop-ui-mod-list--item-title">
                  <a target="_blank" href="{{url}}">
                    {{#title}}{{title}}{{/title}}
                    {{^title}}{{url}}{{/title}}
                  </a>
                </div>
              {{/url}}'
          ];

          $sections[] = [
            'ui'   => 'rawHtml',
            'html' => '
              {{^url}}
                <div bebop-ui-mod-list--media-source-icon><span class="bebop-ui-icon-warning"></div>
                <span class="description">You need to insert a Google Map url</span>
              {{/url}}'
          ];

          break;

        case 'edit':

          $sections[] = [
            'ui'   => 'rawHtml',
            'html' => '<div bebop-ui-mod-list--media-source-icon><span class="bebop-ui-icon-map"></div>'
          ];

          $sections[] = [
            'ui'    => 'input',
            'name'  => 'url',
            'label' => 'Map URL'
          ];
          break;
      }
    }

    return $sections;
  }
}