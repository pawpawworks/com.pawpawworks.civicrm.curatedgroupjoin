<?php

namespace Civi\Curatedgroupjoin\FormMod\CRM\Contribute\Form\ContributionPage;

use \Civi\Curatedgroupjoin\FormMod as FormMod;
use CRM_Curatedgroupjoin_ExtensionUtil as E;

class Custom extends FormMod implements FormMod\IFace {

  /**
   * Delegate of hook_civicrm_buildForm().
   *
   * Injects fields to allow admins to customize the contribution page.
   */
  public function buildForm() {
    $this->form->add('text', 'cgj_label', E::ts('Label'));
    $this->form->addEntityRef('cgj_groups', E::ts('Groups'), [
      'api' => [
        'params' => [
          'is_active' => 1,
          'is_hidden' => 0,
          'saved_search_id' => ['IS NULL' => 1],
          'visibility' => 'Public Pages',
        ]
      ],
      'entity' => 'Group',
      'multiple' => TRUE,
      'placeholder' => E::ts('- Select Group -'),
    ]);

    $this->form->addFormRule([$this, 'validateRequiredFields']);

    $this->setDefaults();

    $this->addTemplate('contribute-form-contributionpage-custom-main');
  }

  /**
   * Delegate of hook_civicrm_postProcess().
   *
   * Processes injected fields.
   */
  public function postProcess() {
    $groups = $this->form->exportValue('cgj_groups');
    $label = $this->form->exportValue('cgj_label');

    // Action is required only if the user made use of the extended feature.
    if (!$label || !$groups) {
      return;
    }

    civicrm_api3('CuratedGroupJoin', 'create', [
      'options' => [
        'match' => ['entity_id', 'form_class'],
      ],
      'entity_id' => $this->form->getVar('_id'),
      'form_class' => \CRM_Contribute_Form_Contribution_Main::class,
      'group_ids' => $groups,
      'label' => $label,
    ]);
  }

  /**
   * Prepopulates injected fields with values from the DB, if they exist.
   */
  private function setDefaults() {
    try {
      $groupConfig = civicrm_api3('CuratedGroupJoin', 'getsingle', [
        'entity_id' => $this->form->getVar('_id'),
        'form_class' => \CRM_Contribute_Form_Contribution_Main::class,
      ]);

      $this->form->setDefaults([
        'cgj_groups' => $groupConfig['group_ids'],
        'cgj_label' => $groupConfig['label'],
      ]);
    }
    catch (CiviCRM_API3_Exception $e) {
      // nothing to do here; the fields will be appropriately blank
    }
  }

  /**
   * Validation callback to ensure that if one injected field is populated, that
   * both are.
   *
   * @param array $values
   *   User-submitted values to validate.
   * @return mixed
   *   Boolean TRUE if valid, else array of errors [field_key => err_msg]
   */
  public static function validateRequiredFields(array $values) {
    $errors = [];

    $labelProvided = (strlen(trim($values['cgj_label'])) > 0);
    $groupsProvided = (strlen(trim($values['cgj_groups'])) > 0);
    if ($labelProvided xor $groupsProvided) {
      $errorField = ($labelProvided ? 'cgj_groups' : 'cgj_label');
      $errors[$errorField] = E::ts('This field is required.');
    }

    return empty($errors) ? TRUE : $errors;
  }

}
