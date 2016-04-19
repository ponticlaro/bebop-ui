<?php

namespace Ponticlaro\Bebop\UI\Modules;

class Audio extends FileUpload {

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
        'modal_title'       => 'Upload or select an existing audio',
        'modal_button_text' => 'Add Audios',
        'mime_types'        => [
          'audio'
        ]
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-fileupload bebop-ui-mod-fileupload-audio">'
    ]);
  }
}