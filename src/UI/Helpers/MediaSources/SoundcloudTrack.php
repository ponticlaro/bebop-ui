<?php

namespace Ponticlaro\Bebop\UI\Helpers\MediaSources;

class SoundcloudTrack extends \Ponticlaro\Bebop\UI\Patterns\MediaSourceAbstract {

  /**
   * Instantiates this class
   * 
   */
  public function __construct(array $config = [])
  {
    parent::__construct();

    $this->config->set($config);
    $this->config->set('id', 'soundcloud_track');
    $this->config->set('name', 'Soundcloud Track');
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
                <div bebop-ui-mod-list--media-source-embed>
                  <iframe width="120" height="120" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/{{id}}&amp;player_type=tiny&amp;auto_play=false&amp;hide_related=true&amp;visual=false&amp;buying=false&amp;sharing=false&amp;download=false&amp;show_bpm=false&amp;bpm=false&amp;show_comments=false&amp;show_playcount=false"></iframe>
                </div>
              {{/id}}'
          ];

          $sections[] = [
            'ui'   => 'rawHtml',
            'html' => '
              {{^id}}
                <div bebop-ui-mod-list--media-source-icon><span class="bebop-ui-icon-warning"></div>
                <span class="description">You need to insert a Soundcloud Track ID</span>
              {{/id}}'
          ];

          break;

        case 'reorder':

          $sections[] = [
            'ui'   => 'rawHtml',
            'html' => '
              {{#id}}
                <div bebop-ui-mod-list--media-source-icon><span class="bebop-ui-icon-soundcloud"></div>
                <div class="bebop-ui-mod-list--item-title">
                  <a target="_blank" href="https://api.soundcloud.com/tracks/{{id}}">
                    {{#title}}
                      {{title}}
                    {{/title}}
                    {{^title}}
                      https://api.soundcloud.com/tracks/{{id}}
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
                <span class="description">You need to insert a Soundcloud Track ID</span>
              {{/id}}'
          ];

          break;

        case 'edit':

          $sections[] = [
            'ui'   => 'rawHtml',
            'html' => '<div bebop-ui-mod-list--media-source-icon><span class="bebop-ui-icon-soundcloud"></div>'
          ];

          $sections[] = [
            'ui'    => 'input',
            'name'  => 'id',
            'label' => 'Track ID'
          ];
          break;
      }
    }

    return $sections;
  }
}