<?php 

namespace Ponticlaro\Bebop;

use \Ponticlaro\Bebop\Html\Element;
use \Ponticlaro\Bebop\Html\HtmlFactory;

class Html {

  /**
   * Generates new HTML element
   * 
   * @param  string                                 $tag  Element tag
   * @param  array                                  $args Element arguments
   * @return \Ponticlaro\Bebop\Html\ElementAbstract       Element instance
   */
  public static function __callStatic($tag, $args)
  { 
    if (HtmlFactory::canManufacture($tag)) {

      return call_user_func_array(['\Ponticlaro\Bebop\Html\HtmlFactory', 'create'], [$tag, $args]); 
    }

    else {

      $args        = isset($args[0]) && is_array($args[0]) ? $args[0] : [];
      $args['tag'] = $tag;

      return new Element($args);
    }
  }
}