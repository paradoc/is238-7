<?php

namespace helper;

require_once(__DIR__.'/../strategies/IMDB.php');
use \strategies\IMDB as IMDB;
require_once(__DIR__.'/../strategies/IP.php');
use \strategies\IP as IP;
require_once(__DIR__.'/../strategies/GENDER.php');
use \strategies\GENDER as GENDER;
require_once(__DIR__.'/../strategies/HISTORY.php');
use \strategies\HISTORY as HISTORY;
require_once(__DIR__.'/../strategies/University.php');
use \strategies\University as University;
require_once(__DIR__.'/../strategies/Pokedex.php');
use \strategies\Pokedex as Pokedex;
require_once(__DIR__.'/../strategies/PHP.php');
use \strategies\PHP as PHP;
require_once(__DIR__.'/../strategies/theEcho.php');
use \strategies\theEcho as theEcho;

$_COMMANDS = [
  'IMDB', 'PHP', 'WEATHER', 'PHONE', 'GENDER', 'RECIPE', 'POKEDEX', 'IP',
  'HISTORY', 'TRUMP', 'UNIVERSITY', 'ECHO'
];

/**
 * Class Handler
 * @author Mark Johndy Coprada
 */
class Handler
{
  /**
   * @param string $message The received message to be parsed.
   */
  public function __construct($message)
  {
    $this->message = $message;
  }

  /**
   * Handles the request using a lexer and parser which eventually sends back
   * a response to be handled back to the FbHelper class.
   *
   * @throws Exception when an error occurs while processing the request.
   * @return mixed String if the API returned only 1 response; Array otherwise.
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
      throw new \Exception($err);
    }

    return $response;
  }

  /**
   * Main logic for lexical analysis.
   *
   * @return mixed String if errors occur; null otherwise.
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
   * Analyzes the current token in hand if it matches the commands available.
   *
   * @return mixed Matched command; null otherwise.
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
   * Trims the input of extra whitespaces.
   *
   * @return string Trimmed message.
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
   * Main logic for parsing. This forwards request to appropriate strategies.
   *
   * @return array Contains the response array/string and errors if any.
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
      case 'UNIVERSITY':
        $strategy = new University($this->message);
        break;
      case 'POKEDEX':
        $strategy = new Pokedex($this->message);
        break;
      case 'HISTORY':
        $strategy = new HISTORY($this->message);
        break;
	  case 'PHP':
        $strategy = new PHP($this->message);
        break;
      case 'ECHO':
        $strategy = new theEcho($this->message);
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
