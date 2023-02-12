<?php

class Router
{
  private static function endpointExists($controller, $endpoint): bool
  {
    // Searching for endpoint
    return file_exists("endpoints/$controller/$endpoint.php") || (!$endpoint && file_exists("endpoints/$controller/default.php"));
  }

  public static function route(): ?Endpoint
  {
    // Creates Endpoint Object If Found
    $url = 'api' . explode('/api', parse_url($_SERVER['REQUEST_URI'])['path'])[1];
    $sections = explode('/', explode('?', $url)[0]);
    $len = count($sections);
    if ($len != 2 && $len != 3)
      return null;

    $controller = strtolower($sections[1]);
    if ($len == 3) $endpoint = ucfirst($sections[2]);
    else $endpoint = null;

    if (!self::endpointExists($controller, $endpoint)) // either endpoint found or default endpoint exists for that controller
      return null;

    if ($endpoint) { // endpoint found
      require("bases/Endpoint.php");
      require("endpoints/$controller/$endpoint.php");
      return new $endpoint;
    }

    // Search for a vaild default endpoint
    $before = count(get_declared_classes());

    require("endpoints/$controller/default.php");

    $classes = get_declared_classes();
    if (count($classes) > $before) {
      $classname = end($classes);
      return new $classname;
    }
    return null;
  }
}
