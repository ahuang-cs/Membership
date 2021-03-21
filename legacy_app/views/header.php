<?php

$currentUser = nezamy_app()->user;
$cvp = 'generic';
if (nezamy_app()->tenant->isCLS() && $currentUser != null && $currentUser->getUserBooleanOption('UsesGenericTheme')) {
  $cvp = 'generic';
} else if (nezamy_app()->tenant->isCLS()) {
  $cvp = 'chester';
}

include $cvp . '/header.php';
