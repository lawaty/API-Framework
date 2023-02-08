<?php

if (!function_exists('str_contains')) {
  function str_contains(string $haystack, string $needle): bool
  {
    return '' === $needle || false !== strpos($haystack, $needle);
  }
}

require "config.php";
require "autoload.php";

require "proxy/Logger.php";
require "proxy/Router.php";
require "proxy/Response.php";

$logger = new Logger();

if (!($endpoint = Router::route())) {
  $response = new Response(NOT_FOUND, 'Endpoint Not Found');
} else {
  try {
    $response = $endpoint->run();
  } catch (Exception $e) {
    $code = is_integer($e->getCode()) ? $e->getCode() : FAIL;
    $response = new Response($code, $e->getMessage());
    if(DEBUG)
      $response = new Response($code, trace($e));
  }

  // if(isset($response['type'])){
  //   header('content-type: '.$response['type']);
  //   echo file_get_contents($response['data']);
  //   exit();
  // }
}

$response->echo();

$endpoint->postHandle();

$logger->log($endpoint, $response);

function isJson($payload)
{
  if (!is_string($payload))
    return false;

  if (!empty($payload) && $payload[0] != '{' && $payload[0] != '[')
    return false;

  json_decode($payload);
  return json_last_error() === JSON_ERROR_NONE;
}

function trace(Exception $e, $seen = null)
{
  $starter = $seen ? 'Caused by: ' : '';
  $result = array();
  if (!$seen) $seen = array();
  $trace = $e->getTrace();
  $prev = $e->getPrevious();
  $result[] = sprintf('%s%s: %s', $starter, get_class($e), $e->getMessage());
  $file = $e->getFile();
  $line = $e->getLine();
  while (true) {
    $current = "$file:$line";
    if (is_array($seen) && in_array($current, $seen)) {
      $result[] = sprintf(' ... %d more', count($trace) + 1);
      break;
    }
    $result[] = sprintf(
      ' at %s%s%s(%s%s%s)',
      count($trace) && array_key_exists('class', $trace[0]) ? str_replace('\\', '.', $trace[0]['class']) : '',
      count($trace) && array_key_exists('class', $trace[0]) && array_key_exists('function', $trace[0]) ? '.' : '',
      count($trace) && array_key_exists('function', $trace[0]) ? str_replace('\\', '.', $trace[0]['function']) : '(main)',
      $line === null ? $file : basename($file),
      $line === null ? '' : ':',
      $line === null ? '' : $line
    );
    if (is_array($seen))
      $seen[] = "$file:$line";
    if (!count($trace))
      break;
    $file = array_key_exists('file', $trace[0]) ? $trace[0]['file'] : 'Unknown Source';
    $line = array_key_exists('file', $trace[0]) && array_key_exists('line', $trace[0]) && $trace[0]['line'] ? $trace[0]['line'] : null;
    array_shift($trace);
  }
  $result = join("\n", $result);
  if ($prev)
    $result .= "\n" . trace($prev, $seen);

  return $result;
}
