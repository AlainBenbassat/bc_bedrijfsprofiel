<?php

namespace Drupal\bc_bedrijfsprofiel\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\bc_bedrijfsprofiel\MemberList;

class MemberListController extends ControllerBase {
  public function __construct() {
    \Drupal::service('civicrm')->initialize();
  }

  public function content() {
    $build = [
      '#markup' => MemberList::get(),
    ];
    return $build;
  }
}
