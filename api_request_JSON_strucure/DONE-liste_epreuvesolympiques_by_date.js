[
  {
    date: '2024-08-07',
    practices: [
      {
        id: 1,
        name: 'tennis', 
        icon: 'tennis'
      }
    ]
  },
  {
    date: '2024-08-15',
    practices: [
      {
        id: 4,
        name: 'basket ball', 
        icon: 'basket-ball'
      },
      {
        id: 9,
        name: 'natation', 
        icon: 'natation'
      }
    ]
  }
]

explications: 

- id: pour pouvoir renvoyer l'id comme paramètre à une autre route
- name: nom à afficher
- icon: nom icone à chercher dans le front


// forme potentielle d'url
fzegaerg?param=zefuhzfhze&param2=qergqergeqrg

// parametres : 

none


/* ---------------------- SQL request + traitement ------------------ */

SELECT DISTINCT s.id, s.practice, s.image_name, o.date
FROM sports_practice s
INNER JOIN olympic_event o ON s.id = o.id_sports_practice


/* status */
DONE !!!!