<?php

namespace Ponticlaro\Bebop\UI\Modules;

class Pdf extends FileUpload {

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
        'modal_title'       => 'Upload or select an existing PDF',
        'modal_button_text' => 'Select PDF',
        'mime_types'        => [
          'application/pdf'
        ]
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-fileupload bebop-ui-mod-fileupload-pdf">'
    ]);
  }
}