<?php

namespace strategies;

require_once('Strategy.php');
use \strategies\Strategy as Strategy;

/**
 * Class PHP
 */
class PHP extends Strategy
{
  private $url = 'https://api.fixer.io/';

  /**
   * undocumented function
   *
   * @return void
   */
  protected function format_response($response)
  {
    $formatted = [];
    $response_arr = json_decode($response, true);

    // file_put_contents('php://stderr', print_r($response_arr, TRUE));
	
	#$formatted = '1' . $response_arr['rates[\'symbols\']'];
	
	echo $formatted_rates = $response_arr['rates'][strtoupper($this->request)];
	  
    $formatted = 'Currency Rate \n' . 'Date: ' . date("F j, Y") . '\n' . '1 ' . $response_arr['base'].' = ' . $formatted_rates . ' ' . strtoupper($this->request);

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
      $err = 'Please provide a currency.';
      return [$response, $err];
    }

    // Form request URL.
    #$this->set_api_key('d8b8ba2c');
    $url = $this->url.'latest?base=USD';

    // Get data and format response.
    $response = $this->get($url);
    $response = $this->format_response($response);

    return [$response, $err];
  }
}
