// TODO: ajouter filtre par arrondissement

let f = {
  joSports: [
    {
      idPractce: 2,
      name: 'Tennis',
      image: 'tennis',
      etablissementAmount: 2
    },
    {
      name: 'Foot-Ball',
      image: 'foot-ball',
      etablissementAmount: 90
    },
  ],
  otherFamilies: [
    {
      name: 'Tennis',
      image: 'tennis',
      etablissementAmount: 30,
    },
    {
      name: 'Baseball',
      image: 'baseball',
      etablissementAmount: 3,
    },
    {
      name: 'Golf',
      image: 'golf',
      etablissementAmount: 29,
    }
  ]
}



// renvoie les épreuves du jour + résultats filtrés

// parametres : 

[{date: '2024-23-02', arrondissement: 2, handicapMobility: true, handicapSensory: false, practiceLevel: 'Compétition'}]


/* ---------------------- SQL request + traitement ------------------ */

16 cas à traiter :
- sans filtre
- level
- sensory
- mobility
- level + mobilité
- level + sensory
- level + sensory + mobility
- mobility + sensory
- arrondissement
- arrondissement + mobility
- arrondissement + sensory
- arrondissement + level
- arrondissement + mobility + level
- arrondissement + mobility + sensory
- arrondissement + sensory + level

- arrondissement + mobility + sensory + level



////// en faire plusieurs différentes pour chaque partie du résultat :

// récupération des id des practice + image + nom practice --> liste

SELECT DISTINCT p.id, p.practice, p.image_name
FROM sports_practice p
INNER JOIN olympic_event o ON p.id = o.id_sports_practice
WHERE o.date = '2024-07-26'

--> faire un array des id pratice
--> pour chaque id (suite des filtres)



// count nombre etab si : filtre level 
SELECT COUNT(f.id) FROM sports_facility f
INNER JOIN facility_practice_association a ON f.id = a.id_sports_facility
INNER JOIN sports_practice p ON p.id = a.id_sports_practice
WHERE p.id = 5201 AND a.practice_level = 'Entrainement'



// count nombre d'établissements si: hadicap mobilité + level
SELECT COUNT(f.id) FROM sports_facility f
INNER JOIN facility_practice_association a ON f.id = a.id_sports_facility
INNER JOIN sports_practice p ON p.id = a.id_sports_practice
WHERE p.id = 5201 AND a.practice_level = 'Entrainement' AND (a.handicap_access_mobility_sport_area = 1 OR a.handicap_access_mobility_locker_room = 1 OR a.handicap_access_mobility_swimming_pool = 1 OR a.handicap_access_mobility_sanitary = 1)
 



// count nombre d'établissements selon les le filtre hadicap sensory activé pour UN SPORT ALOYMPIQUE
SELECT COUNT(f.id) FROM sports_facility f
INNER JOIN facility_practice_association a ON f.id = a.id_sports_facility
INNER JOIN sports_practice p ON p.id = a.id_sports_practice 
WHERE p.id = 5201 AND a.practice_level = 'Entrainement' AND (a.handicap_access_sensory_sport_area = 1 OR a.handicap_access_sensory_locker_room = 1 OR a.handicap_access_sensory_sanitary = 1)






// count nombre d'établissements selon les le filtre hadicap sensory + mobility activé pour UN SPORT ALOYMPIQUE
SELECT COUNT(f.id) FROM sports_facility f
INNER JOIN facility_practice_association a ON f.id = a.id_sports_facility
INNER JOIN sports_practice p ON p.id = a.id_sports_practice 
WHERE p.id = 5201 AND ((a.handicap_access_mobility_sport_area = 1 OR a.handicap_access_mobility_locker_room  = 1 OR a.handicap_access_mobility_swimming_pool = 1 OR a.handicap_access_mobility_sanitary = 1) AND (a.handicap_access_sensory_sport_area = 1 OR a.handicap_access_sensory_locker_room = 1 OR a.handicap_access_sensory_sanitary = 1))


/* status */

doooone !!!