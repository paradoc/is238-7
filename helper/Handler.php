<?php

namespace helper;

require_once(__DIR__.'/../strategies/IMDB.php');
use \strategies\IMDB as IMDB;
require_once(__DIR__.'/../strategies/IP.php');
use \strategies\IP as IP;
require_once(__DIR__.'/../strategies/GENDER.php');
use \strategies\GENDER as GENDER;

$_COMMANDS = [
  'IMDB', 'PHP', 'WEATHER', 'PHONE', 'GENDER', 'RECIPE', 'POKEDEX', 'IP',
  'HISTORY', 'TRUMP', 'UNIVERSITY', 'NETFLIX',
];

/**
 * Class Handler
 * @author Mark Johndy Coprada
 */
class Handler
{
  /**
   * @param mixed $message
   */
  public function __construct($message)
  {
    $this->message = $message;
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

    list($response, $err) = $this->parse();
    if ($err) {
      throw new \Exception('Error in parsing: '.$err);
    }

    return $response;
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

    if (in_array(strtoupper($this->token), $_COMMANDS)) {
      ; // Valid token.
    } else {
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
    $strategy = null;
    $response = '';
    $err = null;

    // Forward request to strategies.
    switch (strtoupper($this->token)) {
      case 'IMDB':
        $strategy = new IMDB($this->message);
        break;
      case 'IP':
        $strategy = new IP($this->message);
        break;
      case 'GENDER':
        $strategy = new GENDER($this->message);
        break;
      default:
        break;
    }

    if ($strategy) {
      list($response, $err) = $strategy->get_response();
    }

    return [$response, $err];
  }
}
