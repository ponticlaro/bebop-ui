<?php

namespace Ponticlaro\Bebop\UI\Modules;

class AudioGallery extends MultimediaGallery {

  /**
   * List of media sources allowed for this module
   * 
   * @var string
   */
  protected static $allowed_media_sources = [
    'internal',
    'soundcloud_track',
    'soundcloud_playlist'
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
        'internal' => [
          'modal_title'       => 'Upload or select existing audios',
          'modal_button_text' => 'Add Audios',
          'mime_types'        => ['audio']
        ],
        'soundcloud_track'    => false,
        'soundcloud_playlist' => false,
      ],
      'config' => [
        'labels' => [
          'add_button' => 'Add Audios'
        ]
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-list bebop-ui-mod-list-audiogallery">'
    ]);
  }
}