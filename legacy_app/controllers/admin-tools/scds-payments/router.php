<?php

$this->get('/', function() {
  if (nezamy_app()->user->hasPermission('SCDSPaymentsManager')) {
    include 'redirect.php';
  } else {
    include 'no-access.php';
  }
});