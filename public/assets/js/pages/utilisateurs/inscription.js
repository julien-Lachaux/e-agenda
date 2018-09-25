app.get("/utilisateurs/inscriptionFormulaire", (response) => {
    $('.contenu').html(response)
})