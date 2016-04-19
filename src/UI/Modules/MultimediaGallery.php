<?php

namespace Ponticlaro\Bebop\UI\Modules;

use Ponticlaro\Bebop\UI\Plugins\ContentList\ContentList;
use Ponticlaro\Bebop\Common\Utils;

class MultimediaGallery extends ItemList {

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
          'add_button' => 'Add Media'
        ],
        'mode'        => 'gallery',
        'file_upload' => [
          'config' => [
            'modal_title'       => 'Upload or select existing Media',
            'modal_button_text' => 'Add Media',
            'mime_types'        => ['image', 'audio', 'video'],
          ]
        ]
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-list bebop-ui-mod-list-multimediagallery">'
    ]);
  }
}