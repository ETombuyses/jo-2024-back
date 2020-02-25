[
  {
    arrondissement: 1,
    concentration: 5,
  },
  {
    arrondissement: 2,
    concentration: 10,
  },
  {
    arrondissement: 3,
    concentration: 1,
  }
]

- arrondissement: numéro de 'larrondissement
- concentration: nombre d'établissement par km2 --> déterminera la couleur à afficher



// parametres : 

[{practiceId : 40, arrondiseement: 2, handicapMobility: true, handicapSensory: false, practiceLevel: 'compétition'}]



// toutes les id + km2 des arrondissements
SELECT id, surface_km_square from arrondissement



// count le nombre d'établissements par arrondissement
// même chose que pour les autres requetes de filtre, il faut 8 requetes sql différentes.
// voir si je peux récupérer l'existante et la modifier

SELECT f.id_arrondissement, COUNT(f.id) as amount_facilities FROM sports_facility f
                INNER JOIN facility_practice_association a ON f.id = a.id_sports_facility
                INNER JOIN sports_practice p ON p.id = a.id_sports_practice
                INNER JOIN arrondissement ar ON ar.id = f.id_arrondissement
                WHERE p.id = 5201
                GROUP BY f.id_arrondissement


SELECT f.id_arrondissement, COUNT(f.id) as amount_facilities FROM sports_facility f
INNER JOIN facility_practice_association a ON f.id = a.id_sports_facility
INNER JOIN sports_practice p ON p.id = a.id_sports_practice
WHERE p.id = 5201
GROUP BY f.id_arrondissement