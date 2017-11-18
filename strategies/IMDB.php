<?php

namespace strategies;

require_once('Strategy.php');
use \strategies\Strategy as Strategy;

/**
 * Class IMDB
 * @author Mark Johndy Coprada
 */
class IMDB extends Strategy
{
  private $url = 'http://www.omdbapi.com/';

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

    if ($response_arr['Response'] === 'False')
      return $response_arr['Error'];

    $poster = $response_arr['Poster'];
    $message = $response_arr['Title'].' ('.$response_arr['Year'].')\n'
      .'IMDB Rating: '.$response_arr['imdbRating'].'\n'
      .'Plot: '.$response_arr['Plot'];

    array_push($formatted, $poster, $message);

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
      $err = 'Please input a movie title.';
      return [$response, $err];
    }

    // Sanitize request.
    $this->request = $this->sanitize($this->request);

    // Form request URL.
    $this->set_api_key('d8b8ba2c');
    $url = $this->url.'?apikey='.$this->api_key.'&t='.$this->request;

    // Get data and format response.
    $response = $this->get($url);
    $response = $this->format_response($response);

    return [$response, $err];
  }
}
