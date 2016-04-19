<?php

namespace Ponticlaro\Bebop\UI\Modules;

class ImageGallery extends ItemList {

  /**
   * Applies module defaults
   * 
   * @return void
   */
  protected function __init()
  {
    parent::__init();

    $this->setVars([
      'config' => [
        'labels' => [
          'add_button' => 'Add Images'
        ],
        'mode'        => 'gallery',
        'file_upload' => [
          'config' => [
            'modal_title'       => 'Upload or select existing images',
            'modal_button_text' => 'Add Images',
            'mime_types'        => ['image'],
          ]
        ]
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-list bebop-ui-mod-list-imagegallery">'
    ]);
  }
}