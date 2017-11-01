<?php

namespace helper;

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
    $response = null;

    $err = $this->lex();
    if ($err) {
      throw new \Exception('Error in token: '.$err);
    }

    $err = $this->parse();
    if ($err) {
      throw new \Exception('Error in parsing: '.$err);
    }
  }

  /**
   * undocumented function
   *
   * @return void
   */
  private function lex()
  {
    $err = null;
    global $_COMMANDS;

    $this->message_arr = explode(' ', $this->trim_whitespaces($this->message));
    $token = array_shift($this->message_arr);
    $this->message = implode(' ', $this->message_arr);

    if (in_array(strtoupper($token), $_COMMANDS)) {
      // Implement logic
    } else {
      $err = $token;

      // Try analyzing possibilities
      $match = $this->analyze_token($token);

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
    // Forward request to strategies.
    return null;
  }

}
