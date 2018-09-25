<?php
namespace Source;

use Source\Utils;
use Source\interfaces\requeteInterface;

class Requete implements requeteInterface
{

  private $requeteManuel = false;

  function __construct($requeteManuel)
  {
    if ($requeteManuel === NULL) {
      $this->bootstrapSelf();
    } else {
      $this->requeteManuel = true;
      foreach ($requeteManuel as $paramNom => $paramValeur) {
        $this->{$paramNom} = $paramValeur;
      }
    }
  }
  private function bootstrapSelf()
  {
    foreach($_SERVER as $key => $value)
    {
      $this->{$this->toCamelCase($key)} = $value;
    }
  }
  private function toCamelCase($chaine)
  {
    $resultat = strtolower($chaine);
        
    preg_match_all('/_[a-z]/', $resultat, $matches);
    foreach($matches[0] as $match)
    {
        $c = str_replace('_', '', strtoupper($match));
        $resultat = str_replace($match, $c, $resultat);
    }
    return $resultat;
  }
  public function getBody()
  {
    if($this->requestMethod === "GET")
    {
      return null;
    }
    if ($this->requestMethod == "POST")
    {
      $result = array();
      $body = ($this->requeteManuel === true) ? $this->body : $_POST;
      foreach($body as $key => $value)
      {
        $result[$key] = ($this->requeteManuel === true) ? $value : filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
      }
      return $result;
    }
    
    return $body;
  }
  public function getUrlParams() {
    $urlParams = explode("/", $this->requestUri);

    return array_slice($urlParams, 3);
  }
}