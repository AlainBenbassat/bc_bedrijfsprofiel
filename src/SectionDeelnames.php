<?php

namespace Drupal\bc_bedrijfsprofiel;

class SectionDeelnames extends \Drupal\bc_bedrijfsprofiel\Section {

  public function setTitle() {
    $this->title = 'Deelname aan evenementen van de Blauwe Cluster';
  }

  public function setIntro() {
    $this->intro = '';
  }

  public function setData() {
    $dao = $this->getDeelnames();

    $html = '<table>';
    $html .= "<tr><th>Evenement</th><th>Evenementdatum</th></tr>";
    while ($dao->fetch()) {
      $html .= "<tr><td>{$dao->title}</td>";
      $html .= "<td>{$dao->start_date}</td>";
    }
    $html .= '</table>';

    $this->data = $html;
  }

  private function getDeelnames() {
    $sql = "
      select
        e.title
        , DATE_FORMAT(e.start_date, '%d-%m-%Y') start_date
      from
        civicrm_contact c
      inner join
        civicrm_participant p on p.contact_id = c.id
      inner join
        civicrm_event e on p.event_id = e.id
      where
        c.id = %1
      and
        c.is_deleted = 0
      and
        p.status_id in (1, 2)
      order by
        e.start_date desc
    ";
    $sqlParams = [
      1 => [$this->contactID, 'Integer'],
    ];
    $dao = \CRM_Core_DAO::executeQuery($sql, $sqlParams);

    return $dao;
  }
}

