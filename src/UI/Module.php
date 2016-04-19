<?php 

namespace Ponticlaro\Bebop\UI;

use Ponticlaro\Bebop\UI\Helpers\ModuleFactory;

class Module {

  /**
   * Generates new UI module
   * 
   * @param  string                                      $id   Module ID
   * @param  array                                       $args Module arguments
   * @return Ponticlaro\Bebop\UI\Patterns\ModuleAbstract       Module instance
   */
  public static function __callStatic($id, array $args)
  { 
    if (ModuleFactory::canManufacture($id)) {

      $args = isset($args[0]) && is_array($args[0]) ? $args[0] : [];

      return call_user_func_array(array('Ponticlaro\Bebop\UI\Helpers\ModuleFactory', 'create'), [$id, $args]);
    }
  }
}