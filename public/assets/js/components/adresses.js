const adresses = {

    contactAdressesListe(cible, contactId) {
        app.get("/contact/adresses/" + contactId, (reponse) => {
            $(cible).html(reponse)
        })
    },

    nouvelleAdresseModal() {
        app.get("/adresses/formulaire", (reponse) => {
            let modalContact = $("#modalContact")
            modalContact.modal("hide")
            modalContact.after(reponse)
            let modalAdresse = $("#modalAdresse")
            modalAdresse.modal("show")
        })
    },

    nouvelleAdresse() {
        let modal = $("#modalAdresse")
        let contactId = $("#contactId").val()

        $(".form-control[name=contacts_id]").val(contactId)
        app.ajaxForm("#formAdresse", (reponse) => {
            modal.remove()
            adresses.contactAdressesListe("#contactAdresses", contactId)
            $(".modal-backdrop").remove()
            modal.after(reponse)
            modal = $("#modalContact")
            modal.modal("show")
        })
    },

    editerAdresseModal(adresseId) {
        app.get("/adresses/formulaire/" + adresseId, (reponse) => {
            let modalContact = $("#modalContact")
            modalContact.modal("hide")
            modalContact.after(reponse)
            let modalAdresse = $("#modalAdresse")
            modalAdresse.modal("show")
        })
    },

    editerAdresse() {
        let modal = $("#modalAdresse")
        let contactId = $("#contactId").val()

        $(".form-control[name=contacts_id]").val(contactId)
        app.ajaxForm("#formAdresse", (reponse) => {
            modal.remove()
            adresses.contactAdressesListe("#contactAdresses", contactId)
            $(".modal-backdrop").remove()
            modal.after(reponse)
            modal = $("#modalContact")
            modal.modal("show")
        })
    },

    retourAuContact() {
        let contactId = $("#contactId").val()
        console.log("demo")
        this.contactAdressesListe("#contactAdresses", contactId)
        let modalContact = $("#modalContact")
        let modalAdresse = $("#modalAdresse")
        modalAdresse.modal("hide")
        modalAdresse.remove()
        $(".modal-backdrop").remove()
        modalContact.modal("show")
    }

}