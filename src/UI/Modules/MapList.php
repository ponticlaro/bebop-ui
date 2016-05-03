<?php

namespace Ponticlaro\Bebop\UI\Modules;

class MapList extends MultimediaGallery {

  /**
   * List of media sources allowed for this module
   * 
   * @var string
   */
  protected static $allowed_media_sources = [
    'google_map'
  ];

  /**
   * Applies module defaults
   * 
   * @return void
   */
  protected function __init()
  {
    parent::__init();

    $this->setVars([
      'media_sources' => [
        'google_map' => true
      ],
      'config' => [
        'labels' => [
          'add_button' => 'Add Maps'
        ]
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-list bebop-ui-mod-list-mapgallery">'
    ]);
  }
}