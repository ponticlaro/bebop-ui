<?php

namespace Ponticlaro\Bebop\UI\Helpers\MediaSources;

class Types extends \Ponticlaro\Bebop\UI\Patterns\MediaSourceAbstract {

  /**
   * Instantiates this class
   * 
   */
  public function __construct(array $config = [])
  {
    parent::__construct();

    // Defaults
    $this->config->set('name', 'Post');
    $this->config->set('labels', [
      'identifier_field'           => 'Select Post',
      'missing_identifier_message' => 'You need to select a Post'
    ]);
    $this->config->set('types', [
      'post'
    ]);

    $this->config->set($config);
    $this->config->set('id', 'types');
    $this->config->set('identifier_field', 'id');
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
  public function getContentListUISections($view, array $config)
  {
    $sections = [];

    if (is_string($view)) {
      switch ($view) {
        case 'browse':
          
          $sections[] = [
            'ui'   => 'rawHtml',
            'html' => '
              {{#id}}
                <div bebop-ui-mod-list--media-source-icon><span class="bebop-ui-icon-file"></div>
              {{/id}}'
          ];

          $sections[] = [
            'ui'   => 'rawHtml',
            'html' => '
              {{^id}}
                <div bebop-ui-mod-list--media-source-icon><span class="bebop-ui-icon-warning"></div>
                <span class="description">'. $this->config->get('labels.missing_identifier_message') .'</span>
              {{/id}}'
          ];

          break;

        case 'reorder':

          $sections[] = [
            'ui'   => 'rawHtml',
            'html' => '
              {{#id}}
                <div bebop-ui-mod-list--media-source-icon><span class="bebop-ui-icon-file"></div>
              {{/id}}'
          ];

          $sections[] = [
            'ui'   => 'rawHtml',
            'html' => '
              {{^id}}
                <div bebop-ui-mod-list--media-source-icon><span class="bebop-ui-icon-warning"></div>
                <span class="description">'. $this->config->get('labels.missing_identifier_message') .'</span>
              {{/id}}'
          ];

          break;

        case 'edit':

          $sections[] = [
            'ui'   => 'rawHtml',
            'html' => '<div bebop-ui-mod-list--media-source-icon><span class="bebop-ui-icon-file"></div>'
          ];

          $sections[] = [
            'ui'    => 'postsearch',
            'name'  => 'id',
            'label' => $this->config->get('labels.identifier_field'),
            'attrs' => [
              'style' => 'width:100%'
            ],
            'query' => [
              'type' => $this->config->get('types') ?: []
            ]
          ];
          break;
      }
    }

    return $sections;
  }
}