<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2018
 *
 * Generated from /extension-root/xml/schema/CRM/Curatedgroupjoin/CuratedGroupJoin.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:ea508f46abf7e505741c61028d4b4137)
 */

/**
 * Database access object for the CuratedGroupJoin entity.
 */
class CRM_Curatedgroupjoin_DAO_CuratedGroupJoin extends CRM_Core_DAO {

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  static $_tableName = 'civicrm_curated_group_join';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  static $_log = TRUE;

  /**
   * Unique CuratedGroupJoin ID
   *
   * @var int unsigned
   */
  public $id;

  /**
   * The class of form affected by this configuration.
   *
   * @var string
   */
  public $form_class;

  /**
   * The ID of the form affected by this configuration.
   *
   * @var int unsigned
   */
  public $entity_id;

  /**
   * A heading to display before the group listing.
   *
   * @var string
   */
  public $label;

  /**
   * A comma-separated list of group IDs to display.
   *
   * @var string
   */
  public $group_ids;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_curated_group_join';
    parent::__construct();
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => ts('Unique CuratedGroupJoin ID'),
          'required' => TRUE,
          'table_name' => 'civicrm_curated_group_join',
          'entity' => 'CuratedGroupJoin',
          'bao' => 'CRM_Curatedgroupjoin_DAO_CuratedGroupJoin',
          'localizable' => 0,
        ],
        'form_class' => [
          'name' => 'form_class',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Form Class'),
          'description' => ts('The class of form affected by this configuration.'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_curated_group_join',
          'entity' => 'CuratedGroupJoin',
          'bao' => 'CRM_Curatedgroupjoin_DAO_CuratedGroupJoin',
          'localizable' => 0,
        ],
        'entity_id' => [
          'name' => 'entity_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => ts('The ID of the form affected by this configuration.'),
          'required' => TRUE,
          'table_name' => 'civicrm_curated_group_join',
          'entity' => 'CuratedGroupJoin',
          'bao' => 'CRM_Curatedgroupjoin_DAO_CuratedGroupJoin',
          'localizable' => 0,
        ],
        'label' => [
          'name' => 'label',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Label'),
          'description' => ts('A heading to display before the group listing.'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_curated_group_join',
          'entity' => 'CuratedGroupJoin',
          'bao' => 'CRM_Curatedgroupjoin_DAO_CuratedGroupJoin',
          'localizable' => 0,
        ],
        'group_ids' => [
          'name' => 'group_ids',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Group Ids'),
          'description' => ts('A comma-separated list of group IDs to display.'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_curated_group_join',
          'entity' => 'CuratedGroupJoin',
          'bao' => 'CRM_Curatedgroupjoin_DAO_CuratedGroupJoin',
          'localizable' => 0,
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'curated_group_join', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'curated_group_join', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [
      'index_entity_id' => [
        'name' => 'index_entity_id',
        'field' => [
          0 => 'entity_id',
        ],
        'localizable' => FALSE,
        'sig' => 'civicrm_curated_group_join::0::entity_id',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
