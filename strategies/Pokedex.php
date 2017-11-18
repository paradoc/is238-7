<?php

namespace strategies;

require_once('Strategy.php');
use \strategies\Strategy as Strategy;

/**
 * Class Pokedex
 * @author Mark Johndy Coprada
 */
class Pokedex extends Strategy
{
  private $url = 'https://pokeapi.co/api/v2/';

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

    if (!$response_arr || array_key_exists('detail', $response_arr))
      return 'There is no Pokemon with that name.';

    $sprite = $response_arr['sprites']['front_default'];
    $type = $response_arr['types'][0]['type']['name'];
    $height = $response_arr['height'];
    $weight = $response_arr['weight'];
    $message = $this->request . ' is a(n) ' . $type . ' type pokemon. '
      .'Its height is ' . $height . ' and its weight is ' .$weight .'.';

    array_push($formatted, $sprite, $message);

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
      $err = 'Please input a Pokemon.';
      return [$response, $err];
    }

    // Sanitize request.
    $this->request = $this->sanitize($this->request);

    // Form request URL.
    $url = $this->url.'pokemon/'.$this->request.'/';

    // Get data and format response.
    $response = $this->get($url);
    $response = $this->format_response($response);

    return [$response, $err];
  }
}
