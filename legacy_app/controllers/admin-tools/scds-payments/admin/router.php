<?php

if (!isset($_SESSION['SCDS-Payments-Admin'])) {
  $this->get(['/', '/*'], function () {
    halt(404);
  });
} else {

  nezamy_app()->adminCurrentTenant = Tenant::fromId($_SESSION['SCDS-Payments-Admin']['tenant']);
  nezamy_app()->adminCurrentUser = new User($_SESSION['SCDS-Payments-Admin']['user']);

  $this->get('/', function () {
    include 'home.php';
  });

  $this->get('/exit', function () {
    include 'exit.php';
  });

  $this->group('/payment-methods', function () {
    include 'payment-methods/router.php';
  });

  $this->group('/direct-debit-instruction', function () {
    include 'payment-methods/mandates/router.php';
  });

  $this->group('/payment-cards', function () {
    include 'payment-methods/cards/router.php';
  });

  $this->group('/subscriptions', function () {
    include 'subscriptions/router.php';
  });

}
