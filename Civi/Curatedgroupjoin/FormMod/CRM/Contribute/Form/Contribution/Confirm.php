<?php

namespace Civi\Curatedgroupjoin\FormMod\CRM\Contribute\Form\Contribution;

use \Civi\Curatedgroupjoin\FormMod as FormMod;

class Confirm extends FormMod\ConfiguredForm implements FormMod\IFace {

  public function __construct(\CRM_Core_Form $form) {
    $this->configuredFormClass = \CRM_Contribute_Form_Contribution_Main::class;
    parent::__construct($form);
  }

  /**
   * Delegate of hook_civicrm_buildForm().
   *
   * Injects fields to allow users to select groups to which to subscribe.
   */
  public function buildForm() {
    $label = \CRM_Utils_Array::value('label', $this->config);
    $selectedGroupIds = array_keys(\CRM_Utils_Array::value($this->groupFieldName, $this->form->_params, []));
    if ($label && $selectedGroupIds) {
      $this->addTemplate('page-body');
      $this->form->assign('cgj_label', $label);

      $groupData = $this->getGroupData();
      $selectedGroupTitles = array_intersect_key($groupData, array_flip($selectedGroupIds));
      $this->form->assign('cgj_selected_groups', $selectedGroupTitles);
    }

  }

  public function postProcess() {
    $selectedGroups = array_keys(\CRM_Utils_Array::value($this->groupFieldName, $this->form->_params, []));
    $this->processGroupSelections($selectedGroups);
  }

}
