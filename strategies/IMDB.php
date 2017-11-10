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
   * undocumented function
   *
   * @return void
   */
  protected function format_response($response)
  {
    $formatted = null;
    $response_arr = json_decode($response, true);

    file_put_contents('php://stderr', print_r($response_arr, TRUE));

    $formatted = $response_arr['Title'].' ('.$response_arr['Year'].')\n'
      .$response_arr['Plot'];

    return $formatted;
  }

  /**
   * undocumented function
   *
   * @return void
   */
  public function get_response($cached)
  {
    $response = $err = null;
    $this->set_api_key('d8b8ba2c');

    // Search.
    $param = 's';

    if ($cached)
      $param = 't';

    if (!$this->request) {
      $err = 'Please input a movie title.';
      return [$response, $err];
    }

    $url = $this->url.'?apikey='.$this->api_key.'&'.$param.'='.$this->request;

    // Get data and format response.
    $response = $this->get($url);
    $response = $this->format_response($response);

    return [$response, $err];
  }
}
