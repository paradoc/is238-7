<?php
namespace strategies;
require_once('Strategy.php');
use \strategies\Strategy as Strategy;
/**
 * Class IP
 * @author Jeano Ermitano
 */
class IP extends Strategy
{
  private $url = 'https://ipapi.co/';
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

    $formatted = 'IP: '.$response_arr['ip'].'\n'
        .'City: '.$response_arr['city'].'\n'
        .'Region: '.$response_arr['region'].'\n'
        .'Country: '.$response_arr['country_name'].' ('.$response_arr['country'].')\n'
        .'Latitude/Longitude: '.$response_arr['latitude'].', '.$response_arr['longitude'].'\n'
        .'Time Zone: '.$response_arr['timezone'].'\n'
        .'Postal Code: '.$response_arr['postal'].'\n'
        .'ASN: '.$response_arr['asn'].'\n'
        .'Org: '.$response_arr['org'].'\n';

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
      $err = 'Please input an IP address.';
      return [$response, $err];
    }
    
    $url = $this->url.'/'.$this->request.'/json';
    // $url = $this->url.'?apikey='.$this->api_key.'&t='.$this->request;

    // Get data and format response.
    $response = $this->get($url);
    $response = $this->format_response($response);
    return [$response, $err];
  }
}