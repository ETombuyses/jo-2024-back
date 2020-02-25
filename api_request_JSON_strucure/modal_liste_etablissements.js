[
  {
    facility_type: 'Salle de sport',
    handicap_access_mobility_sport_area: true,
    handicap_access_sensory_sport_area: false,
    handicap_access_sensory_locker_room: true,
    handicap_access_mobility_locker_room: false,
    handicap_access_mobility_swimming_pool: false,
    handicap_access_sensory_sanitary: true,
    handicap_access_mobility_sanitary: false,
    isPool: false,
    facilityName: 'Clubi sport',
    addressNumber: 29,
    address_street: 'Rue sant vincent',
    arrondissement: '75001'
  },
  {
    facility_type: 'piscine',
    handicap_access_mobility_sport_area: true,
    handicap_access_sensory_sport_area: false,
    handicap_access_sensory_locker_room: true,
    handicap_access_mobility_locker_room: false,
    handicap_access_mobility_swimming_pool: false,
    handicap_access_sensory_sanitary: true,
    handicap_access_mobility_sanitary: false,
    facilityName: 'Clubi sport',
    addressNumber: 29,
    address_street: 'Rue sant vincent',
    arrondissement: '75001'
  },
  {
    facility_type: 'Salle multisport',
    handicap_access_mobility_sport_area: true,
    handicap_access_sensory_sport_area: false,
    handicap_access_sensory_locker_room: true,
    handicap_access_mobility_locker_room: false,
    handicap_access_mobility_swimming_pool: false,
    handicap_access_sensory_sanitary: true,
    handicap_access_mobility_sanitary: false,
    isPool: true,
    facilityName: 'Clubi sport',
    addressNumber: 29,
    address_street: 'Rue sant vincent',
    arrondissement: '75001'
  }
]


// tout renvoyer suaf le level car tout le reste est affiché



// parametres : 

[{practiceId : 40, arrondissement: 2, handicapMobility: true, handicapSensory: false, practiceLevel: 'compétition'}]


/* ---------------------- SQL request + traitement ------------------ */

// no filter
SELECT f.facility_name, f.address_number, f.address_street, f.facility_type, ar.insee_code, 
a.handicap_access_mobility_sport_area, a.handicap_access_sensory_sport_area, a.handicap_access_sensory_locker_room,
a.handicap_access_mobility_locker_room, a.handicap_access_mobility_swimming_pool, a.handicap_access_sensory_sanitary,
a.handicap_access_mobility_sanitary 
FROM sports_facility f
INNER JOIN arrondissement ar ON f.id_arrondissement = ar.id
INNER JOIN facility_practice_association a ON a.id_sports_facility = f.id
INNER JOIN sports_practice p ON p.id = a.id_sports_practice
WHERE p.id = 5201


/* status */



mysql -u root -p -e "USE jo_2024 select count(f.id) as number_etab, f.id_arrondissement, p.practice from sports_facility f INNER JOIN facility_practice_association a ON a.id_sports_facility = f.id INNER JOIN sports_practice p ON p.id = a.id_sports_practice GROUP BY f.id_arrondissement, p.id" > teestjo