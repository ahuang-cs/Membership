<?php

$currentUser = null;
if (isset(nezamy_app()->user)) {
  $currentUser = nezamy_app()->user;
}
$cvp = 'generic';
if (isset(nezamy_app()->tenant) && nezamy_app()->tenant->isCLS() && $currentUser != null && $currentUser->getUserBooleanOption('UsesGenericTheme')) {
  $cvp = 'generic';
} else if (isset(nezamy_app()->tenant) && nezamy_app()->tenant->isCLS()) {
  $cvp = 'chester';
}

include $cvp . '/GlobalHead.php';
