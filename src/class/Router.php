
<?php
class Route {

  private $requete;
  private $supportedHttpMethods = ["GET", "POST"];

  /**
   * constructeur
   *
   * @param requeteInterface $requete
   */
  function __construct(requeteInterface $requete) {
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
    echo "<pre>";
    var_dump($args);
    echo "</pre>";
    list($route, $method) = $args;
    if(!in_array(strtoupper($nom), $this->supportedHttpMethods))
    {
      $this->invalideMethodHandler();
    }
    $this->{strtolower($nom)}[$this->formatRoute($route)] = $method;
  }

  /**
   * formate la route
   *
   * @param [string] $route
   * @return string
   */
  private function formatRoute($route) {
    $resultat = rtrim($route, '/');
    if ($resultat === '')
    {
      return '/';
    }
    return $resultat;
  }

  /**
   * Renvoie le header HTTP 405 en cas de route non valide
   *
   * @return void
   */
  private function invalideMethodHandler() {
    header("{$this->requete->serverProtocol} 405 Method Not Allowed");
  }

  /**
   * Renvoie le header HTTP 404 en cas de route non defini
   *
   * @return void
   */
  private function defaultrequeteHandler() {
    header("{$this->requete->serverProtocol} 404 Not Found");
  }

  /**
   * Resoud la route
   */
  function resoudre() {
    $methodDictionary = $this->{strtolower($this->requete->requeteMethod)};
    $formatedRoute = $this->formatRoute($this->requete->requeteUri);
    $methode = $methodDictionary[$formatedRoute];
    if(is_null($methode)) {
      $this->defaultrequeteHandler();
      return;
    }
    echo call_user_func_array($methode, array($this->requete));
  }

  /**
   * Declanche la resolution de la route à la destuction de l'objct
   */
  function __destruct() {
    $this->resoudre();
  }
}