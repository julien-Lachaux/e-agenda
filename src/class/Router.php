
<?php
class Router
{
  private $requete;
  private $supportedHttpMethods = ["GET", "POST"];
  function __construct(requeteInterface $requete)
  {
   $this->requete = $requete;
  }
  function __call($nom, $args)
  {
    list($route, $method) = $args;
    if(!in_array(strtoupper($nom), $this->supportedHttpMethods))
    {
      $this->invalideMethodHandler();
    }
    $this->{strtolower($nom)}[$this->formatRoute($route)] = $method;
  }
  /**
   * netoie la route
   * @param route (string)
   */
  private function formatRoute($route)
  {
    $resultat = rtrim($route, '/');
    if ($resultat === '')
    {
      return '/';
    }
    return $resultat;
  }
  private function invalideMethodHandler()
  {
    header("{$this->requete->serverProtocol} 405 Method Not Allowed");
  }
  private function defaultrequeteHandler()
  {
    header("{$this->requete->serverProtocol} 404 Not Found");
  }
  /**
   * Resoud la route
   */
  function resoudre()
  {
    $methodDictionary = $this->{strtolower($this->requete->requeteMethod)};
    $formatedRoute = $this->formatRoute($this->requete->requeteUri);
    $methode = $methodDictionary[$formatedRoute];
    if(is_null($methode))
    {
      $this->defaultrequeteHandler();
      return;
    }
    echo call_user_func_array($methode, array($this->requete));
  }
  function __destruct()
  {
    $this->resoudre();
  }
}