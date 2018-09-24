const contacts = {

    nouveauContactModal() {
        let modals = $("#modals")
        app.get('/contacts/formulaire', (reponse) => {
            modals.html(reponse)
            $('#modalContact').modal('show')
        })
    },

    nouveauContact() {
        let modals = $("#modals")
        let modal = $('#modalContact')
        app.ajaxForm("#formContact", (reponse) => {
            modal.remove()
            $('.modal-backdrop').remove()
            modals.html(reponse)
            modal = $('#modalContact')
            modal.modal('show')
        })
    },

    nouveauContactCreer() {
        app.get("/utilisateur/contacts", (reponse) => {
            $('.contenu').html(reponse)
        })
    },

    editerContactModal(contactId) {
        let modals = $("#modals")
        app.get('/contacts/formulaire/' + contactId, (reponse) => {
            modals.html(reponse)
            $('#modalContact').modal('show')
        })
    },

    editerContact() {
        let modals = $("#modals")
        let modal = $('#modalContact')
        app.ajaxForm("#formContact", (reponse) => {
            modal.remove()
            $('.modal-backdrop').remove()
            modals.html(reponse)
            modal = $('#modalContact')
            modal.modal('show')
        })
    },

    supprimerContact(contactId) {
        app.get("/contacts/supprimer/" + contactId, (reponse) => {

            app.get("/utilisateur/contacts", (sousReponse) => {
                $('.contenu').html(sousReponse)
                $('#alertes').html(reponse)
                setTimeout(() => {
                    $('#alerteContact').alert('close')
                    setTimeout(() => {
                        $('#alerteContact').remove()
                    }, 2000);
                }, 5000);
            })
        })
    }

}