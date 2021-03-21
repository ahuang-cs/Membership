<?php

$db = nezamy_app()->db;
$tenant = nezamy_app()->tenant;

unset($_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['AssRegStage']);

try {
  $login = new \CLSASC\Membership\Login($db);
  $login->setUser($_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['AssRegGuestUser']);
  $login->stayLoggedIn();
  $login->preventWarningEmail();
  $currentUser = nezamy_app()->user;
  $currentUser = $login->login();
} catch (Exception $e) {
  halt(403);
}

unset($_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['AssRegGuestUser']);

$requiresRegistration = $db->prepare("SELECT `RR` FROM users WHERE UserID = ?");
$requiresRegistration->execute([$_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['UserID']]);

if (bool($requiresRegistration->fetchColumn()) && $tenant->getBooleanKey('REQUIRE_FULL_REGISTRATION')) {
  header("Location: " . autoUrl("onboarding/go"));
} else {
  // Ensure RR is false
  $updateRR = $db->prepare("UPDATE `users` SET `RR` = ? WHERE UserID = ?");
  $updateRR->execute([
    0,
    $_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['UserID']
  ]);

  header("Location: " . autoUrl(""));
}
