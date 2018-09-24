app.get("/utilisateurs/editerUtilisateurActuel", (response) => {
    $('.contenu').html(response)
})