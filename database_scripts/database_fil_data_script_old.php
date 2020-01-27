
foreach($sport_facilities_json as $sport_facility) {

// keep data only if has address + facility type + sport practice

if (isset($sport_facility['fields']['insnovoie'])
&& isset($sport_facility['fields']['inslibellevoie'])
&& isset($sport_facility['fields']['insarrondissement'])
&& isset($sport_facility['fields']['actcode'])
&& isset($sport_facility['fields']['equipementtypecode'])
) {

if (isset($sport_facility['fields']['equacceshandimaire']) && $sport_facility['fields']['equacceshandimaire'] === 'Oui') {
$mob_aire = 1;
} else {
$mob_aire = 0;
}

if (isset($sport_facility['fields']['equacceshandisaire']) && $sport_facility['fields']['equacceshandisaire'] === 'Oui') {
$sens_aire = 1;
} else {
$sens_aire = 0;
}

if (isset($sport_facility['fields']['equacceshandisvestiaire']) && $sport_facility['fields']['equacceshandisvestiaire'] === 'Oui') {
$sens_vestiaire = 1;
} else {
$sens_vestiaire = 0;
}

if (isset($sport_facility['fields']['equacceshandimvestiaire']) && $sport_facility['fields']['equacceshandimvestiaire'] === 'Oui') {
$mob_vestiaire = 1;
} else {
$mob_vestiaire = 0;
}

if (isset($sport_facility['fields']['equnatimhandi']) && $sport_facility['fields']['equnatimhandi'] == 0) {
$mob_nat = 1;
} else {
$mob_nat = 0;
}

if (isset($sport_facility['fields']['equacceshandimsanispo']) && $sport_facility['fields']['equacceshandimsanispo'] === 'Oui') {
$mob_sanitaire = 1;
} else {
$mob_sanitaire = 0;
}

if (isset($sport_facility['fields']['equacceshandissanispo']) && $sport_facility['fields']['equacceshandissanispo'] === 'Oui') {
$sens_sanitaire = 1;
} else {
$sens_sanitaire = 0;
}

// practice type renaming

if (isset($sport_facility['fields']['actnivlib']) && ($sport_facility['fields']['actnivlib'] === 'Compétition nationale' || $sport_facility['fields']['actnivlib'] === 'Compétition départementale' || $sport_facility['fields']['actnivlib'] === 'Compétition internationale')) {
$practice_level = 'Compétition';
} else if (isset($sport_facility['fields']['actnivlib']) && $sport_facility['fields']['actnivlib'] === 'Loisir - Entretien - Remise en forme') {
$practice_level = 'Loisir';
} else if (!isset($sport_facility['fields']['actnivlib'])){
$practice_level = '';
}

$nom_etab = isset($sport_facility['fields']['insnom']) ? $sport_facility['fields']['insnom'] : '';
$equipement_type_code = (int)$sport_facility['fields']['equipementtypecode'];

$request = $pdo->prepare('INSERT INTO etablissement_sportif(
type_pratique,
acces_handicap_mobilite_aire_sportive,
acces_handicap_sensoriel_aire_sportive,
acces_handicap_sensoriel_vestiaire,
acces_handicap_mobilite_vestiaire,
acces_handicap_mobilite_natation,
acces_handicap_sensoriel_sanitaire,
acces_handicap_mobilite_sanitaire,
nom_etablissement,
adresse_num,
adresse_voie,
insee_arrondissement,
id_pratique_sportive,
id_type_etablissement) VALUES (
:type_pratique,
:mob_aire,
:sens_aire,
:sens_vestiaire,
:mob_vestiaire,
:mob_natation,
:sens_sanitaire,
:mob_sanitaire,
:nom_etab,
:numero,
:nom_voie,
:insee_arrondissement,
:sport,
:etab_type)');

$request->bindParam(':type_pratique', $practice_level);
$request->bindParam(':mob_aire', $mob_aire);
$request->bindParam(':sens_aire', $sens_aire);
$request->bindParam(':sens_vestiaire', $sens_vestiaire);
$request->bindParam(':mob_vestiaire', $mob_vestiaire);
$request->bindParam(':mob_natation', $mob_nat);
$request->bindParam(':sens_sanitaire', $sens_sanitaire);
$request->bindParam(':mob_sanitaire', $mob_sanitaire);
$request->bindParam(':nom_etab', $nom_etab);
$request->bindParam(':numero', $sport_facility['fields']['insnovoie']);
$request->bindParam(':nom_voie', $sport_facility['fields']['inslibellevoie']);
$request->bindParam(':insee_arrondissement', $sport_facility['fields']['insarrondissement']);
$request->bindParam(':sport', $sport_facility['fields']['actcode']);
$request->bindParam(':etab_type', $equipement_type_code);
$request->execute();
}
}

