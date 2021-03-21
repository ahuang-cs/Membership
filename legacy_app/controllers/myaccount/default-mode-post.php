<?php

$fluidContainer = true;

$db = nezamy_app()->db;
$currentUser = nezamy_app()->user;

$perms = $currentUser->getPrintPermissions();
$default = $currentUser->getUserOption('DefaultAccessLevel');

foreach ($perms as $key => $value) {
  if ($_POST['selector'] == $key) {
    $currentUser->setUserOption('DefaultAccessLevel', $key);
    $_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['SavedChanges'] = true;
    break;
  }
}

header("location: " . autoUrl("my-account/default-access-level"));