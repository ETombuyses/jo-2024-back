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
    facilityName: 'Clubi sport',
    addressNumber: 29,
    address_street: 'Rue sant vincent'
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
    address_street: 'Rue sant vincent'
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
    facilityName: 'Clubi sport',
    addressNumber: 29,
    address_street: 'Rue sant vincent'
  }
]


// tout renvoyer suaf le level car tout le reste est affiché

 !!!!!!! renvoyer le handi piscine que si c'est une piscine !!!



// parametres : 

[{practiceId : 40, handicapMobility: true, handicapSensory: false, practiceLevel: 'compétition'}]


/* ---------------------- SQL request + traitement ------------------ */

SELECT t.facility_type, f.handicap_access_mobility_sport_area, f.handicap_access_sensory_sport_area, 
f.handicap_access_sensory_locker_room, f.handicap_access_mobility_locker_room, f.handicap_access_mobility_swimming_pool,
f.handicap_access_sensory_sanitary, f.handicap_access_mobility_sanitary, f.facilityName, f.addressNumber, f.address_street
FROM sports_facility f
INNER JOIN facility_type_association a ON f.id = a.id_sports_facility
INNER JOIN facility_type t ON t.id = a.id_facility_type
WHERE

/* status */

