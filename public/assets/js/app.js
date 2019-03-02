const app = {

    /**
     * proxy pour les requetes
     * /!\ DOIT ETRE SET DANS LE HTML DE BASE /!\
     */
    withProxy = false,
    proxy_host = null,

    /**
     * @description effectue une requete ajax get avec la gestion d'erreur
     * @param {string} url url à appelé en ajax
     * @param {funtion} callback fonction de callback
     */
    get(url, callback = () => {}) {
        $.ajax({
            url: url,
            method: 'GET',
        })
        .done(callback)
        .fail(app.ajaxError)
    },

    /**
     * @description effectue une requete ajax post avec la gestion d'erreur
     * @param {strin} url url à appelé en ajax
     * @param {object} data data envoyé avec la requete POST
     * @param {function} callback fonction de callback
     */
    post(url, data, callback = () => {}) {
        $.post(url, data, (response) => {
            callback(response)
        })
    },

    /**
     * @description serialize un formulaire en un tableau clé - valeur
     * @param {string} form selecteur css pour cibler le formulaire a serializer
     */
    serializeForm(form) {
        var formData = {}
        $(form).find('.form-control').each((key, input) => {
            let champ = $(input).attr('name')
            let valeur = $(input).val()
            formData[champ] = valeur
        });
        return formData
    },

    /**
     * @description affiche un message d'erreur en cas d'echec q'un appel ajax
     * @param {object} error 
     */
    ajaxError(error) {
        console.log(error)
    },

    /**
     * @description recupere la page courante dans l'url
     */
    getCurrentPage() {
        var CheminComplet = document.location.href
        var hash = CheminComplet.substring(CheminComplet.lastIndexOf( "/" ) + 1)

        return hash
    },

    /**
     * @param {string} formSelecteur 
     * @param {function} callback 
     */
    ajaxForm(formSelecteur, callback = (reponse) => {}) {
        let form = $(formSelecteur)
        let url = form.attr("action")
        let methode = form.attr("method")
        app[methode](url, app.serializeForm(form), (reponse) => {
            callback(reponse);
        })
    }
}