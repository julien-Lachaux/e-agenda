<?php
namespace Source;

use Source\Utils;

class Router
{

    private $requete;
    private $supportedHttpMethods = ["GET", "POST"];

    /**
     * constructeur
     *
     * @param requete $requete
     */
    function __construct(requete $requete) {
        $this->requete = $requete;
    }

    /**
     * Enregistre toute les routes déclarer
     *
     * @param [string] $nom
     * @param [array] $args
     * @return void
     */
    function __call($nom, $args) {
        list($route, $method) = $args;
        if (!in_array(strtoupper($nom), $this->supportedHttpMethods)) {
            $this->invalideMethodHandler();
        }
        $this->{strtolower($nom)}[$this->formatRoute($route)] = (object) $method;
    }

    /**
     * formate la route
     *
     * @param [string] $route
     * @return string
     */
    private function formatRoute($route, $declaration = true) {
        $resultat = rtrim($route, '/');
        if ($resultat === '') {
            return '/';
        }
        $ulrParam = explode('/', $resultat);
        if ($declaration && isset($ulrParam[2])) {
            $resultat = "/{$ulrParam[1]}/{$ulrParam[2]}";
        } else if ($declaration && isset($ulrParam[2]) === false) {
            $resultat = "/{$ulrParam[1]}";
        }
        return $resultat;
    }

    /**
     * Renvoie le header HTTP 405 en cas de route non valide
     *
     * @return void
     */
    private function invalideMethodHandler() {
        header("{$this->requete->serverProtocol} 405 Route invalide");
    }

    /**
     * Renvoie le header HTTP 404 en cas de route non defini
     *
     * @return void
     */
    private function defaultrequeteHandler() {
        header("{$this->requete->serverProtocol} 404 Page introuvable");
    }

    /**
     * Resoud la route
     */
    function resoudre() {
        $methodDictionary = $this->{strtolower($this->requete->requestMethod)};
        $formatedRoute = $this->formatRoute($this->requete->requestUri);
        
        if (!isset($methodDictionary[$formatedRoute])) {
            if(isset($this->get["/erreur/404"])) {
                $instruction404 = $this->get["/erreur/404"];
                $reponse404 = $instruction404->controller->{$instruction404->methode}($this->requete);
                echo $reponse404;
            } else {
                $this->defaultrequeteHandler();
            }
        } else {
            $instruction = $methodDictionary[$formatedRoute];
            $reponse = $instruction->controller->{$instruction->methode}($this->requete);
            echo $reponse;
        }
    }

    /**
     * Declanche la resolution de la route à la destuction de l'objct
     */
    function __destruct() {
        $this->resoudre();
    }

    public function getRequete() {
        return $this->requete;
    }

    public function recupererRoutesModule($nomModule) {
        $routesJson = json_decode(utf8_encode(Utils::recupererContenuFichier(__DIR__ . "/../modules/{$nomModule}/@routes.json")), false);
        $controlleurs = [];
        foreach ($routesJson->routes as $routeJson) {
            if ($routeJson->active) {
                if (!isset($controller[$routeJson->controller])) {
                    require_once(__DIR__ . "/../modules/$nomModule/$routeJson->controller.php");
                    $controllerTexte = "\\modules\\$nomModule\\$routeJson->controller";
                    $controller[$routeJson->controller] = new $controllerTexte;
                }
    
                $this->{$routeJson->methodeHTTP}($routeJson->route, [
                    "controller"    => $controller[$routeJson->controller],
                    "methode"       => $routeJson->methode
                ]);
            }
        }
    }
}