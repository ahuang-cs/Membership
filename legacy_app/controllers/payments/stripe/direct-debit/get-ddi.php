<?php

$db = nezamy_app()->db;
$tenant = nezamy_app()->tenant;

// Get mandates
$getMandates = $db->prepare("SELECT `URL` FROM stripeMandates WHERE Customer = ? AND ID = ?");
$getMandates->execute([
  nezamy_app()->user->getStripeCustomer()->id,
  $id,
]);
$url = $getMandates->fetchColumn();

if (!$url) {
  halt(404);
} else {
  http_response_code(302);
  header("location: " . $url);
}