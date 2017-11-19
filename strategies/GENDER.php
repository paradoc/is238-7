<?php
namespace strategies;
require_once('Strategy.php');
use \strategies\Strategy as Strategy;
/**
 * Class GENDER
 * @author Jeano Ermitano
 */
class GENDER extends Strategy
{
  private $url = 'https://api.genderize.io/';
  /**
   * undocumented function
   *
   * @return void
   */
  protected function format_response($response)
  {
    $formatted = null;
    $response_arr = json_decode($response, true);
    if ($response_arr['gender'] === null)
      return "Sorry, that name has not yet been genderized.";

    $formatted = ucfirst(strtolower($response_arr['name'])).' is most probably '.$response_arr['gender'].'.';

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
      $err = 'Please input a name.';
      return [$response, $err];
    }
    
    $url = $this->url.'?name='.$this->request;
    // $url = $this->url.'?apikey='.$this->api_key.'&t='.$this->request;

    // Get data and format response.
    $response = $this->get($url);
    $response = $this->format_response($response);
    return [$response, $err];
  }
}
