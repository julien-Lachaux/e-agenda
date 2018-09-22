app.get("/users/lister", (response) => {
    $('.contenu').html(response)
})