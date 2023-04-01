<?php

namespace Drupal\bc_bedrijfsprofiel;

class SectionWorkshops extends \Drupal\bc_bedrijfsprofiel\Section {

  public function setTitle() {
    $this->title = 'Workshops';
  }

  public function setIntro() {
    $this->intro = 'Onderstaande personen worden, naast de primaire contactpersoon, uitgenodigd voor (thematische) workshops.';
  }

  public function setData() {
    $dao = $this->getWorkshops();

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

  private function getWorkshops() {
    $sql = "
      select
        c.first_name
        , c.last_name
        , e.email
      from
        civicrm_contact c
      inner join
        civicrm_entity_tag et on et.entity_id = c.id and et.entity_table = 'civicrm_contact' and et.tag_id = (select t.id from civicrm_tag t where t.name = 'workshops')
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

