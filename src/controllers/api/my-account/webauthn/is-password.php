<?php

// Checks if username has WebAuthn creds

$db = app()->db;
$tenant = app()->tenant;

$email = isset($_GET['email']) ? $_GET['email'] : "";

$getCount = $db->prepare("SELECT COUNT(*) FROM users INNER JOIN userCredentials ON users.UserID = userCredentials.user_id WHERE users.EmailAddress = ? AND users.Tenant = ?");
$getCount->execute([
  $email,
  $tenant->getId(),
]);

echo json_encode([
  "has_webauthn" => $getCount->fetchColumn() > 0,
]);