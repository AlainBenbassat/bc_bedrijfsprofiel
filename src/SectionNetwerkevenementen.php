<?php

namespace Drupal\bc_bedrijfsprofiel;

class SectionNetwerkevenementen extends \Drupal\bc_bedrijfsprofiel\Section {

  public function setTitle() {
    $this->title = 'Netwerkevenementen';
  }

  public function setIntro() {
    $this->intro = 'Onderstaande personen zullen, naast de primaire contactpersoon en de CEO, uitgenodigd voor netwerkevents';
  }

  public function setData() {
    $dao = $this->getNetwerkevenementen();

    $html = '<table>';
    $html .= "<tr><th>Voornaam</th><th>Naam</th><th>E-mailadres</th></tr>";
    while ($dao->fetch()) {
      $html .= "<tr><td>{$dao->first_name}</td>";
      $html .= "<td>{$dao->last_name}</td>";
      $html .= "<td>{$dao->email}</td></tr>";
    }
    $html .= '</table>';

    $this->data = $html;
  }

  private function getNetwerkevenementen() {
    $sql = "
      select
        c.first_name
        , c.last_name
        , e.email
      from
        civicrm_contact c
      inner join
        civicrm_entity_tag et on et.entity_id = c.id and et.entity_table = 'civicrm_contact' and et.tag_id = (select t.id from civicrm_tag t where t.name = 'netwerkevents')
      left outer join
        civicrm_email e on e.contact_id = c.id and e.is_primary = 1
      where
        c.employer_id = %1
      and
        c.is_deleted = 0
      and
        c.is_deceased = 0
      order by
        c.sort_name
    ";
    $sqlParams = [
      1 => [$this->contactID, 'Integer'],
    ];
    $dao = \CRM_Core_DAO::executeQuery($sql, $sqlParams);

    return $dao;
  }
}

