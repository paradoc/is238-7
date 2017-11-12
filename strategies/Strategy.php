<?php

namespace strategies;

/**
 * Class Strategy
 * @author Mark Johndy Coprada
 */
abstract class Strategy
{
  /**
   * @param string $request API request parameter.
   */
  public function __construct($request)
  {
    $this->request = $request;
    $this->api_key = null;
  }

  /**
   * Sets the API key.
   *
   * @param string $key API key to be set.
   * @return void
   */
  public function set_api_key($key)
  {
    $this->api_key = $key;
  }

  /**
   * cURL interface for HTTP Get request.
   *
   * @param string $request URL request with parameters.
   * @return mixed Response from cURL request.
   */
  public function get($request)
  {
    // Initiate cURL and set options.
    $ch = curl_init($request);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the request.
    $response = curl_exec($ch);

    // Cleanup.
    curl_close($ch);

    return $response;
  }

  /**
   * Converts whitespaces into '%20'.
   *
   * @return string Sanitized input.
   */
  protected function sanitize($input)
  {
    $sanitized = explode(' ', $input);
    $sanitized = implode('%20', $sanitized);

    return $sanitized;
  }

  /**
   * Exposed method to be overridden by concrete strategies.
   * Parses the request and sends a GET request to the URL.
   *
   * @return array Contains the response message and errors if any.
   */
  abstract public function get_response();

  /**
   * Protected method to be overridden by concrete strategies.
   * Formats raw response received from API.
   *
   * @param mixed $response Response received from HTTP Get.
   * @return array Contains the formatted response.
   */
  abstract protected function format_response($response);
}
