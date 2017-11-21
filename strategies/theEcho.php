<?php

namespace strategies;

require_once('Strategy.php');
use \strategies\Strategy as Strategy;

/**
 * Class IMDB
 * @author Mark Johndy Coprada
 */
class theEcho extends Strategy
{

  /**
   * undocumented function
   *
   * @return void
   */
  protected function format_response($response)
  {
    $formatted = null;
    $response_arr = json_decode($response, true);

    // file_put_contents('php://stderr', print_r($response_arr, TRUE));

    $formatted = $response_arr['Title'].' ('.$response_arr['Year'].')\n'
      .$response_arr['Plot'];

    return $formatted;
  }

  /**
   * undocumented function
   *
   * @return void
   */
  public function get_response()
  {
    $response = $err = null;

    if (!$this->request) {
      $err = 'Please write a message.';
      return [$response, $err];
    }


    // Get data and format response.
    $response = $this->request;

    return [$response, $err];
  }
}
