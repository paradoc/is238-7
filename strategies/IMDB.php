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
  private function get_details($id)
  {
    $url = $this->url
      ."?apikey={$this->api_key}"
      ."&i={$id}&type=movie";

    $response = json_decode($this->get($url), true);

    return
      "{$response['Title']} ({$response['Year']})\\n"
      ."{$response['Plot']}";
  }

  /**
   * undocumented function
   *
   * @return void
   */
  protected function format_response($response)
  {
    $formatted = $err = null;
    $response_arr = json_decode($response, true);

    $success = $response_arr['Response'] === 'True';

    if (!$success) {
      if (array_key_exists('Error', $response_arr))
        $err = $response_arr['Error'];
      else
        $err = 'Unknown error in <format_response()>.';

      return [null, $err];
    }

    $search_data = $response_arr['Search'];
    $total = $response_arr['totalResults'];

    if ($total >= 10)
      $total = 10;

    $filtered = [
      'data' => [],
      'type' => 'IMDB',
      'is_done' => 0,
    ];

    if ($total == 1) {
      $filtered['is_done'] = 1;
      $formatted = $this->get_details($search_data[0]['imdbID']);
    } else {
      $formatted = "There were multiple results in your search. You might want"
        ." to narrow it down by being more specific. Which one were you "
        ."referring to?\\n";

      $i = 1;
      foreach ($search_data as $data) {
        $title = $data['Title'].' ('.$data['Year'].')';
        $formatted .= $i.". ".$title."\\n";
        array_push($filtered['data'], [
          'title' => $title,
          'id' => $data['imdbID'],
        ]);

        $i++;
      }
    }

    return [$formatted, $filtered, $err];
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

    if ($cached) {
      $param = 't';
    } else {
      if (!$this->request) {
        $err = 'Please input a movie title.';
        return [$response, $err];
      }

      $this->request = explode(' ',$this->request);
      $this->request = implode('%20', $this->request);
    }

    $url = $this->url
      ."?apikey={$this->api_key}"
      ."&{$param}=\"{$this->request}\""
      ."&type=movie";

    // Get data and format response.
    $response = $this->get($url);

    if ($cached)
      return [$this->get_details($this->request), ['is_done' => 1], $err];
    else
      return $this->format_response($response);
  }
}
