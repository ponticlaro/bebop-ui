<?php

namespace Ponticlaro\Bebop\UI\Helpers\MediaSources;

class Internal extends \Ponticlaro\Bebop\UI\Patterns\MediaSourceAbstract {

  /**
   * Instantiates this class
   * 
   */
  public function __construct(array $config = [])
  {
    parent::__construct();

    $this->config->set($config);
    $this->config->set('id', 'internal');
    $this->config->set('name', 'Media Library');
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

    $default_config = [
      'modal_title'       => 'Upload or select existing Media',
      'modal_button_text' => 'Add Media',
      'mime_types'        => [
        'image', 
        'audio', 
        'video'
       ]
    ];

    if ($config)
      $default_config = array_merge($default_config, $config);

    $file_upload_section = [
      'ui'     => 'fileupload',
      'name'   => 'id',
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-fileupload">',
      'config' => $default_config
    ];

    $main_sections = [
      [
        'ui'   => 'rawHtml',
        'html' => '<div bebop-ui-mod-list--media-source-embed>'
      ],
      $file_upload_section,
      [
        'ui'   => 'rawHtml',
        'html' => '</div>'
      ],
    ];

    if (is_string($view)) {
      switch ($view) {
        case 'browse':
        case 'edit':

          $sections = array_merge($sections, $main_sections);
          break;

        case 'reorder':

          $sections   = array_merge($sections, $main_sections);
          $sections[] = [
            'ui'   => 'rawHtml',
            'html' => '
              {{#title}}
                <div class="bebop-ui-mod-list--item-title">{{title}}</div>
              {{/title}}
              {{^title}}
                <span class="description">This media have no title</span>
              {{/title}}
              
            '
          ];

          break;
      }
    }

    return $sections;
  }
}