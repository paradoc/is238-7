<?php

namespace helper;

require_once(__DIR__.'/../strategies/IMDB.php');
use \strategies\IMDB as IMDB;

$_COMMANDS = [
  'ECHO', 'IMDB', 'PHP', 'WEATHER',
  'PHONE', 'GENDER', 'RECIPE', 'POKEDEX',
  'IP', 'HISTORY', 'TRUMP', 'UNIVERSITY',
  /* TODO: Add custom command. */
];

/**
 * Class Handler
 * @author Mark Johndy Coprada
 */
class Handler
{
  /**
   * @param mixed $message
   * @param mixed $session_data
   */
  public function __construct($message, $session_data)
  {
    $this->message = $message;
    $this->session_data = json_decode($session_data, true);
  }

  /**
   * undocumented function
   *
   * @return void
   */
  public function handle_request()
  {
    /* TODO: Update error messages. */
    $err = $this->lex();
    if ($err) {
      throw new \Exception('Error in token: '.$err);
    }

    list($response, $data, $err) = $this->parse();
    if ($err) {
      throw new \Exception($err);
    }

    return [$response, $data];
  }

  /**
   * undocumented function
   *
   * @return void
   */
  private function lex()
  {
    global $_COMMANDS;
    $err = null;

    $this->message_arr = explode(' ', $this->trim_whitespaces($this->message));
    $this->token = array_shift($this->message_arr);
    $this->message = implode(' ', $this->message_arr);

    if (!$this->is_valid_token($this->token)) {
      $err = $this->token;

      // Try analyzing possibilities
      $match = $this->analyze_token($this->token);

      if ($match)
        $err .= '. Did you mean '.$match.'?';
    }

    return $err;
  }

  /**
   * undocumented function
   *
   * @return void
   */
  private function is_valid_token($token)
  {
    global $_COMMANDS;
    $is_valid = false;

    if (in_array(strtoupper($token), $_COMMANDS)) {
      $is_valid = true;
    } else if (is_numeric($token)) {
      $len = count($this->session_data['data']);

      if ((intval($token) >= $len) || (intval($token) <= $len))
        $is_valid = true;
    }

    return $is_valid;
  }

  /**
   * undocumented function
   *
   * @return void
   */
  private function analyze_token($token)
  {
    global $_COMMANDS;
    $r = '/'.implode('|', $_COMMANDS).'/i';
    $match = null;

    preg_match($r, $token, $matches);

    // Return first match.
    if ($matches)
      $match = array_shift($matches);

    return $match;
  }


  /**
   * undocumented function
   *
   * @return void
   */
  private function trim_whitespaces($message)
  {
    $message_arr = explode(' ', $message);
    $trimmed = [];

    foreach ($message_arr as $value) {
      if ($value)
        array_push($trimmed, $value);
    }

    return implode(' ', $trimmed);
  }


  /**
   * undocumented function
   *
   * @return void
   */
  private function parse()
  {
    $strategy = $data = $err = null;
    $cached = false;
    $response = '';
    $token = strtoupper($this->token);
    $message = $this->message;

    if ($this->session_data) {
      $cached = true;
      $selection = intval($token) - 1;
      $token = $this->session_data['type'];
      $message = $this->session_data['data'][$selection];
    }

    // Forward request to strategies.
    switch ($token) {
      case 'IMDB':
        $strategy = new IMDB($message['id']);
        break;
      default:
        $err = 'Unknown error in parsing token: '.$token;
        break;
    }

    if ($strategy) {
      list($response, $data, $err) = $strategy->get_response($cached);
    }

    return [$response, $data, $err];
  }
}
