<?php

namespace Drupal\bc_bedrijfsprofiel;

class SectionSamenwerkingen extends \Drupal\bc_bedrijfsprofiel\Section {

  public function setTitle() {
    $this->title = 'Betrokkenheid in samenwerkingsinitiatieven';
  }

  public function setIntro() {
    $this->intro = '';
  }

  public function setData() {
    $dao = $this->getSamenwerkingen();

    $html = '<table>';
    $html .= "<tr><th>Type samenwerking (dossiertype)</th><th>Naam dossier</th><th>Partners</th><th>Status</th></tr>";
    while ($dao->fetch()) {
      $html .= "<tr><td>{$dao->title} (geleid door {$dao->case_org})</td>";
      $html .= "<td>{$dao->subject}</td>";
      $html .= "<td>{$dao->partners}</td>";
      $html .= "<td>{$dao->status}</td></tr>";
    }
    $html .= '</table>';

    $this->data = $html;
  }

  private function getSamenwerkingen() {
    $sql = "
      select
        c.subject
        , ct.title
        , group_concat(org.organization_name separator ', ') partners
        , v.label status
        , corg.organization_name case_org
      from
        civicrm_case c
      inner join
        civicrm_case_type ct on c.case_type_id = ct.id
      inner join
        civicrm_case_contact cc on c.id = cc.case_id
      inner join
        civicrm_contact corg on corg.id = cc.contact_id
      inner join
        civicrm_relationship r on r.case_id = c.id and r.relationship_type_id = 19
      left outer join
        civicrm_contact org on org.id = r.contact_id_b
      left outer join
        civicrm_option_value v on v.value = c.status_id and v.option_group_id = 28
      where
        corg.is_deleted = 0
      and (
        c.id in (select case_rels.case_id from civicrm_relationship case_rels where case_rels.contact_id_b = %1 and case_rels.relationship_type_id = 19)
      or
        cc.contact_id = %1)
      group by
        c.subject
        , ct.title
        , v.label
        , corg.organization_name
      order by
        org.organization_name
    ";
    $sqlParams = [
      1 => [$this->contactID, 'Integer'],
    ];
    $dao = \CRM_Core_DAO::executeQuery($sql, $sqlParams);

    return $dao;
  }
}

