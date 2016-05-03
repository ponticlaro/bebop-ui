<?php

namespace Ponticlaro\Bebop\UI\Helpers\MediaSources;

class Vimeo extends \Ponticlaro\Bebop\UI\Patterns\MediaSourceAbstract {

  /**
   * Instantiates this class
   * 
   */
  public function __construct(array $config = [])
  {
    parent::__construct();

    $this->config->set($config);
    $this->config->set('id', 'vimeo_video');
    $this->config->set('name', 'Vimeo Video');
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
  public static function getContentListUISections($view, array $config)
  {
    $sections = [];

    if (is_string($view)) {
      switch ($view) {
        case 'browse':
          
          $sections[] = [
            'ui'   => 'rawHtml',
            'html' => '
              {{#id}}
                <div bebop-ui-mod-list--media-source-embed>
                  <iframe src="//player.vimeo.com/video/{{id}}" width="120" height="120" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                </div>
              {{/id}}'
          ];

          $sections[] = [
            'ui'   => 'rawHtml',
            'html' => '
              {{^id}}
                <div bebop-ui-mod-list--media-source-icon><span class="bebop-ui-icon-warning"></div>
                <span class="description">You need to insert a Vimeo video ID</span>
              {{/id}}'
          ];

          break;

        case 'reorder':

          $sections[] = [
            'ui'   => 'rawHtml',
            'html' => '
              {{#id}}
                <div bebop-ui-mod-list--media-source-icon><span class="bebop-ui-icon-vimeo"></div>
                <div class="bebop-ui-mod-list--item-title">
                  <a target="_blank" href="https://player.vimeo.com/video/{{id}}">
                    {{#title}}
                      {{title}}
                    {{/title}}
                    {{^title}}
                      https://player.vimeo.com/video/{{id}}
                    {{/title}}
                  </a>
                </div>
              {{/id}}'
          ];

          $sections[] = [
            'ui'   => 'rawHtml',
            'html' => '
              {{^id}}
                <div bebop-ui-mod-list--media-source-icon><span class="bebop-ui-icon-warning"></div>
                <span class="description">You need to insert a Vimeo video ID</span>
              {{/id}}'
          ];

          break;

        case 'edit':

          $sections[] = [
            'ui'   => 'rawHtml',
            'html' => '<div bebop-ui-mod-list--media-source-icon><span class="bebop-ui-icon-vimeo"></div>'
          ];

          $sections[] = [
            'ui'    => 'input',
            'name'  => 'id',
            'label' => 'Video ID'
          ];
          break;
      }
    }

    return $sections;
  }
}