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
   * @var int
   *   The contact ID of the user represented in the form, initialized to the
   *   anonymous user.
   */
  private $contactId = 0;

  /**
   * @var string
   *   The name of the form class to which the group configs were applied.
   *   Note that this may vary from the class of $this->form in the case of
   *   confirmation screens, etc.
   */
  protected $configuredFormClass;

  /**
   * @var string
   *   The name to use for the field containing the user's group selections.
   */
  protected $groupFieldName = 'cgj_groups';

  /**
   * @var array
   *   The IDs of groups to which the user is subscribed.
   */
  protected $userGroups = [];

  public function __construct(\CRM_Core_Form $form) {
    parent::__construct($form);

    // Allow overrides from children
    if (!isset($this->configuredFormClass)) {
      $this->configuredFormClass = get_class($this->form);
    }

    try {
      $this->config = civicrm_api3('CuratedGroupJoin', 'getsingle', [
        'entity_id' => $this->form->getVar('_id'),
        'form_class' => $this->configuredFormClass,
      ]);
    }
    catch (\CiviCRM_API3_Exception $e) {
      // nothing to do here; form not configured for use with this extension
    }
  }

  /**
   * @return int
   *   Returns 0 if the contact ID cannot be determined or the user is anonymous.
   */
  protected function getContactId() {
    if (($this->contactId === 0) && isset($this->form->_contactID)) {
      $this->contactId = (int) $this->form->_contactID;
    }
    if (($this->contactId === 0) && isset($this->form->_params['select_contact_id'])) {
      $this->contactId = (int) $this->form->_params['select_contact_id'];
    }
    return $this->contactId;
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
    // Checking to see if the user is authenticated keeps the class unaware of
    // an unauthenticated contact's pre-existing group subscriptions throughout
    // the form lifecycle. (CiviCRM's dedupe rules may cause the contact ID --
    // and by extension the group associations -- of the unauthenticated contact
    // to become known, which could result in unintended group unsubscription.
    // See Issue #2.)
    $isAuthenticated = (\CRM_Core_Session::getLoggedInContactID() !== NULL);
    if (empty($this->userGroups) && $isAuthenticated && $this->getContactId()) {
      $apiResult = civicrm_api3('GroupContact', 'get', [
        'contact_id' => $this->getContactId(),
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
    $selectedGroups = array_keys((array) $this->form->exportValue($this->groupFieldName));
    $this->processGroupSelections($selectedGroups);
  }

  /**
   * Helper function to un/subscribe user to groups per selections.
   *
   * This is broken out from postProcess to isolate the processing logic from
   * the extraction of the submission from the form object, which varies
   * according to the class (e.g., submission vs. confirmation screens).
   *
   * @param array $groupSelections
   *   Group IDs that the user selected.
   */
  protected function processGroupSelections(array $groupSelections) {
    $configuredGroups = explode(',', \CRM_Utils_Array::value('group_ids', $this->config));
    // The list of groups the user is subscribed to that appear on the form.
    $potentialUnsubscribes = array_intersect($configuredGroups, $this->getUserGroups());

    $unsubscribes = array_diff($potentialUnsubscribes, $groupSelections);

    $groupContactParams = [
      'options' => [
        'match' => ['group_id', 'contact_id'],
      ],
      'contact_id' => $this->getContactId(),
      'status' => 'Removed',
    ];
    foreach ($unsubscribes as $groupId) {
      $groupContactParams['group_id'] = $groupId;
      civicrm_api3('GroupContact', 'create', $groupContactParams);
    }

    $groupContactParams['status'] = 'Added';
    foreach ($groupSelections as $groupId) {
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
