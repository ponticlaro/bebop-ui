<?php

namespace Ponticlaro\Bebop\UI\Modules;

class VideoGallery extends MultimediaGallery {

  /**
   * List of media sources allowed for this module
   * 
   * @var string
   */
  protected static $allowed_media_sources = [
    'internal',
    'vimeo',
    'youtube_video',
    'youtube_playlist'
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
          'modal_title'       => 'Upload or select existing videos',
          'modal_button_text' => 'Add Videos',
          'mime_types'        => ['video'],
        ],
        'vimeo'            => false,
        'youtube_video'    => false,
        'youtube_playlist' => false
      ],
      'config' => [
        'labels' => [
          'add_button' => 'Add Videos'
        ]
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-list bebop-ui-mod-list-videogallery">'
    ]);
  }
}