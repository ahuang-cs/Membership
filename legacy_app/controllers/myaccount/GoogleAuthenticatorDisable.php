<?php

if (filter_var(getUserOption($_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['UserID'], "hasGoogleAuth2FA"), FILTER_VALIDATE_BOOLEAN)) {

  setUserOption($_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['UserID'], "hasGoogleAuth2FA", false);
  setUserOption($_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['UserID'], "GoogleAuth2FASecret", null);
  header("Location: " . autoUrl("my-account/googleauthenticator"));

} else {
  halt(404);
}
