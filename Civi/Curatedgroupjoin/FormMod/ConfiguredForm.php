<?php

namespace Civi\Curatedgroupjoin\FormMod;

/**
 * Provides the basis for form modification classes which have been configured
 * to offer end users the ability to sign up for groups.
 */
abstract class ConfiguredForm extends \Civi\Curatedgroupjoin\FormMod {

  /**
   * @var array
   *   Extension configs for this form; result of CuratedGroupJoin.getsingle.
   */
  protected $config;

  /**
   * @var string
   *   The name to use for the field containing the user's group selections.
   */
  protected $groupFieldName = 'cgj_groups';

  /**
   * @var array
   *   The IDs of groups to which the user is subscribed.
   */
  protected $userGroups;

  public function __construct(\CRM_Core_Form $form) {
    parent::__construct($form);

    try {
      $this->config = civicrm_api3('CuratedGroupJoin', 'getsingle', [
        'entity_id' => $this->form->getVar('_id'),
        'form_class' => get_class($this->form),
      ]);
    }
    catch (\CiviCRM_API3_Exception $e) {
      // nothing to do here; form not configured for use with this extension
    }
  }

  /**
   * Gets data for groups configured for use with this form.
   *
   * @return array
   */
  protected function getGroupData() {
    if (!isset($this->config)) {
      return [];
    }

    // Get enough group information to build a checkbox list while ensuring that
    // the original selections are still valid.
    $groups = civicrm_api3('Group', 'get', [
          'id' => ['IN' => explode(',', $this->config['group_ids'])],
          'is_active' => 1,
          'is_hidden' => 0,
          'saved_search_id' => ['IS NULL' => 1],
          'visibility' => 'Public Pages',
        ])['values'];

    return array_column($groups, 'title', 'id');
  }

  /**
   * Gets the IDs of the groups to which the user is subscribed.
   *
   * @return array
   */
  protected function getUserGroups() {
    if (!isset($this->userGroups)) {
      $apiResult = civicrm_api3('GroupContact', 'get', [
        'contact_id' => 'user_contact_id', // i.e., the current user
        'sequential' => 0,
        'status' => 'Added',
      ]);
      $this->userGroups = array_column($apiResult['values'], 'group_id');
    }
    return $this->userGroups;
  }

  /**
   * Delegate of hook_civicrm_postProcess().
   *
   * Un/subscribes user to groups per selections.
   */
  public function postProcess() {
    $configuredGroups = explode(',', \CRM_Utils_Array::value('group_ids', $this->config));
    // The list of groups the user is subscribed to that appear on the form.
    $potentialUnsubscribes = array_intersect($configuredGroups, $this->getUserGroups());

    $selectedGroups = array_keys((array) $this->form->exportValue($this->groupFieldName));
    $unsubscribes = array_diff($potentialUnsubscribes, $selectedGroups);

    $groupContactParams = [
      'options' => [
        'match' => ['group_id', 'contact_id'],
      ],
      'contact_id' => 'user_contact_id',
      'status' => 'Removed',
    ];
    foreach ($unsubscribes as $groupId) {
      $groupContactParams['group_id'] = $groupId;
      civicrm_api3('GroupContact', 'create', $groupContactParams);
    }

    $groupContactParams['status'] = 'Added';
    foreach ($selectedGroups as $groupId) {
      $groupContactParams['group_id'] = $groupId;
      civicrm_api3('GroupContact', 'create', $groupContactParams);
    }
  }

  /**
   * Pre-selects groups the users is already a member of.
   */
  protected function setDefaults() {
    $configuredGroups = explode(',', \CRM_Utils_Array::value('group_ids', $this->config));
    $defaultToActive = array_intersect($configuredGroups, $this->getUserGroups());
    $this->form->setDefaults([
      $this->groupFieldName => array_fill_keys($defaultToActive, 1),
    ]);
  }

}
