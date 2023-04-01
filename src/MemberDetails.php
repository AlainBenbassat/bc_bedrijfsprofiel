<?php

namespace Drupal\bc_bedrijfsprofiel;

class MemberDetails {
  public static function get($contactID) {
    $sectionAlgemeneGegevens = new SectionAlgemeneGegevens($contactID);
    $sectionBedrijfsomschrijving = new SectionBedrijfsomschrijving($contactID);
    $sectionContactgegevensPrimaireContactpersoon = new SectionContactgegevensPrimaireContactpersoon($contactID);
    $sectionContactgegevensCEO = new SectionContactgegevensCEO($contactID);
    $sectionPrivacy = new SectionPrivacy($contactID);
    $sectionNetwerkevenementen = new SectionNetwerkevenementen($contactID);
    $sectionWorkshops = new SectionWorkshops($contactID);
    $sectionInternationaal = new SectionInternationaal($contactID);
    $sectionInteressedomeinen = new SectionInteressedomeinen($contactID);
    $sectionRollen = new SectionRollen($contactID);
    $sectionSamenwerkingen = new SectionSamenwerkingen($contactID);
    $sectionDeelnames = new SectionDeelnames($contactID);
    $sectionTelefoongesprekken = new SectionTelefoongesprekken($contactID);

    return
      $sectionAlgemeneGegevens->get()
      . $sectionBedrijfsomschrijving->get()
      . $sectionContactgegevensPrimaireContactpersoon->get()
      . $sectionContactgegevensCEO->get()
      . $sectionNetwerkevenementen->get()
      . $sectionWorkshops->get()
      . $sectionInternationaal->get()
      . $sectionInteressedomeinen->get()
      . $sectionRollen->get()
      . $sectionSamenwerkingen->get()
      . $sectionDeelnames->get()
      . $sectionTelefoongesprekken->get()
      . $sectionPrivacy->get();
  }
}
