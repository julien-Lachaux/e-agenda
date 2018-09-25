const adresses = {

    /**
     * @description recupère la liste des adresses d'un contact
     * @param {string} cible 
     * @param {integer} contactId 
     */
    contactAdressesListe(cible, contactId) {
        app.get("/contact/adresses/" + contactId, (reponse) => {
            $(cible).html(reponse)
        })
    },

    /**
     * @description ouvre la modal de creation d'adresse
     */
    nouvelleAdresseModal() {
        app.get("/adresses/formulaire", (reponse) => {
            let modalContact = $("#modalContact")
            modalContact.modal("hide")
            modalContact.after(reponse)
            let modalAdresse = $("#modalAdresse")
            modalAdresse.modal("show")
        })
    },

    /**
     * @description ajoute la nouvelle adresse
     */
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

    /**
     * @description ouvre la modal d'edition d'adresse
     * @param {integer} adresseId 
     */
    editerAdresseModal(adresseId) {
        app.get("/adresses/formulaire/" + adresseId, (reponse) => {
            let modalContact = $("#modalContact")
            modalContact.modal("hide")
            modalContact.after(reponse)
            let modalAdresse = $("#modalAdresse")
            modalAdresse.modal("show")
        })
    },

    /**
     * @description edite l'adresse
     */
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

    /**
     * @description renvoie au contact de l'adresse
     */
    retourAuContact() {
        let contactId = $("#contactId").val()
        let modalContact = $("#modalContact")
        let modalAdresse = $("#modalAdresse")

        this.contactAdressesListe("#contactAdresses", contactId)
        modalAdresse.modal("hide")
        modalAdresse.remove()
        $(".modal-backdrop").remove()
        modalContact.modal("show")
    },

    /**
     * @description supprime une adresse
     * @param {integer} adresseId 
     */
    supprimerAdresse(adresseId) {
        app.get("/adresses/supprimer/" + adresseId, (reponse) => {
            let contactId = $("#contactId").val()
            
            this.contactAdressesListe("#contactAdresses", contactId)
            $("#alertes").html(reponse)
            setTimeout(() => {
                $("#alerteAdresse").alert("close")
                setTimeout(() => {
                    $("#alerteAdresse").remove()
                }, 2000);
            }, 5000);
        })
    }

}