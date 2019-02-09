<?php

namespace Civi\Curatedgroupjoin\FormMod\CRM\Contribute\Form\Contribution;

use \Civi\Curatedgroupjoin\FormMod as FormMod;

class Main extends FormMod\ConfiguredForm implements FormMod\IFace {

  /**
   * Delegate of hook_civicrm_buildForm().
   *
   * Injects fields to allow users to select groups to which to subscribe.
   */
  public function buildForm() {
    if ($this->isAnonUserWithNoConfirmationScreen()) {
      return;
    }

    $groups = $this->getGroupData();
    if (empty($groups)) {
      return;
    }

    $other = $attributes = $required = $javascriptMethod = NULL;
    $separator = '<br />';
    $flipValues = TRUE;
    $this->form->addCheckBox($this->groupFieldName, $this->config['label'], $groups,
        $other, $attributes, $required, $javascriptMethod, $separator, $flipValues);

    $this->setDefaults();

    $this->addTemplate('page-body');
    $this->form->assign('cgj_legend', $this->config['label']);
  }

  /**
   * Used as a killswitch which disables this extension's functions for a
   * particular form in the case of a known edge case where the ID of the
   * contact represented in the form can't be determined.
   *
   * This is a stopgap measure until Issue #1 can be dealt with fully, which
   * will probably require a change to CiviCRM core.
   *
   * @return boolean
   */
  private function isAnonUserWithNoConfirmationScreen() {
    // Note: the session is used rather than $this->getContactId() because the
    // contact ID can be 0 when the user is authenticated, as in the case of
    // submitting on behalf of another user.
    $isAnonUser = (\CRM_Core_Session::getLoggedInContactID() === NULL);
    $usesConfirmationPage = (\CRM_Utils_Array::value('is_confirm_enabled', $this->form->_values) === "1");
    return ($isAnonUser && !$usesConfirmationPage);
  }

  /**
   * Delegates processing of user selections depending on whether or not the
   * form uses a confirmation screen.
   */
  public function postProcess() {
    if ($this->isAnonUserWithNoConfirmationScreen()) {
      return;
    }

    $usesConfirmationPage = (\CRM_Utils_Array::value('is_confirm_enabled', $this->form->_values) === "1");
    if (!$usesConfirmationPage) {
      parent::postProcess();
    }
  }

}
