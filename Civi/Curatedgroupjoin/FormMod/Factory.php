<?php

namespace Civi\Curatedgroupjoin\FormMod;

class Factory {

  /**
   * Factory method for instantiating a FormMod object.
   *
   * @return FALSE|object
   *   Object implementing \Civi\Curatedgroupjoin\FormMod\Interface if an
   *   appropriate class can be found, else boolean FALSE.
   */
  public static function create($form) {
    $result = FALSE;

    $class = '\Civi\Curatedgroupjoin\FormMod\\' . str_replace('_', '\\', get_class($form));
    if (class_exists($class) && in_array(IFace::class, class_implements($class))) {
      $result = new $class($form);
    }

    return $result;
  }

}
