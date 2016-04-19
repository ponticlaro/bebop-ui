<?php

namespace Ponticlaro\Bebop\UI\Modules;

use Ponticlaro\Bebop\UI\Plugins\ContentList\ContentList;
use Ponticlaro\Bebop\Common\Utils;

class VideoGallery extends ItemList {

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
          'add_button' => 'Add Videos'
        ],
        'mode'        => 'gallery',
        'file_upload' => [
          'config' => [
            'modal_title'       => 'Upload or select existing videos',
            'modal_button_text' => 'Add Videos',
            'mime_types'        => ['video'],
          ]
        ]
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-list bebop-ui-mod-list-videogallery">'
    ]);
  }
}