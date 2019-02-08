<?php

namespace Civi\Curatedgroupjoin\FormMod\CRM\Contribute\Form\Contribution;

use \Civi\Curatedgroupjoin\FormMod as FormMod;

class Main extends FormMod implements FormMod\IFace {

  /**
   * @var array
   *   Extension configs for this form; result of CuratedGroupJoin.getsingle.
   */
  protected $config;

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
   * Delegate of hook_civicrm_buildForm().
   *
   * Injects fields to allow users to select groups to which to subscribe.
   */
  public function buildForm() {
    $groups = $this->getGroupData();
    if (empty($groups)) {
      return;
    }

    $other = $attributes = $required = $javascriptMethod = NULL;
    $separator = '<br />';
    $flipValues = TRUE;
    $this->form->addCheckBox('cgj_groups', $this->config['label'], $groups, $other,
        $attributes, $required, $javascriptMethod, $separator, $flipValues);

    $this->setDefaults();

    $this->addTemplate('page-body');
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
   * @return type
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
   * Delegates processing of user selections depending on whether or not the
   * form uses a confirmation screen.
   */
  public function postProcess() {
    $usesConfirmationPage = (\CRM_Utils_Array::value('is_confirm_enabled', $this->form->_values) === "1");
    if (!$usesConfirmationPage) {
      $this->processGroupSelections();
    }
  }

  /**
   * Un/subscribes user to groups per selections.
   */
  protected function processGroupSelections() {
    $configuredGroups = explode(',', $this->config['group_ids']);
    // The list of groups the user is subscribed to that appear on the form.
    $potentialUnsubscribes = array_intersect($configuredGroups, $this->getUserGroups());

    $selectedGroups = array_keys((array) $this->form->exportValue('cgj_groups'));
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
   *
   * The "IN" operator doesn't work for 'group_id', so instead the list of all
   * of the current user's groups is compared against the list configured for
   * this form.
   */
  protected function setDefaults() {
    $configuredGroups = explode(',', $this->config['group_ids']);
    $defaultToActive = array_intersect($configuredGroups, $this->getUserGroups());
    $this->form->setDefaults([
      'cgj_groups' => array_fill_keys($defaultToActive, 1),
    ]);
  }

}
