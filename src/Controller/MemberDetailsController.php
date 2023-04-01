<?php

namespace Drupal\bc_bedrijfsprofiel\Controller;

use Drupal\bc_bedrijfsprofiel\MemberDetails;
use Drupal\Core\Controller\ControllerBase;

class MemberDetailsController extends ControllerBase {
  public function __construct() {
    \Drupal::service('civicrm')->initialize();
  }

  public function content() {
    $contactID = $this->getContactIdFromQueryParameter();
    if ($contactID) {
      $html = MemberDetails::get($contactID);
    }
    else {
      $html = '<p>Geen ID meegegeven</p>';
    }

    $build = [
      '#markup' => $html,
    ];
    return $build;
  }

  private function getContactIdFromQueryParameter() {
    return \Drupal::request()->query->get('id');
  }
}
