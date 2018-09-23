app.get("/utilisateurs/lister", (response) => {
    $('.contenu').html(response)
})