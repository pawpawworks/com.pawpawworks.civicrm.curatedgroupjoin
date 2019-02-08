<?php

namespace Civi\Curatedgroupjoin\FormMod;

/**
 * Provides an interface for objects which serve as delegates for form-modifying
 * hooks.
 */
interface IFace {

  /**
   * Delegate for hook_civicrm_buildForm().
   */
  public function buildForm();

  /**
   * Delegate for hook_civicrm_postProcess().
   */
  public function postProcess();
}
