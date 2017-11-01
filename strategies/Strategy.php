<?php

namespace strategies;

/**
 * Interface Strategy
 * @author Mark Johndy Coprada
 */
interface Strategy
{
  public function get_response();
  public function set_api_key($key);
}
