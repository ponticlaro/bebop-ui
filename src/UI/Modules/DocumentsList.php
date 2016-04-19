<?php

namespace Ponticlaro\Bebop\UI\Modules;

class DocumentsList extends ItemList {

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
          'add_button' => 'Add Documents'
        ],
        'mode'        => 'gallery',
        'file_upload' => [
          'config' => [
            'modal_title'       => 'Upload or select existing documents',
            'modal_button_text' => 'Add Documents',
            'mime_types'        => ['text', 'application'],
          ]
        ]
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-list bebop-ui-mod-list-docslist">'
    ]);
  }
}