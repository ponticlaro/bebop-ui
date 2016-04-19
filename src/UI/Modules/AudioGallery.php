<?php

namespace Ponticlaro\Bebop\UI\Modules;

use Ponticlaro\Bebop\UI\Plugins\ContentList\ContentList;
use Ponticlaro\Bebop\Common\Utils;

class AudioGallery extends ItemList {

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
          'add_button' => 'Add Audios'
        ],
        'mode'        => 'gallery',
        'file_upload' => [
          'config' => [
            'modal_title'       => 'Upload or select existing audios',
            'modal_button_text' => 'Add Audios',
            'mime_types'        => ['audio'],
          ]
        ]
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-list bebop-ui-mod-list-audiogallery">'
    ]);
  }
}