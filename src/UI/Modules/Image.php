<?php

namespace Ponticlaro\Bebop\UI\Modules;

class Image extends FileUpload {

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
        'modal_title'       => 'Upload or select an existing image',
        'modal_button_text' => 'Add Image',
        'mime_types'        => [
          'image'
        ]
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-fileupload bebop-ui-mod-fileupload-image">'
    ]);
  }
}