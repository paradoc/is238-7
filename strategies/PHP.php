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
	  
	  if (is_null($formatted_rates)){
		//$formatted = 'No ' . strtoupper($this->request) . ' currency found. Try one of the following: \n \n AUD - Australian Dollars \n BGN - Bulgarian Lev \n BRL - Brazilian Real \n CAD - Canadian Dollars \n CHF - Swiss Franc \n CNY - Chinese Yuan \n CZK - Czech Koruna \n DKK - Denmark Krone \n GBP - Great Britain Pound \n HKD - Hong Kong Dollar \n HRK - Croatia Kuna \n HUF - Hungary Forint \n IDR - Indonesia Rupiah \n ILS - Israel New Shekel \n INR - India Rupee \n JPY - Japan Yen \n KRW - South Korea Won \n MXN - Mexico Peso \n MYR - Malaysia Ringgit \n NOK - Norway Kroner \n NZD - New Zealand Dollar \n PHP - Philippine Peso \n PLN - Poland Zloty \n RON - Romania New Lei \n RUB - Russia Rouble \n SEK - Sweden Krona \n SGD - Singapore Dollar \n THB - Thailand Baht \n TRY - Turkish New Lira \n ZAR - South Africa Rand \n EUR - Euro';
		$formatted = 'No ' . strtoupper($this->request) . ' currency found. Try one of the following: \n \n AUD, BGN, BRL, CAD, CHF, CNY, CZK, DKK, GBP, HKD, HRK, HUF, IDR, ILS, INR, JPY, KRW, MXN, MYR, NOK, NZD, PHP, PLN, RON, RUB, SEK, SGD, THB, TRY, ZAR, EUR';
	  }
	  else{
		  $formatted = 'Currency Rate \n' . 'Date: ' . date("F j, Y") . '\n' . '1 ' . $response_arr['base'].' = ' . $formatted_rates . ' ' . strtoupper($this->request);
	  }
	  

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
