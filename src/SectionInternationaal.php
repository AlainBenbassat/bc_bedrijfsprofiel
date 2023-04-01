<?php

namespace Drupal\bc_bedrijfsprofiel;

class SectionInternationaal extends \Drupal\bc_bedrijfsprofiel\Section {

  public function setTitle() {
    $this->title = 'Internationaal';
  }

  public function setIntro() {
    $this->intro = '';
  }

  public function setData() {
    $dao = $this->getInternationaal();

    $html = '<table>';
    $html .= "<tr><td>Hoofdkantoor of filiaal</td><td>{$dao->filiaal}</td></tr>";
    $html .= "<tr><td>Internationaal actief?</td><td>{$dao->internationaal}</td></tr>";
    $html .= "<tr><td>Huidige exportlanden voor blauwe economie activiteiten</td><td>{$dao->huidige_export_landen}</td></tr>";
    $html .= "<tr><td>Welke nieuwe landen staan in uw top 3 voor prospectie</td><td>{$dao->toekomstige_export_landen}</td></tr>";
    $html .= '</table>';

    $this->data = $html;
  }

  private function getInternationaal() {
    $sql = "
      select
        filiaal.name filiaal
        , if(o.export_internationaal_actief__30=1,'Ja',if(o.export_internationaal_actief__30=0,'Nee','')) internationaal
        , '' huidige_export_landen
        , '' toekomstige_export_landen
      from
        civicrm_contact c
      left outer join
        civicrm_value_organisatie_i_5 o on o.entity_id = c.id
      left outer join
        civicrm_option_value filiaal on filiaal.value = o.hoofdkantoor_of_filiaal_28 and filiaal.option_group_id = 101
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

