<?php

namespace strategies;

require_once('Strategy.php');
use \strategies\Strategy as Strategy;

/**
 * Class History
 * @author Jeano Ermitaño
 */
class HISTORY extends Strategy
{
  private $url = 'http://numbersapi.com';

  
  protected function format_response($response)
  {
    /*
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
    */
  }
  

  public function get_response()
  {
    $response = $err = null;

    // if no request, assume the current date will be used
    if (!$this->request) {
        // Form request URL
        $url = $this->url.'/'.date("m").'/'.date("d");

        // Get data and format response.
        $response = $this->get($url);
    }

    else {
        $date = explode(' ',$this->request);

        if (count($date) !== 2) {
            $err = "Sorry, syntax error. Try using two numbers for month and day, for September 7 for example, HISTORY 9 7.";
        }

        else if (!is_numeric($date[0]) || !is_numeric($date[1])) {
            $err = "Sorry, I don't understand long dates. Try using numbers for month and day, for September 7 for example, HISTORY 9 7.";
        }

        else if ($date[0] > 12 || $date[1] > 31 || ($date[0] == 2 && $date[1] > 29)) {
            $err = "Sorry, that's an invalid date. Please try another.";
        }

        else {
              // Form request URL
              $url = $this->url.'/'.$date[0].'/'.$date[1];

              // Get data and format response.
              $response = $this->get($url);
            }
        }
      return [$response, $err];
    }
}
