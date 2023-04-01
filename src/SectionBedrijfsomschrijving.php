<?php

namespace Drupal\bc_bedrijfsprofiel;

class SectionBedrijfsomschrijving extends \Drupal\bc_bedrijfsprofiel\Section {
  public function setTitle() {
    $this->title = 'Bedrijfsomschrijving';
  }

  public function setIntro() {
    $this->intro = '';
  }

  public function setData() {
    $dao = $this->getBedrijfsomschrijving();

    $html = '<table>';
    $html .= "<tr><td>Nederlands (1 slagzin) – wordt voor de ledenpagina op de website gebruikt</td><td>{$dao->activiteiten_nl_25}</td></tr>";
    $html .= "<tr><td>Engels (1 slagzin)</td><td>{$dao->activiteiten_en_26}</td></tr>";
    $html .= '</table>';

    $this->data = $html;
  }

  private function getBedrijfsomschrijving() {
    $sql = "
      select
        o.activiteiten_nl_25,
        o.activiteiten_en_26
      from
        civicrm_contact c
      left outer join
        civicrm_value_organisatie_i_5 o on o.entity_id = c.id
      where
        c.id = %1
      and
        c.is_deleted = 0
      and
        c.contact_type = 'Organization'
    ";
    $sqlParams = [
      1 => [$this->contactID, 'Integer'],
    ];
    $dao = \CRM_Core_DAO::executeQuery($sql, $sqlParams);
    $dao->fetch();

    return $dao;
  }
}
