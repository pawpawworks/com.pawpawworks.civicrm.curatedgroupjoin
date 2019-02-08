<?php

namespace Civi\Curatedgroupjoin;

abstract class FormMod {

  /**
   * @var \CRM_Core_Form
   */
  protected $form;

  public function __construct(\CRM_Core_Form $form) {
    $this->form = $form;
  }

  /**
   * Adds a template named for the class and region to the specific page region.
   *
   * For example: CRM/Full/Class/Name.region.tpl
   *
   * @param string $region
   */
  protected function addTemplate($region) {
    $tpl = str_replace('\\', '/', get_class($this)) . ".$region.tpl";

    \CRM_Core_Region::instance($region)->add(array(
      'template' => $tpl,
    ));
  }

}
