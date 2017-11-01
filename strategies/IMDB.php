<?php

namespace strategies;

require_once('Strategy.php');
use \strategies\Strategy as Strategy;

/**
 * Class IMDB
 * @author Mark Johndy Coprada
 */
class IMDB implements Strategy
{
  /**
   * @param mixed $request
   */
  public function __construct($request)
  {
    $this->request = $request;
    $this->api_key = null;
  }

  /**
   * undocumented function
   *
   * @return void
   */
  public function get_response()
  {
    $err = null;
    $response = 'Not implemented yet.';

    return [$response, $err];
  }

  /**
   * undocumented function
   *
   * @return void
   */
  public function set_api_key($key)
  {
    $this->api_key = $key;
  }

  /**
   * undocumented function
   *
   * @return void
   */
  private function forward_request()
  {
    return null;
  }
}
