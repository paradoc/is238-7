<?php

namespace strategies;

require_once('Strategy.php');
use \strategies\Strategy as Strategy;

/**
 * Class Phone
 * @author Aneurin Boquer
 */
class Phone extends Strategy
{
  private $url = 'http://apilayer.net/api/validate';

  /**
   * Formats raw response received from API.
   *
   * @param mixed $response Response received from HTTP Get.
   * @return string Formatted response to be replied back to sender.
   */
  protected function format_response($response)
  {
    $formatted = [];
    $country = NULL;
	$response_arr = json_decode($response, true);

    if (!$response_arr['country_name']) 
		{
		return 'No data available. Pls. try again.';
		}
	else 
		{
		$formatted = $response_arr['country_name'];
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
      $err = 'Please input a Phone number.';
      return [$response, $err];
    }

    // Sanitize request.
    $this->request = $this->sanitize($this->request);

    // Form request URL.
	$this->set_api_key('870d2a71dc07f91ac59e27586457d28b');
    $url = $this->url.'?access_key='.$this->api_key.'&number='.$this->request;

    // Get data and format response.
    $response = $this->get($url);
    $response = $this->format_response($response);

    return [$response, $err];
  }
}
