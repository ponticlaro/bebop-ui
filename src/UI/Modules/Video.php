<?php

namespace Ponticlaro\Bebop\UI\Modules;

class Video extends FileUpload {

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
        'modal_title'       => 'Upload or select an existing video',
        'modal_button_text' => 'Add Video',
        'mime_types'        => [
          'video'
        ]
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-fileupload bebop-ui-mod-fileupload-video">'
    ]);
  }
}