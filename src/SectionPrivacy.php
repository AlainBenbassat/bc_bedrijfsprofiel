<?php

namespace Drupal\bc_bedrijfsprofiel;

class SectionPrivacy extends \Drupal\bc_bedrijfsprofiel\Section {

  public function setTitle() {
    $this->title = 'Privacy';
  }

  public function setIntro() {
    $this->intro = "De Blauwe Cluster vzw conformeert aan de nieuwe Algemene Verordening Gegevensbescherming. Je hebt een wettelijk recht om toegang tot, correctie en verwijdering van je persoonlijke gegevens te vragen. Je hebt ook het recht om de verwerking van je persoonlijke informatie te beperken.";
  }

  public function setData() {
    $html = '<p>[&nbsp;&nbsp;] Ik ga akkoord dat DBC de gegevens van dit formulier bijhoudt in de eigen database.<br>';
    $html .= '<br><br>[Plaats]<br><br>';
    $html .= '<br><br>[Datum]<br><br>';
    $html .= '<br><br>[Handtekening]<br><br></p>';

    $this->data = $html;
  }
}

