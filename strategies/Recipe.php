<?php

namespace strategies;

require_once('Strategy.php');
use \strategies\Strategy as Strategy;

/**
 * Class Recipe
 * @author Mark Johndy Coprada
 */
class Recipe extends Strategy
{
  private $url = 'http://www.recipepuppy.com/api/';

  /**
   * Formats raw response received from API.
   *
   * @param mixed $response Response received from HTTP Get.
   * @return string Formatted response to be replied back to sender.
   */
  protected function format_response($response)
  {
    $formatted = [];
    $selected = 0;
    $response_arr = json_decode($response, true);

    if (!$response_arr['results'])
      return 'There are no dishes with that ingredient.';

    $count = count($response_arr['results']);

    if ($count > 3) {
      array_push($formatted, 'There were '.$count.' results in your search. '
        .'Here are some of them:');

      $selected = $this->randomize(count($response_arr['results']));

      foreach ($selected as $value) {
        $data = $response_arr['results'][$value];
        $title = preg_replace('/\\n/', '', $data['title']);
        $message = $title . '\n' . $data['href'];
        array_push($formatted, $message);
      }
    } else {
      for ($i = 0; $i < $count; $i++) {
        $data = $response_arr['results'][$i];
        $title = preg_replace('/\\n/', '', $data['title']);
        $message = $title . '\n' . $data['href'];
        array_push($formatted, $message);
      }
    }

    return $formatted;
  }

  /**
   * Randomizes responses if there are more than 3 results returned.
   *
   * @return array Number array of randomized responses.
   */
  private function randomize($result_count)
  {
    $selected = [];

    while(count($selected) != 3) {
      $rand = rand(0, $result_count - 1);

      if (!in_array($rand, $selected))
        array_push($selected, $rand);
    }

    return $selected;
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
      $err = 'Please input an ingredient.';
      return [$response, $err];
    }

    // Sanitize request.
    $this->request = $this->sanitize($this->request);

    // Form request URL.
    $url = $this->url.'?i='.$this->request;

    // Get data and format response.
    $response = $this->get($url);
    $response = $this->format_response($response);

    return [$response, $err];
  }
}
