<?php

$db = app()->db;
$tenant = app()->tenant;

$output = [];

try {

  $json = json_decode(file_get_contents('php://input'));

  $token = trim(mb_strtolower($json->token));

  $getUser = $db->prepare("SELECT users.UserID, users.Forename, users.Surname FROM passwordTokens INNER JOIN users ON users.UserID = passwordTokens.UserID WHERE `Type` = ? AND `Token` = ? AND users.Tenant = ? ORDER BY TokenID DESC LIMIT 1");
  $getUser->execute([
    "Password_Reset",
    $token,
    $tenant->getId()
  ]);

  $user = $getUser->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    throw new Exception("No match found");
  }

  $output = [
    "success" => true,
    "user" => [
      "first_name" => $user["Forename"],
      "last_name" => $user["Surname"],
    ],
  ];
} catch (Exception $e) {

  $output = [
    "success" => false,
    "message" => $e->getMessage(),
  ];
}

echo json_encode($output);
