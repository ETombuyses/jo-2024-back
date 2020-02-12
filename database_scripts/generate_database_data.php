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


// -------- DATABASE TABLES ----------------
// sports_practice
// facility_type
// sports_family
// arrondissement
// sports_facility
// olympic_event
// facility_type_association
// facility_practice_association
// sports_family_practice_association



// ---------------- reused functions --------------------------

function getFileJson($file_name) {
    $file_path = '../data/' . $file_name;
    $file = file_get_contents($file_path);
    $json = json_decode($file, true);
    return $json;
} 

function shorten_string($string, $character_to_cut_from) {
    if (strpos($string, $character_to_cut_from) !== false) {
        $string = substr($string, 0, strpos($string, $character_to_cut_from));
    }
    return $string;
}

function format_image_name($image_name) {
    $special_characters_replacement = ['Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
    'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
    'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
    'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
    'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', ' ' => '-', "'" => '-', ',' => ''];

    $image_name = strtr($image_name, $special_characters_replacement);
    $image_name = strtolower($image_name);
    $image_name = trim($image_name);
    return $image_name;
}

function convert_into_boolean($source, $field, $true_statement) {
    if (isset($source[$field]) && $source[$field] === $true_statement) {
        $boolean = 1;
    } else {
        $boolean = 0;
    }
    return $boolean;
}


// -------------- files --------

$arrondissements_json = getFileJson('arrondissements.json');
$sports_facilities_json = getFileJson('sports_facilities.json');
$olympics_json = getFileJson('olympic_events.json');
$sports_families_json = getFileJson('sports_families.json');

// ----------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------


// ------------------- sports_practice ---------------------------------------------------- //

$sports_practices = [];

// fill the $sports_practices array with one occurence of each sports practice

foreach($sports_facilities_json as $sports_facility) {
    if (isset($sports_facility['fields']['actcode']) && !in_array(['id' => $sports_facility['fields']['actcode'], 'practice_name'=> $sports_facility['fields']['actlib']], $sports_practices)){
        array_push(  $sports_practices, [
            'id'=> $sports_facility['fields']['actcode'],
            'practice_name'=> $sports_facility['fields']['actlib']
        ] );
    }
}

// transform long sports practices name into smaller ones

foreach($sports_practices as $practice) {

    $practice_name = shorten_string($practice['practice_name'], "/");
    $practice_name = shorten_string($practice_name, "(");
    $practice_name = shorten_string($practice_name, ",");
    $practice_name = trim($practice_name);
    $practice_name = str_replace(' - ', '-', $practice_name);

    $image_name = format_image_name($practice_name);

    $request = $pdo->prepare('INSERT INTO sports_practice(id, practice, image_name) VALUES (
    :id, :practice_name, :image)');

    $request->bindParam(':id', $practice['id']);
    $request->bindParam(':practice_name', $practice_name);
    $request->bindParam(':image', $image_name);
    $request->execute();
}


// add olympic events sports practice to the others if it doesn't already exists

foreach ($olympics_json as $olympic_event) {
   $request = $pdo->prepare('SELECT practice FROM sports_practice WHERE practice = :practice');
   $request->bindParam(':practice', $olympic_event['sportsFamily']);
   $request->execute();
   $result = $request->fetch(PDO::FETCH_ASSOC);

   if (!$result) {
       $image_name = format_image_name($olympic_event['sportsFamily']);

       $request = $pdo->prepare('INSERT INTO sports_practice(practice, image_name) VALUES (
        :practice, :image)');

       $request->bindParam(':practice', $olympic_event['sportsFamily']);
       $request->bindParam(':image', $image_name);
       $request->execute();
   }
}




// ------------------------------ sports_family --------------------------------------------------- //

// table with all the sports families (ball sports, swimming sports....)

foreach($sports_families_json as $sports_family) {
    $request = $pdo->prepare('INSERT INTO sports_family(sports_family_name) VALUES (:name)');
    $request->bindParam(':name', $sports_family['sport']);
    $request->execute();
}


//------------------- arrondissement ---------------------------------------------------- //

// paris arrondissements
foreach($arrondissements_json as $arrondissement) {

    $km_square = round($arrondissement['fields']['surface'] / 10000) / 100;

    $request = $pdo->prepare('INSERT INTO arrondissement(id, insee_code, name, surface_km_square) VALUES (:id, :insee, :name, :surface)');
    $request->bindParam(':id', $arrondissement['fields']['c_ar']);
    $request->bindParam(':insee', $arrondissement['fields']['c_arinsee']);
    $request->bindParam(':name', $arrondissement['fields']['l_aroff']);
    $request->bindParam(':surface', $km_square);
    $request->execute();
}





// ----------- sports_facility ---------------------------------------------------------------------- //

// sort data to keep only facilities that have a complete address + a facility type + a sport practice
// first check if the facility is not already in the db (facilities in the json data can occur multiple times)

