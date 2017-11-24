<?php

namespace strategies;

require_once('Strategy.php');
use \strategies\Strategy as Strategy;

/**
 * Class Trump
 * @author Aneurin Boquer
 */
class Trump extends Strategy
{
  private $url = 'https://api.tronalddump.io/search/quote?query=';

  /**
   * Formats raw response received from API.
   *
   * @param mixed $response Response received from HTTP Get.
   * @return string Formatted response to be replied back to sender.
   */
  protected function format_response($response)
  {
    $formatted = [];
    $response_arr = json_decode($response, true);

    if (!$response_arr['_embedded']['quotes'][0]['value'])
    {
      return 'No data available. Pls. try again.';
    }
    else
    {
      $formatted = $response_arr['_embedded']['quotes'][0]['value'];
      $formatted = preg_replace('/\\"/', '\'', $formatted);
      $formatted = preg_replace('/\\n/', '', $formatted);
    }

    return $formatted;
  }

  /**
   * Parses the request and sends a GET request to the URL.
   *
   * @return array Contains the response message and errors if any.
   */
  public function get_response()
  {
    $response = $err = null;

    // Return as we require requests to be not empty.
    if (!$this->request) {
      $err = 'Please input a keyword from Trump\'s quotes.';
      return [$response, $err];
    }

    // Sanitize request.
    $this->request = $this->sanitize($this->request);

    // Form request URL.
    $url = $this->url.''.$this->request;

    // Get data and format response.
    $response = $this->get($url);
    $response = $this->format_response($response);

    return [$response, $err];
  }
}
