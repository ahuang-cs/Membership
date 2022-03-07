<?php

$db = app()->db;
$tenant = app()->tenant;

$resetFailedLoginCount = $db->prepare("UPDATE users SET WrongPassCount = 0 WHERE UserID = ?");

use GeoIp2\Database\Reader;

$output = [];

use PragmaRX\Google2FA\Google2FA;

$ga2fa = new Google2FA();

try {

  $json = json_decode(file_get_contents('php://input'));

  if (!isset($_SESSION['TENANT-' . app()->tenant->getId()]['2FAUserID'])) {
    throw new Exception("Invalid request: No user login attempt");
  }

  $user = $_SESSION['TENANT-' . app()->tenant->getId()]['2FAUserID'];

  $secret = getUserOption($user, "GoogleAuth2FASecret");

  $auth_via_google_authenticator = false;
  try {
    $auth_via_google_authenticator = $_SESSION['TENANT-' . app()->tenant->getId()]['TWO_FACTOR_GOOGLE'] && $ga2fa->verifyKey($secret, $json->auth_code);
  } catch (Exception $e) {
    $auth_via_google_authenticator = false;
  }

  if (($json->auth_code == $_SESSION['TENANT-' . app()->tenant->getId()]['TWO_FACTOR_CODE']) || $auth_via_google_authenticator) {
    unset($_SESSION['TENANT-' . app()->tenant->getId()]['TWO_FACTOR']);
    unset($_SESSION['TENANT-' . app()->tenant->getId()]['TWO_FACTOR_CODE']);
    unset($_SESSION['TENANT-' . app()->tenant->getId()]['TWO_FACTOR_GOOGLE']);

    if ($auth_via_google_authenticator) {
      // Do work to prevent replay attacks etc.
    }

    // Record ready to login via redirect
  } else {
    throw new Exception("Invalid");
  }

  $target = autoUrl("");
  if (isset($json->target) && $json->target) {
    $target = $json->target;
  }
  if ($secret && isset($json->setup_two_factor) && $json->setup_two_factor) {
    $target = autoUrl("my-account/googleauthenticator/setup");
  }

  $url = autoUrl("/api/auth/success-redirect-flow?target=" . urlencode($json->target));

  $output = [
    'success' => true,
    'redirectUrl' => $url
  ];
} catch (Exception $e) {
  $output = [
    'success' => false,
    'message' => $e->getMessage()
  ];
}

echo json_encode($output);