foreach($sports_facilities_json as $sports_facility) {

    // check if is in db
    $request = $pdo->prepare('SELECT id FROM sports_facility WHERE id = :id');
    $request->bindParam(':id', $sports_facility['fields']['equipementid']);
    $request->execute();
    $is_already_in_db = $request->fetch(PDO::FETCH_ASSOC);


    if (!$is_already_in_db
        && isset($sports_facility['fields']['insnovoie'])
        && isset($sports_facility['fields']['inslibellevoie'])
        && isset($sports_facility['fields']['insarrondissement'])
        && isset($sports_facility['fields']['actcode'])
        && isset($sports_facility['fields']['equipementtypecode'])
        && isset($sports_facility['fields']['insarrondissement'])
    ) {
       
        $facility_name = isset($sports_facility['fields']['insnom']) ? $sports_facility['fields']['insnom'] : '';
        $address_number = (int)$sports_facility['fields']['insnovoie'];

        
        // corresponding arrondissement id
        $request = $pdo->prepare('SELECT id FROM arrondissement WHERE insee_code = :insee');
        $request->bindParam(':insee', $sports_facility['fields']['insarrondissement']);
        $request->execute();
        $id_arrondissement = $request->fetch(PDO::FETCH_ASSOC);

        $request = $pdo->prepare('INSERT INTO sports_facility(
        id,
        facility_name,
        address_number,
        address_street,
        facility_type,
        id_arrondissement) VALUES (
        :id,
        :facility_name,
        :address_number,
        :street_name,
        :type,
        :arrondissement_id)');

        $request->bindParam(':id', $sports_facility['fields']['equipementid']);
        $request->bindParam(':facility_name', $facility_name);
        $request->bindParam(':address_number', $address_number);
        $request->bindParam(':street_name', $sports_facility['fields']['inslibellevoie']);
        $request->bindParam(':type', $sports_facility['fields']['equipementtypelib']);
        $request->bindParam(':arrondissement_id', $id_arrondissement['id']);
        $request->execute();
    }
}



// --------------------- olympic_event ------------------------------------------------------------------ //

// all the olympic events by date. 
// + association with the sports practice they are linked to

