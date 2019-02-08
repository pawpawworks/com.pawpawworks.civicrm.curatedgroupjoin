<?php

/**
 * This file contains hook implementations specific to this extension's
 * behavior. Civix-generated hook implementations primarily related to
 * the extension lifecycle are located in curatedgroupjoin.hooks.php.
 */
use Civi\Curatedgroupjoin\FormMod\Factory;

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_buildForm/
 */
function curatedgroupjoin_civicrm_buildForm($formName, &$form) {
  $formMod = Factory::create($form);
  if ($formMod) {
    $formMod->buildForm();
  }
}

/**
 * Implements hook_civicrm_postProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postProcess/
 */
function curatedgroupjoin_civicrm_postProcess($formName, &$form) {
  $formMod = Factory::create($form);
  if ($formMod) {
    $formMod->postProcess();
  }
}
