<?php

require_once('helper/FbHelper.php');
use helper\FbHelper as FbHelper;

$access_token = 'EAAKoOMWC0kgBAIHmpOIyG6cAQztPCj5XSZBiCf6Uyqe4EifVlc26RVndCoxFM7ZAIksuwWyqOsxk0jO3ZC4gY0JtO4eyCCJuTKFOhS9ZCZB1mqaXhmKITvetojRmZCmnWr7YtxglZCFnzj962uAw2bVxZBty19EoUEvAZAnvV8I84MgZDZD';
$verify_token = 'is238';

$helper = new FbHelper($_REQUEST, $verify_token);
$helper->set_access_token($access_token);

if (!array_key_exists('hub_mode', $_REQUEST)) {
  $helper->process_request();
}
