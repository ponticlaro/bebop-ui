<?php

namespace Ponticlaro\Bebop\UI\Modules;

class ImageGallery extends MultimediaGallery {

  /**
   * List of media sources allowed for this module
   * 
   * @var string
   */
  protected static $allowed_media_sources = [
    'internal'
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
          'modal_title'       => 'Upload or select existing images',
          'modal_button_text' => 'Add Images',
          'mime_types'        => ['image'],
        ]
      ],
      'config' => [
        'labels' => [
          'add_button' => 'Add Images'
        ]
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-list bebop-ui-mod-list-imagegallery">'
    ]);
  }
}