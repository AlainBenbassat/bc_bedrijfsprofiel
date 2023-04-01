<?php

namespace Drupal\bc_bedrijfsprofiel;

abstract class Section {
  protected $contactID;
  protected $title;
  protected $intro;
  protected $data;

  public abstract function setTitle();
  public abstract function setIntro();
  public abstract function setData();

  public function __construct($contactID) {
    $this->contactID = $contactID;

    $this->setTitle();
    $this->setIntro();
    $this->setData();
  }

  public function get() {
    return $this->getFormattedTitle()
      . $this->getFormattedIntro()
      . $this->data;
  }

  private function getFormattedTitle() {
    return '<h2>' . $this->title . '</h2>';
  }

  private function getFormattedIntro() {
    if ($this->intro) {
      return '<p class="sectionInfo">' . $this->intro . '</p>';
    }
    else {
      return '';
    }
  }
}
