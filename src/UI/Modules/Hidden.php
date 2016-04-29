<?php

namespace Ponticlaro\Bebop\UI\Modules;

use Ponticlaro\Bebop\Html\Elements\Input as InputElement;
use Ponticlaro\Bebop\Common\Utils;

class Hidden extends Input {

  /**
   * Applies module defaults
   * 
   * @return void
   */
  protected function __init()
  {
    parent::__init();

    // Set default vars
    $this->setVars([
      'type'   => 'hidden',
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-input bebop-ui-mod-inputhidden">'
    ]);
  }
}