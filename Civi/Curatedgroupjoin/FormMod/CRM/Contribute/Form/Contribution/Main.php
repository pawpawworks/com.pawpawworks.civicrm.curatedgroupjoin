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
   * Delegates processing of user selections depending on whether or not the
   * form uses a confirmation screen.
   */
  public function postProcess() {
    $usesConfirmationPage = (\CRM_Utils_Array::value('is_confirm_enabled', $this->form->_values) === "1");
    if (!$usesConfirmationPage) {
      parent::postProcess();
    }
  }

}
