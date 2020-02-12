[
  {
    name: 'Marathon',
    place: 'Paris Arena',
    image: 'marathon'
  },
  {
    name: 'Athlétisme',
    place: 'Centre d athlétisme',
    image: 'athletisme'
  }
]


// si on affiche encore l'épreuve qui est en cours dans l'arrondissmeent (normalement oui)
// il peut y en avoir plusieurs dans un même arrondissement la même date


// parametres : 

[{idArrondissement : 4, date: '23-02-2024'}]


/* ---------------------- SQL request + traitement ------------------ */

SELECT p.practice, p.image_name FROM sports_practice p
INNER JOIN olympic_event o ON p.id = o.id_sports_practice
WHERE o.date = '2024-07-27' AND o.id_arrondissement = 8

/* status */

DONE !!!!!
