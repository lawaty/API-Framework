<?php

  class Router {
    private static function endpointExists($controller, $endpoint){
      // Searching for endpoint
      return file_exists("endpoints/$controller/$endpoint.php");
    }

    public static function route(){
      // Creates Endpoint Object If Found
      $sections = explode('/', explode('?', parse_url($_SERVER['REQUEST_URI'])['path'])[0]);
      $len = count($sections);

      $controller = strtolower($sections[$len - 2]);
      $endpoint = ucfirst($sections[$len - 1]);

      if(!self::endpointExists($controller, $endpoint))
        return false;
      
      require("endpoints/bases/Endpoint.php");
      require("endpoints/$controller/$endpoint.php");
      return new $sections[$len - 1];
    }
  }
?>