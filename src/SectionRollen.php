<?php

namespace Drupal\bc_bedrijfsprofiel;

class SectionRollen extends \Drupal\bc_bedrijfsprofiel\Section {

  public function setTitle() {
    $this->title = 'Rol in de waardeketen';
  }

  public function setIntro() {
    $this->intro = '';
  }

  public function setData() {
    $dao = $this->getRollen();

    $html = '<table>';
    while ($dao->fetch()) {
      $html .= "<tr><td>{$dao->name}</td>";
      $html .= "<td>{$dao->has_tag}</td>";
      $html .= "<td>{$dao->description}</td></tr>";
    }
    $html .= '</table>';

    $this->data = $html;
  }

  private function getRollen() {
    $sql = "
      select
        t.name
        , (select if(et.id IS NOT NULL, 'x', '') from civicrm_entity_tag et where et.tag_id = t.id and et.entity_id = %1) has_tag
        , t.description
      from
        civicrm_tag t
      where
        parent_id = (select pt.id from civicrm_tag pt where name = 'Rol')
      order by
        t.name
    ";
    $sqlParams = [
      1 => [$this->contactID, 'Integer'],
    ];
    $dao = \CRM_Core_DAO::executeQuery($sql, $sqlParams);

    return $dao;
  }
}

