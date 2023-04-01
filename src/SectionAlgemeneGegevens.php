<?php

namespace Drupal\bc_bedrijfsprofiel;

class SectionAlgemeneGegevens extends \Drupal\bc_bedrijfsprofiel\Section {
  public function setTitle() {
    $this->title = 'Algemene gegevens';
  }

  public function setIntro() {
    $this->intro = '';
  }

  public function setData() {
    $dao = $this->getAlgemeneGegevens();

    $html = '<table>';
    $html .= "<tr><td>Bedrijfsnaam</td><td>{$dao->organization_name}</td></tr>";
    $html .= "<tr><td>Adres (straat, nr, postcode, stad)</td><td>{$dao->street_address}<br>";
    $html .= $dao->supplemental_address_1 ? "{$dao->supplemental_address_1}<br>" : '';
    $html .= "{$dao->postal_code} {$dao->city}</td></tr>";
    $html .= "<tr><td>Algemeen Telefoonnummer</td><td>{$dao->phone}</td></tr>";
    $html .= "<tr><td>Algemeen E-mailadres</td><td>{$dao->email}</td></tr>";
    $html .= "<tr><td>Website</td><td>{$dao->website}</td></tr>";
    $html .= "<tr><td>Aantal werknemers</td><td>{$dao->aantal_werknemers}</td></tr>";
    $html .= "<tr><td>Jaarlijkse omzet</td><td>{$dao->jaarlijkse_omzet}</td></tr>";
    $html .= "<tr><td>Percentage omzet 'blauw'</td><td>{$dao->percentage_omzet_blauw}</td></tr>";
    $html .= "<tr><td>Balanstotaal</td><td>{$dao->balanstotaal}</td></tr>";
    $html .= "<tr><td>Grootte (GO, MO, KO)</td><td>{$dao->grootte}</td></tr>";
    $html .= "<tr><td>BTW nummer</td><td>{$dao->btw_nummer}</td></tr>";
    $html .= "<tr><td>Publiek of privaat</td><td>{$dao->publiek_prive}</td></tr>";
    $html .= "<tr><td>Type lidmaatschap</td><td>{$dao->type_lidmaatschap}</td></tr>";
    $html .= "<tr><td>Herkomst lidmaatschap</td><td>{$dao->herkomst_lidmaatschap}</td></tr>";
    $html .= "<tr><td>Lid sinds</td><td>{$dao->lid_sinds}</td></tr>";
    $html .= '</table>';

    $this->data = $html;
  }

  private function getAlgemeneGegevens() {
    $sql = "
      select
        c.organization_name,
        a.street_address,
        a.supplemental_address_1,
        a.postal_code,
        a.city,
        p.phone,
        w.url website,
        e.email,
        '' aantal_werknemers,
        '' jaarlijkse_omzet,
        '' balanstotaal,
        o.percentage_omzet_blauw__31 percentage_omzet_blauw,
        grootte.label grootte,
        b.btw_nummer_1 btw_nummer,
        pubpriv.label publiek_prive,
        mt.name type_lidmaatschap,
        m.source herkomst_lidmaatschap,
        m.start_date lid_sinds
      from
        civicrm_contact c
      left outer join
        civicrm_membership m on m.contact_id = c.id and m.status_id in (1, 2, 3)
      left outer join
        civicrm_membership_type mt on mt.id = m.membership_type_id
      left outer join
        civicrm_address a on a.contact_id = c.id and a.is_primary = 1
      left outer join
        civicrm_phone p on p.contact_id = c.id and p.is_primary = 1
      left outer join
        civicrm_email e on e.contact_id = c.id and e.is_primary = 1
      left outer join
        civicrm_website w on w.contact_id = c.id and w.website_type_id = 1
      left outer join
        civicrm_value_organisatie_i_5 o on o.entity_id = c.id
      left outer join
        civicrm_option_value grootte on grootte.value = o.grootte_27 and grootte.option_group_id = 100
      left outer join
        civicrm_value_boekhouding_1  b on b.entity_id  = c.id
      left outer join
        civicrm_option_value pubpriv on pubpriv.value = o.publiek_of_privaat__50 and pubpriv.option_group_id = 109
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
