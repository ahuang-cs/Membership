<?php

$currentUser = app()->user;
$cvp = 'generic';
if (env('IS_CLS') && $currentUser != null && $currentUser->getUserBooleanOption('UsesGenericTheme')) {
  $cvp = 'generic';
} else if (bool(env('IS_CLS'))) {
  $cvp = 'chester';
}

include $cvp . '/header.php';
