<?php

namespace Modules\validateurs;

use Source\Requete;
use Source\Controller;

class ValidateursController extends Controller {

    /**
     * Valide un email
     *
     * @param Requete $requete
     * @return JSON
     */
    public static function validateurEmail(Requete $requete) {
        $body = $requete->getBody();
        
        if (isset($body["email"]) && !empty($body["email"])) {
            $email = $body["email"];
            $validation = filter_var($email, FILTER_VALIDATE_EMAIL);
            if ($validation) {
                $reponse = [
                    "email"         => $email,
                    "emailValide"   => true
                ];
            } else {
                $reponse = [
                    "email"         => $email,
                    "emailValide"   => false
                ];
            }
        } else {
            $reponse = [ "erreur" => "requete invalide" ];
        }
        
        header("Content-type:application/json");
        
        return json_encode($reponse, JSON_FORCE_OBJECT);
    }

}