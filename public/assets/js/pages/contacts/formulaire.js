let finUri = app.getCurrentPage()
let url = "/contacts/formulaire"
if (!isNaN(finUri)) { url += "/" + finUri }

app.get(url, (response) => {
    $('.contenu').html(response)
})