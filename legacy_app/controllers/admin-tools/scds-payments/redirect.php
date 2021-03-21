<?php

$db = nezamy_app()->db;
$user = nezamy_app()->user;
$tenant = nezamy_app()->tenant;

$_SESSION['SCDS-Payments-Admin'] = [
  'user' => $user->getId(),
  'userNames' => [
    $user->getForename(),
    $user->getSurname(),
  ],
  'tenant' => $tenant->getId(),
];

http_response_code(302);
header("location: " . autoUrl('payments-admin', false));