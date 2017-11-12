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
    // file_put_contents('php://stderr', print_r($response_arr, TRUE));
    //$formatted = $response_arr['Title'].' ('.$response_arr['Year'].')\n'
    //  .$response_arr['Plot'];

    $formatted = ucfirst($response_arr['name']).' is most probably '.$response_arr['gender'].'.';

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