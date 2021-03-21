<?php

// Setup GoCardless Client

// $at = nezamy_app()->tenant->getGoCardlessAccessToken();
$client = null;
try {
  $client = SCDS\GoCardless\Client::get();
} catch (Exception $e) {
  halt(902);
}
