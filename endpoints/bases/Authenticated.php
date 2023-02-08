<?php

abstract class Authenticated extends Endpoint
{
  /**
   * Endpoints that require user authentication
   */
  protected $user = null;

  protected function init($expect, $request)
  {
    /**
     * Add token to expected array
     * @param array $expect: expectation array
     * @param array $request: request array to be validated
     */
    $expect['token'] = [true, Regex::JWT];
    parent::init($expect, $request);
  }

  protected function prehandle(): ?Response
  {
    /**
     * Authenticate user if request is good
     */
    if ($response = parent::prehandle())
      return $response;

    if ($user = Authenticator::auth($this->request['token']))
      $this->user = $user;

    if (!$this->user)
      return new Response(UNAUTHORIZED);

    unset($this->request['token']);
    return null;
  }

  public function getUser()
  {
    /**
     * Returns the request sender's ID
     */
    
    if($this->user)
      return $this->user;
      
    return null;
  }
}
