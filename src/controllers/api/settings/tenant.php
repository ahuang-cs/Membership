<?php

/**
 * API for tenant settings
 */

$db = app()->db;
$tenant = app()->tenant;

$output = [];

try {

  $keys = [
    'CLUB_NAME' => 'string',
    'CLUB_SHORT_NAME' => 'string',
    'ASA_CLUB_CODE' => 'string',
    'CLUB_EMAIL' => 'string',
    'CLUB_TRIAL_EMAIL' => 'string',
    'CLUB_WEBSITE' => 'string',
    'GOCARDLESS_USE_SANDBOX' => 'bool',
    'CLUB_ADDRESS' => 'string',
    'SYSTEM_COLOUR' => 'string',
    'ASA_DISTRICT' => 'string',
    'ASA_COUNTY' => 'string',
    'EMERGENCY_MESSAGE' => 'string',
    'EMERGENCY_MESSAGE_TYPE' => 'string',
    'GALA_CARD_PAYMENTS_ALLOWED' => 'bool',
    'USE_DIRECT_DEBIT' => 'bool',
    'MEMBERSHIP_FEE_PM_CARD' => 'bool',
    'MEMBERSHIP_FEE_PM_DD' => 'bool',
    'ENABLE_BILLING_SYSTEM' => 'bool',
    'NGB_NAME' => 'string',
    'REQUIRE_SQUAD_REP_FOR_APPROVAL' => 'bool',
    'DISPLAY_NAME_FORMAT' => 'string',
    'DEFAULT_GALA_PROCESSING_FEE' => 'int',
  ];

  foreach ($keys as $key => $type) {
    $var = $tenant->getkey($key);
    settype($var, $type);
    $output[mb_strtolower($key)] = $var;
  }

} catch (Exception $e) {
  $output = [
    'success' => false,
    'message' => $e->getMessage()
  ];
}

echo json_encode($output);
