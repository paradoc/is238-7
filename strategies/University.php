<?php

namespace strategies;

require_once('Strategy.php');
use \strategies\Strategy as Strategy;

/**
 * Class University
 * @author Mark Johndy Coprada
 */
class University extends Strategy
{
  private $url = 'http://universities.hipolabs.com/';

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

    if (!$response_arr)
      return 'There are no universities with that name.';

    $count = count($response_arr);

    if ($count > 1) {
     array_push($formatted, "There were {$count} results in your search. "
       ."You might want to narrow it down further? "
       ."Anyway, here are a few of them with their respective websites:");

      $i = 1;
      foreach ($response_arr as $university) {
        $data = $i.'. '.$university['name'].': ';

        if ($university['web_pages'])
          $data .= array_pop($university['web_pages']);
        else
          $data .= 'n/a';

        array_push($formatted, $data);

        if ($i++ == 3) break;
      }
    } else {
      array_push($formatted, $response_arr[0]['name'].': '
        .$response_arr[0]['web_pages'][0]);
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
      $err = 'Please input a university name.';
      return [$response, $err];
    }

    // Sanitize request.
    $this->request = $this->sanitize($this->request);

    // Form request URL.
    $url = $this->url.'search?name='.$this->request;

    // Get data and format response.
    $response = $this->get($url);
    $response = $this->format_response($response);

    return [$response, $err];
  }
}
