<?php

\Stripe\Stripe::setApiKey(getenv('STRIPE'));
$db = nezamy_app()->db;

if (!isset($_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['GalaPaymentIntent'])) {
  halt(404);
}

handleCompletedGalaPayments($_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['GalaPaymentIntent'], true);