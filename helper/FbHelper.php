<?php

namespace helper;
require_once('Handler.php');

/**
 * Class FbHelper
 * @author Mark Johndy Coprada
 */
class FbHelper
{
  /**
   * @param mixed $access_token
   */
  public function __construct($request_data, $verify_token)
  {
    $this->request_data = $request_data;
    $this->verify_token = $verify_token;
    $this->init();
  }

  /**
   * undocumented function
   *
   * @return void
   */
  public function init()
  {
    $hub_verify_token = null;

    if (isset($this->request_data['hub_challenge'])) {
      $hub_challenge = $this->request_data['hub_challenge'];
      $hub_verify_token = $this->request_data['hub_verify_token'];
    }

    if ($hub_verify_token === $this->verify_token) {
      echo $hub_challenge;
    }
  }

  /**
   * undocumented function
   *
   * @return void
   */
  public function set_access_token($access_token)
  {
    $this->access_token = $access_token;
  }

  /**
   * undocumented function
   *
   * @return void
   */
  public function get_request_data()
  {
    if (!$this->request_data) {
      $input = json_decode(file_get_contents('php://input'), true);

      $sender = $input['entry'][0]['messaging'][0]['sender']['id'];
      $message = $input['entry'][0]['messaging'][0]['message']['text'];

      $this->request_data = [
        'sender' => $sender,
        'message' => $message,
      ];
    }

    return $this->request_data;
  }

  /**
   * undocumented function
   *
   * @return void
   */
  private function send_response($message)
  {
    $api_url = 'https://graph.facebook.com/v2.6/me/messages?access_token='
      .$this->access_token;

    // Form response.
    $response = '{
        "recipient": {
            "id": "'.$this->get_request_data()['sender'].'"
        },
        "message": {
            "text": "'.$message.'"
        }
    }';

    // Initiate cURL.
    $ch = curl_init($api_url);

    // Tell cURL that we want to send a POST request.
    curl_setopt($ch, CURLOPT_POST, 1);

    // Attach our encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $response);

    // Set the content type to application/json.
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    //Execute the request
    if ($this->get_request_data()['message']) {
        $result = curl_exec($ch);
    }
  }

  /**
   * undocumented function
   *
   * @return void
   */
  public function process_request()
  {
    $response = null;
    $sender = $this->get_request_data()['sender'];
    $message = $this->get_request_data()['message'];

    try {
      $handler = new Handler($message);
      $response = $handler->handle_request();
    } catch (\Exception $e) {
      $this->send_response($e->getMessage());
    }

    if ($response) {
      /* TODO: Globalize responses as arrays */
      if (is_array($response)) {
        foreach ($response as $resp)
          $this->send_response($resp);
      } else {
        $this->send_response($response);
      }
    }
  }
}
