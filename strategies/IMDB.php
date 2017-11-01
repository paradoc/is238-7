<?php

namespace strategies;

require_once('Strategy.php');
use \strategies\Strategy as Strategy;

/**
 * Class IMDB
 * @author yourname
 */
class IMDB implements Strategy
{
  /**
   * undocumented function
   *
   * @return void
   */
  public function get_response()
  {
    $err = null;
    $response = [
      'is_done' => 1,
      'message' => 'No implementation yet.',
    ];

    return [$response, $err];
  }
}
