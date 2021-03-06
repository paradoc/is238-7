<?php

namespace helper;
require_once('Handler.php');

$_VALID_IMG_EXTS = ['jpg', 'jpeg', 'gif', 'png'];

/**
 * Class FbHelper
 * @author Mark Johndy Coprada
 */
class FbHelper
{
  /**
   * @param string $request_data Request data passed as a message.
   * @param string $verify_token Verification token indicated in the page.
   */
  public function __construct($request_data, $verify_token)
  {
    $this->request_data = $request_data;
    $this->verify_token = $verify_token;
    $this->init();
  }

  /**
   * Initialize subscription logic to the webhook.
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
   * Sets the page's access token.
   *
   * @param string $access_token Access token generated by the page.
   * @return void
   */
  public function set_access_token($access_token)
  {
    $this->access_token = $access_token;
  }

  /**
   * Gets the request data from the given input string.
   *
   * @return array Contains the sender's ID and message.
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
   * Analyzes the given URL if it is a valid image URL.
   *
   * @param string $url URL string.
   * @return boolean True if the URL is an image URL; False otherwise.
   */
  private function is_image($url)
  {
    global $_VALID_IMG_EXTS;
    $is_valid = false;

    $parts = explode('.', $url);
    $extension = array_pop($parts);

    if (in_array($extension, $_VALID_IMG_EXTS))
      $is_valid = true;

    return $is_valid;
  }

  /**
   * Sends a response back to the sender using HTTP Post.
   *
   * @param string $message The message to be sent.
   * @return void
   */
  private function send_response($message)
  {
    $api_url = 'https://graph.facebook.com/v2.6/me/messages?access_token='
      .$this->access_token;

    $has_image = $this->is_image($message);

    // Form message part.
    $message_part = '"message": {';

    if ($has_image) {
      $message_part .= '"attachment": {'
        .'"type": "image",'
        .'"payload": {'
          .'"url": "'.$message.'",'
          .'"is_reusable": "true"'
          .'}'
        .'}';
    } else {
      $message_part .= '"text": "'.$message.'",';
    }

    $message_part .= '}';

    // Form the rest of the response body.
    $response = "{
        \"recipient\": {
            \"id\": \"{$this->get_request_data()['sender']}\"
        },
        {$message_part}
    }";

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
   * Processes the request and sends the response back to the sender.
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
