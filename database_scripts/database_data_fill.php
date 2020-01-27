<?php

// script to fill the databse with data


// connection to the DB
$pdo  = new PDO(
    'mysql:host=localhost;dbname=jo_2024;',
    'root',
    '',
    [
        PDO::ATTR_ERRMODE             => PDO::ERRMODE_WARNING,
        PDO::MYSQL_ATTR_INIT_COMMAND  => 'SET NAMES utf8',
    ]
);


// -------- DATABASE TABLES

// type_etablissement
// pratique_sportive
// etablissement_sportif
// epreuve_olympique


// -------------- files
$sport_facilities_file = file_get_contents('../data/equipements_sportifs.json');
$sport_facilities_json = json_decode($sport_facilities_file, true);

$olympics_file = file_get_contents('../data/epreuves_olympiques.json');
$olympics_json = json_decode($olympics_file, true);

$sport_families_file = file_get_contents('../data/sports_families.json');
$sport_families_json = json_decode($sport_families_file, true);




//------------------- type_etablissement -------------- //

// new data with only id and type once in the array
$facility_type = [];

foreach($sport_facilities_json as $sport_facility) {

    if (isset($sport_facility['fields']['equipementtypecode']) && !in_array( ['id' => $sport_facility['fields']['equipementtypecode'], 'type'=> $sport_facility['fields']['equipementtypelib']] , $facility_type )){
        array_push(  $facility_type, [
            'id'=> $sport_facility['fields']['equipementtypecode'],
            'type'=> $sport_facility['fields']['equipementtypelib']
        ] );
    }


}




foreach($facility_type as $type) {


    $request = $pdo->prepare('INSERT INTO type_etablissement(id, type) VALUES (
:id, :type)');

    $request->bindParam(':id', $type['id']);
    $request->bindParam(':type', $type['type']);
    $request->execute();
}

// DONE : renommer les noms des types d'établissement si trop long !




// ------------------- pratique_sportive --------------- //

$sport_practises = [];

foreach($sport_facilities_json as $sport_facility) {

    if (isset($sport_facility['fields']['actcode']) && !in_array(['id' => $sport_facility['fields']['actcode'], 'name'=> $sport_facility['fields']['actlib']] , $sport_practises)){
        array_push(  $sport_practises, [
            'id'=> $sport_facility['fields']['actcode'],
            'name'=> $sport_facility['fields']['actlib']
        ] );
    }
}


foreach($sport_practises as $practise) {

   // TODO : image name script !!!
    $image_name = 'toto';


    $request = $pdo->prepare('INSERT INTO pratique_sportive(id, pratique, image_nom) VALUES (
:id, :denomination, :image)');

    $request->bindParam(':id', $practise['id']);
    $request->bindParam(':denomination', $practise['name']);
    $request->bindParam(':image', $image_name);
    $request->execute();
}


// DONE: renommer les noms des pratiques sportives quand elles sont trop longues




// -----------  etablissement_sportif ------------- //

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
        $num_voie = (int)$sport_facility['fields']['insnovoie'];

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
        $request->bindParam(':numero', $num_voie);
        $request->bindParam(':nom_voie', $sport_facility['fields']['inslibellevoie']);
        $request->bindParam(':insee_arrondissement', $sport_facility['fields']['insarrondissement']);
        $request->bindParam(':sport', $sport_facility['fields']['actcode']);
        $request->bindParam(':etab_type', $equipement_type_code);
        $request->execute();
    }
}
// ------------------------------ categorie_sport ----------------- //

foreach($sport_families_json as $sport) {

    $request = $pdo->prepare('INSERT INTO categorie_sport(sport_name) VALUES (
:name)');

    $request->bindParam(':name', $sport['sport']);
    $request->execute();
}

// --------------- epreuve_olympique -------------------- //


// TODO :



// --------------- sport_pratiques -------------------- //


// TODO:

foreach($sport_families_json as $sport) {
    foreach ($sport['practices'] as $sport_practice) {
        $db_practise_request = $pdo->prepare('SELECT id from pratique_sportive WHERE pratique === :practice');
        $db_practise_request->bindParam(':practice', $sport['sport']);
        $db_practise_request->execute();
        $db_practise_id = $db_practise_request->fetch(PDO::FETCH_ASSOC);


        $db_sport_family_request = $pdo->prepare('SELECT id from categorie_sport WHERE sport_name === :sport');
        $db_sport_family_request->bindParam(':sport', $sport_practice);
        $db_sport_family_request->execute();
        $db_sport_family_id = $db_sport_family_request->fetch(PDO::FETCH_ASSOC);


        // INSERT INTO
        $request = $pdo->prepare('INSERT INTO sport_pratiques(id, id_categorie_sport) VALUES (
        :id_practice, :id_sport_family)');
        $request->bindParam(':id_practice', $db_practise_id);
        $request->bindParam(':id_sport_family', $sport['sport']);
        $request->execute();

    }
}