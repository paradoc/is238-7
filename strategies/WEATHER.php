<?php

namespace strategies;

require_once('Strategy.php');
use \strategies\Strategy as Strategy;

/**
 * Class WEATHER
 *
 */
class WEATHER extends Strategy
{
  private $url = 'http://api.openweathermap.org/data/2.5/weather';

  /**
   * undocumented function
   *
   * @return void
   */
  protected function format_response($response)
  {
    $formatted = [];
    $response_arr = json_decode($response, true);

    if ($response_arr['message'] === 'city not found')
      return 'City not found!';

    $iconid = 'http://openweathermap.org/img/w/'. $response_arr['weather'][0]['icon'] . '.png';
    $weather = 'Weather in ' . $this->request . ' : ' . ucwords($response_arr['weather'][0]['description']);
    $temp = 'Feels like : ' . round(($response_arr['main']['temp'] - 273.15)) .'℃';
    $wind = 'Wind : ' . round($response_arr['wind']['speed'] * 2.23694) . ' m/h';
    $humidity = 'Humidity : ' . $response_arr['main']['humidity'] . '%';
    $pressure = 'Pressure : ' . $response_arr['main']['pressure']. ' hPa';

    $message = $weather . '\n' . $temp . '\n' . $wind  . '\n' . $humidity . '\n' . $pressure;

    array_push($formatted, $iconid, $message);

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
      $err = 'Please enter location.';
      return [$response, $err];
    }

    // Form request URL.
    $url = $this->url.'?q='.$this->request.'&appid=8d97cce863af0b8e4878f3937b456d36';

    // Get data and format response.
    $response = $this->get($url);
    $response = $this->format_response($response);

    return [$response, $err];
  }
}
