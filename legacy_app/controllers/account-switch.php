<?php

$db = nezamy_app()->db;

setUserOption($_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['UserID'], 'DefaultAccessLevel', $_GET['type']);
// $_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['SelectedAccessLevel'] = $_GET['type'];

$userObject = new \User($_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['UserID'], true);

if (isset($_GET['redirect'])) {
  header("location: " . urldecode($_GET['redirect']));
} else {
  header("location: " . autoUrl(""));
}
