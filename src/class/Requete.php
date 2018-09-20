<?php
include_once 'RequeteInterface.php';
class Requete implements requeteInterface
{
  function __construct()
  {
    $this->bootstrapSelf();
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
    if($this->requeteMethod === "GET")
    {
      return null;
    }
    if ($this->requeteMethod == "POST")
    {
      $result = array();
      foreach($_POST as $key => $value)
      {
        $result[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
      }
      return $result;
    }
    
    return $body;
  }
}