foreach ($olympics_json as $olympic_event) {

    $request = $pdo->prepare('SELECT id FROM sports_practice WHERE practice = :olympic_event');
    $request->bindParam(':olympic_event', $olympic_event['sportsFamily']);
    $request->execute();
    $id_practice = $request->fetch(PDO::FETCH_ASSOC);

    // corresponding arrondissement id
    $request = $pdo->prepare('SELECT id FROM arrondissement WHERE insee_code = :insee');
    $request->bindParam(':insee', $olympic_event['arrondissementInsee']);
    $request->execute();
    $result = $request->fetch(PDO::FETCH_ASSOC);

    if ($result) $id_arrondissement = $result;
    else $id_arrondissement = null;

    foreach($olympic_event['dates'] as $date) {
        $request = $pdo->prepare('INSERT INTO olympic_event(event_name, event_place, date, id_sports_practice, id_arrondissement)
        VALUES (
        :olympic_event_name,
        :place,
        :date,
        :sports_practice_id,
        :insee)');
        $request->bindParam(':olympic_event_name', $olympic_event['sport']);
        $request->bindParam(':place', $olympic_event['place']);
        $request->bindParam(':date', $date);
        $request->bindParam(':sports_practice_id', $id_practice['id']);
        $request->bindParam(':insee', $id_arrondissement['id']);
        $request->execute();
    }
}


// --------------- facility_practice_association ----------------------------------- //

$request = $pdo->prepare('SELECT id FROM sports_practice');
$request->execute();
$results = $request->fetchAll(PDO::FETCH_ASSOC);


foreach ($results as $practice) {
    $associations = [];

    foreach ($sports_facilities_json as $sports_facility) {
         // check if is in db
        $request = $pdo->prepare('SELECT id FROM sports_facility WHERE id = :id');
        $request->bindParam(':id', $sports_facility['fields']['equipementid']);
        $request->execute();
        $is_in_db = $request->fetch(PDO::FETCH_ASSOC);



        if ($is_in_db && (int)$practice['id'] === (int)$sports_facility['fields']['actcode'] && !in_array(['practice_id' => $practice['id'], 'facility_id' => $sports_facility['fields']['equipementid']], $associations)) {
            
            // practice level renaming
            if (isset($sports_facility['fields']['actnivlib']) && ($sports_facility['fields']['actnivlib'] === 'Compétition régionale' || $sports_facility['fields']['actnivlib'] === 'Compétition nationale' || $sports_facility['fields']['actnivlib'] === 'Compétition départementale' || $sports_facility['fields']['actnivlib'] === 'Compétition internationale')) {
                $practice_level = 'Compétition';
            } else if (isset($sports_facility['fields']['actnivlib']) && $sports_facility['fields']['actnivlib'] === 'Loisir - Entretien - Remise en forme') {
                $practice_level = 'Loisir';
            } else if (!isset($sports_facility['fields']['actnivlib']) || $sports_facility['fields']['actnivlib'] === 'Non défini'){
                $practice_level = null;
            } else {
                $practice_level = $sports_facility['fields']['actnivlib'];
            }
            
            array_push($associations, [
                'practice_id' => $practice['id'],
                'facility_id' => $sports_facility['fields']['equipementid'],
                'practice_level' => $practice_level,
                'handicap_access_mobility_sports_area' => convert_into_boolean($sports_facility['fields'], 'equacceshandimaire', 'Oui'),
                'handicap_access_sensory_sports_area' => convert_into_boolean($sports_facility['fields'], 'equacceshandisaire', 'Oui'),
                'handicap_access_sensory_locker_room' => convert_into_boolean($sports_facility['fields'], 'equacceshandisvestiaire', 'Oui'),
                'handicap_access_mobility_locker_room' => convert_into_boolean($sports_facility['fields'], 'equacceshandimvestiaire', 'Oui'),
                'handicap_access_mobility_sanitary' => convert_into_boolean($sports_facility['fields'], 'equacceshandimsanispo', 'Oui'),
                'handicap_access_sensory_sanitary' => convert_into_boolean($sports_facility['fields'], 'equacceshandissanispo', 'Oui'),
                'handicap_access_mobility_pool' => convert_into_boolean($sports_facility['fields'], 'equnatimhandi', 0)
            ]);
        }
    }

    foreach($associations as $association) {
        $request = $pdo->prepare('INSERT INTO facility_practice_association(
        id_sports_practice, 
        id_sports_facility,
        practice_level,
        handicap_access_mobility_sport_area,
        handicap_access_sensory_sport_area,
        handicap_access_sensory_locker_room,
        handicap_access_mobility_locker_room,
        handicap_access_mobility_sanitary,
        handicap_access_sensory_sanitary,
        handicap_access_mobility_swimming_pool
        )
        VALUES (
        :practice_id,
        :facility_id,
        :practice_level,
        :handicap_access_mobility_sports_area,
        :handicap_access_sensory_sports_area,
        :handicap_access_sensory_locker_room,
        :handicap_access_mobility_locker_room,
        :handicap_access_mobility_sanitary,
        :handicap_access_sensory_sanitary,
        :handicap_access_mobility_pool
        )');
        $request->bindParam(':practice_id', $association['practice_id']);
        $request->bindParam(':facility_id', $association['facility_id']);
        $request->bindParam(':practice_level', $association['practice_level']);
        $request->bindParam(':handicap_access_mobility_sports_area', $association['handicap_access_mobility_sports_area']);
        $request->bindParam(':handicap_access_sensory_sports_area', $association['handicap_access_sensory_sports_area']);
        $request->bindParam(':handicap_access_sensory_locker_room', $association['handicap_access_sensory_locker_room']);
        $request->bindParam(':handicap_access_mobility_locker_room', $association['handicap_access_mobility_locker_room']);
        $request->bindParam(':handicap_access_mobility_sanitary', $association['handicap_access_mobility_sanitary']);
        $request->bindParam(':handicap_access_sensory_sanitary', $association['handicap_access_sensory_sanitary']);
        $request->bindParam(':handicap_access_mobility_pool', $association['handicap_access_mobility_pool']);
        $request->execute();
    }
}


// --------------- sports_family_practice_association ----------------------------------- //

// join table linking sports practices with their ralted sports family (they can have more than one: ex: polo --> horse riding + ball sport)

foreach($sports_families_json as $sport) {

    $request = $pdo->prepare('SELECT id from sports_family WHERE sports_family_name = :sport');
    $request->bindParam(':sport', $sport['sport']);
    $request->execute();
    $db_sports_family_id = $request->fetch(PDO::FETCH_ASSOC);


    foreach ($sport['practices'] as $sport_practice) {

        $practice_name = shorten_string($sport_practice, "/");
        $practice_name = shorten_string($practice_name, "(");
        $practice_name = shorten_string($practice_name, ",");
        $practice_name = trim($practice_name);
        $practice_name = str_replace(' - ', '-', $practice_name);


        $request = $pdo->prepare('SELECT id from sports_practice WHERE practice = :practice');
        $request->bindParam(':practice', $practice_name);
        $request->execute();
        $db_practise_id = $request->fetch(PDO::FETCH_ASSOC);


        // INSERT INTO
        $request = $pdo->prepare('INSERT INTO sports_family_practice_association(id_practice, id_sports_family) VALUES (
        :id_practice, :id_sports_family)');
        $request->bindParam(':id_practice', $db_practise_id['id']);
        $request->bindParam(':id_sports_family', $db_sports_family_id['id']);
        $request->execute();
    }
}