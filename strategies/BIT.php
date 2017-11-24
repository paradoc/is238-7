<?php
namespace strategies;
require_once('Strategy.php');
use \strategies\Strategy as Strategy;
/**
 * Class BIT
 * @author Jeano Ermitano
 */
class BIT extends Strategy
{
  private $url = 'https://api.cryptonator.com/api/ticker';
  /**
   * undocumented function
   *
   * @return void
   */
  protected function format_response($response)
  {
    $formatted = null;
    $response_arr = json_decode($response, true);
    if (!$response_arr['success'])
      return "Sorry, I don't recognize that coin. Please try another.";
    else {
      $ticker = $response_arr['ticker'];
      $formatted = '1 '.$ticker['base'].' = '.$ticker['price'].' '.$ticker['target'];
      return $formatted;
    }
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
      $err = 'Please type in the cryptocurrency symbol (e.g. BTC, ETH, XRP).';
    }
    else {
        $url = $this->url.'/'.$this->request.'-usd';

        // Get data and format response.
        $response = $this->get($url);
        $response = $this->format_response($response);
    }
    return [$response, $err];
  }
}