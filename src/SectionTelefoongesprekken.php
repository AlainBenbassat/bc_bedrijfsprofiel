<?php

namespace Drupal\bc_bedrijfsprofiel;

class SectionTelefoongesprekken extends \Drupal\bc_bedrijfsprofiel\Section {

  public function setTitle() {
    $this->title = 'Telefoongesprekken/meetings';
  }

  public function setIntro() {
    $this->intro = '';
  }

  public function setData() {
    $dao = $this->getTelefoongesprekken();

    $html = '<table>';
    $html .= "<tr><th>Datum</th><th>Type</th><th>Onderwerp</th></tr>";
    while ($dao->fetch()) {
      $html .= "<tr><td>{$dao->activity_date}</td>";
      $html .= "<td>{$dao->activity_type}</td>";
      $html .= "<td>{$dao->subject}</td></tr>";
    }
    $html .= '</table>';

    $this->data = $html;
  }

  private function getTelefoongesprekken() {
    $sql = "
      select
        a.subject
        , v.label activity_type
        , DATE_FORMAT(a.activity_date_time, '%d-%m-%Y') activity_date
      from
        civicrm_activity a
      inner join
        civicrm_option_value v on v.value = a.activity_type_id and v.option_group_id = 2
      where
        a.activity_type_id  in (1,2)
      and
        a.id in (select ac.activity_id from civicrm_activity_contact ac where ac.contact_id = %1)
      order by
        a.activity_date_time desc
    ";
    $sqlParams = [
      1 => [$this->contactID, 'Integer'],
    ];
    $dao = \CRM_Core_DAO::executeQuery($sql, $sqlParams);

    return $dao;
  }
}

