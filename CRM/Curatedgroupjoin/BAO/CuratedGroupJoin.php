<?php
use CRM_Curatedgroupjoin_ExtensionUtil as E;

class CRM_Curatedgroupjoin_BAO_CuratedGroupJoin extends CRM_Curatedgroupjoin_DAO_CuratedGroupJoin {

  /**
   * Create a new CuratedGroupJoin based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Curatedgroupjoin_DAO_CuratedGroupJoin|NULL
   *
  public static function create($params) {
    $className = 'CRM_Curatedgroupjoin_DAO_CuratedGroupJoin';
    $entityName = 'CuratedGroupJoin';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
