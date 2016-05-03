<?php

namespace Ponticlaro\Bebop\UI\Modules;

class DocumentList extends MultimediaGallery {

  /**
   * List of media sources allowed for this module
   * 
   * @var string
   */
  protected static $allowed_media_sources = [
    'internal',
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
        'internal' => [
          'modal_title'       => 'Upload or select existing documents',
          'modal_button_text' => 'Add Documents',
          'mime_types'        => ['text', 'application'],
        ],
        'google_map' => false
      ],
      'config' => [
        'labels' => [
          'add_button' => 'Add Documents'
        ]
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-list bebop-ui-mod-list-docslist">'
    ]);
  }
}