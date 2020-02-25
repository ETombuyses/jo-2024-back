let selectedSportModalInfos = {
  selectedSport: {
    name: 'tennis',
    image: 'tennis',
    etablissementAmount: 30
  },
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

// parametres : 

[{practiceId : 40, arrondissement: 2, handicapMobility: true, handicapSensory: false, practiceLevel: 'compétition'}]


1) liste des id des practice sportives à laquelle appartient la selected practice

SELECT f.id FROM sports_family f
INNER JOIN sports_family_practice_association a ON a.id_sports_family = f.id
INNER JOIN sports_practice p ON p.id = a.id_practice
WHERE p.id = 5201

okkkk

reponse ids = 3, 5 et 9



2) avoir la liste des id practice faisant partie de chacune de ces families

SELECT DISTINCT p.id FROM sports_practice p
INNER JOIN sports_family_practice_association a ON p.id = a.id_practice
INNER JOIN sports_family f ON a.id_sports_family = f.id
WHERE f.id IN (3, 5, 9)


2) informations pour chacune de ces id

fonction déjà existante dans symfo !


