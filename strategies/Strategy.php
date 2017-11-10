<?php

namespace strategies;

/**
 * Class Strategy
 * @author Mark Johndy Coprada
 */
abstract class Strategy
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
  public function set_api_key($key)
  {
    $this->api_key = $key;
  }

  /**
   * undocumented function
   *
   * @return void
   */
  public function get($request)
  {
    // Initiate cURL.
    $ch = curl_init($request);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    //Execute the request
    $response = curl_exec($ch);

    // Cleanup.
    curl_close($ch);

    return $response;
  }

  /**
   * undocumented function
   *
   * @return void
   */
  abstract public function get_response($cached);

  /**
   * undocumented function
   *
   * @return void
   */
  abstract protected function format_response($response);
}
