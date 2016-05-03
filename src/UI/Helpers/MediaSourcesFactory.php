<?php

namespace Ponticlaro\Bebop\UI\Helpers;

class MediaSourcesFactory {

  /**
   * Holds the class that manufacturables must extend
   */
  const MEDIA_TYPE_CLASS = 'Ponticlaro\Bebop\UI\Patterns\MediaSourceAbstract';

  /**
   * List of manufacturable classes
   * 
   * @var array
   */
  protected static $manufacturable = array(
   'internal'            => 'Ponticlaro\Bebop\UI\Helpers\MediaSources\Internal',
   'google_map'          => 'Ponticlaro\Bebop\UI\Helpers\MediaSources\GoogleMap',
   'soundcloud_playlist' => 'Ponticlaro\Bebop\UI\Helpers\MediaSources\SoundcloudPlaylist',
   'soundcloud_track'    => 'Ponticlaro\Bebop\UI\Helpers\MediaSources\SoundcloudTrack',
   'vimeo'               => 'Ponticlaro\Bebop\UI\Helpers\MediaSources\Vimeo',
   'youtube_video'       => 'Ponticlaro\Bebop\UI\Helpers\MediaSources\YoutubeVideo',
   'youtube_playlist'    => 'Ponticlaro\Bebop\UI\Helpers\MediaSources\YoutubePlaylist'
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
   * @param string $id    Object type ID
   * @param string $class Full namespace for a class
   */
  public static function set($id, $class)
  {
     self::$manufacturable[strtolower($id)] = $class;
  }

  /**
   * Removes a new manufacturable class
   * 
   * @param string $id  Object type ID
   */
  public static function remove($id)
  {   
    $id = strtolower($id);

    if (isset(self::$manufacturable[$id])) 
        unset(self::$manufacturable[$id]);
  }

  /**
   * Checks if there is a manufacturable with target key
   * 
   * @param  string  $id Target key
   * @return boolean     True if key exists, false otherwise
   */
  public static function canManufacture($id)
  {
    $id = strtolower($id);

    return is_string($id) && isset(self::$manufacturable[$id]) ? true : false;
  }

  /**
   * Returns the id to manufacture another instance of the passed object, if any
   * 
   * @param  object $instance Arg instance
   * @return string           Arg ID 
   */
  public static function getInstanceId($instance)
  {
    if (is_object($instance) && is_a($instance, self::MEDIA_TYPE_CLASS)) {

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
  public static function create($id, array $args = array())
  {
    $id = strtolower($id);

    // Check if target is in the allowed list
    if (array_key_exists($id, self::$manufacturable)) {

      $class_name = self::$manufacturable[$id];

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
      [$args]
    );
        
    // Return object
    return is_a($obj, self::MEDIA_TYPE_CLASS) ? $obj : null;
  }
}