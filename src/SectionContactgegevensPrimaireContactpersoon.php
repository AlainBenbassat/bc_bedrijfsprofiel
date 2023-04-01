<?php

namespace Drupal\bc_bedrijfsprofiel;

class SectionContactgegevensPrimaireContactpersoon extends \Drupal\bc_bedrijfsprofiel\Section {
  public function setTitle() {
    $this->title = 'Contactgegevens primaire contactpersoon';
  }

  public function setIntro() {
    $this->intro = "De primaire contactpersoon zal alle communicatie rond events & workshops automatisch ontvangen. Ook algemene vragen zoals logo’s, vragen omtrent de facturatie zullen aan deze contactpersoon gericht worden.";
  }

  public function setData() {
    $dao = $this->getContactgegevensPrimaireContactpersoon();

    $html = '<table>';
    $html .= "<tr><td>Voornaam en naam</td><td>{$dao->naam}</td></tr>";
    $html .= "<tr><td>Functie</td><td>{$dao->job_title}</td></tr>";
    $html .= "<tr><td>Telefoonnummer</td><td>{$dao->work_phone}</td></tr>";
    $html .= "<tr><td>Gsm-nummer</td><td>{$dao->mobile_phone}</td></tr>";
    $html .= "<tr><td>E-mailadres</td><td>{$dao->email}</td></tr>";
    $html .= '</table>';

    $this->data = $html;
  }

  private function getContactgegevensPrimaireContactpersoon() {
    $sql = "
        select
          concat(c.first_name, ' ', c.last_name) naam
          , c.job_title
          , e.email
          , tel_work.phone work_phone
          , tel_mobile.phone mobile_phone
        from
          civicrm_contact c
        inner join
          civicrm_entity_tag et on et.entity_id = c.id and et.entity_table = 'civicrm_contact' and et.tag_id = (select t.id from civicrm_tag t where t.name = 'Primary contact')
        left outer join
          civicrm_email e on e.contact_id = c.id and e.is_primary = 1
        left outer join
          civicrm_phone tel_work on tel_work.contact_id = c.id and tel_work.location_type_id = 2 and tel_work.phone_type_id = 1
        left outer join
          civicrm_phone tel_mobile on tel_mobile.contact_id = c.id and tel_mobile.phone_type_id = 2
        where
          c.employer_id = %1
        and
          c.is_deleted = 0
        and
          c.is_deceased = 0
      ";
    $sqlParams = [
      1 => [$this->contactID, 'Integer'],
    ];
    $dao = \CRM_Core_DAO::executeQuery($sql, $sqlParams);
    $dao->fetch();

    return $dao;
  }
}
