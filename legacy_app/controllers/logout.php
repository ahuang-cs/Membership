<?php
  $user = $_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['UserID'];

  $_SESSION['TENANT-' . nezamy_app()->tenant->getId()] = null;
  unset($_SESSION['TENANT-' . nezamy_app()->tenant->getId()]);

  $secure = true;
  if (nezamy_app('request')->protocol == 'http') {
    $secure = false;
  }

  setcookie(COOKIE_PREFIX . 'TENANT-' . nezamy_app()->tenant->getId() . '-' . 'AutoLogin', "", 0, "/", nezamy_app('request')->hostname('request')->hostname, $secure, false);

  if (isset($_COOKIE[COOKIE_PREFIX . 'TENANT-' . nezamy_app()->tenant->getId() . '-' . 'AutoLogin'])) {
    // Unset the hash.
    $db = nezamy_app()->db;
    $unset = $db->prepare("UPDATE userLogins SET HashActive = ? WHERE Hash = ? AND UserID = ?");
    $unset->execute([
      0,
      $_COOKIE[COOKIE_PREFIX . 'TENANT-' . nezamy_app()->tenant->getId() . '-' . 'AutoLogin'],
      $user
    ]);
  }

  header("Location: " . autoUrl("", false));
?>
