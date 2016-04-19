<?php

namespace Ponticlaro\Bebop\Html;

class HtmlFactory {

  /**
   * Holds the class that manufacturables must extend
   */
  const ELEMENT_CLASS = 'Ponticlaro\Bebop\Html\ElementAbstract';

  /**
   * List of manufacturable classes
   * 
   * @var array
   */
  protected static $manufacturable = array(
    'checkbox' => 'Ponticlaro\Bebop\Html\Elements\Checkbox',
    'form'     => 'Ponticlaro\Bebop\Html\Elements\Form',
    'img'      => 'Ponticlaro\Bebop\Html\Elements\Img',
    'input'    => 'Ponticlaro\Bebop\Html\Elements\Input', 
    'option'   => 'Ponticlaro\Bebop\Html\Elements\Option', 
    'radio'    => 'Ponticlaro\Bebop\Html\Elements\Radio',
    'select'   => 'Ponticlaro\Bebop\Html\Elements\Select',
    'submit'   => 'Ponticlaro\Bebop\Html\Elements\Submit',
    'textarea' => 'Ponticlaro\Bebop\Html\Elements\Textarea',
  );

  /**
   * Making sure class cannot get instantiated
   */
  protected function __construct() {}

  /**
   * Making sure class cannot get instantiated
   */
  protected function __clone() {}

  /**
   * Adds a new manufacturable class
   * 
   * @param string $type  Object type ID
   * @param string $class Full namespace for a class
   */
  public static function set($type, $class)
  {
    self::$manufacturable[$type] = $class;
  }

  /**
   * Removes a new manufacturable class
   * 
   * @param string $type  Object type ID
   */
  public static function remove($type)
  {
    $type = strtolower($type);

    if (isset(self::$manufacturable[$type])) 
      unset(self::$manufacturable[$type]);
  }

  /**
   * Checks if there is a manufacturable with target key
   * 
   * @param  string  $key Target key
   * @return boolean      True if key exists, false otherwise
   */
  public static function canManufacture($key)
  {
    return is_string($key) && isset(self::$manufacturable[strtolower($key)]) ? true : false;
  }

  /**
   * Returns the id to manufacture another instance of the passed object, if any
   * 
   * @param  object $instance Arg instance
   * @return string           Arg ID 
   */
  public static function getInstanceId($instance)
  {
    if (is_object($instance) && is_a($instance, self::ELEMENT_CLASS)) {

      $class = get_class($instance);
      $id    = array_search($class, self::$manufacturable);

      return $id ?: null;
    }

    return null;
  }

  /**
   * Creates instance of target class
   * 
   * @param  string] $type Class ID
   * @param  array   $args Class arguments
   * @return object        Class instance
   */
  public static function create($type, array $args = array())
  {
    $type = strtolower($type);
    
    // Check if target is in the allowed list
    if (array_key_exists($type, self::$manufacturable)) {

      $class_name = self::$manufacturable[$type];

      return call_user_func(array(__CLASS__, "__createInstance"), $class_name, $args);
    }

    // Return null if target object is not manufacturable
    return null;
  }

  /**
   * Creates and instance of the target class
   * 
   * @param  string $class_name Target class
   * @param  array  $args       Arguments to pass to target class
   * @return mixed              Class instance or false
   */
  private static function __createInstance($class_name, array $args = array())
  {
    // Get an instance of the target class
    $obj = call_user_func_array(

      array(
        new \ReflectionClass($class_name), 
        'newInstance'
      ), 
      $args
    );
        
    // Return object
    return is_a($obj, self::ELEMENT_CLASS) ? $obj : null;
  }
}