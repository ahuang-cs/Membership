<?php

$db = nezamy_app()->db;
$tenant = nezamy_app()->tenant;

$target = $_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['UserID'];

$name = nezamy_app()->user->getFullName();

try {
	$query = $db->prepare("SELECT * FROM `users` WHERE `UserID` = ? AND Tenant = ?");
	$query->execute([
		$_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['UserSimulation']['RealUser'],
		$tenant->getId()
	]);

	$info = $query->fetch(PDO::FETCH_ASSOC);

	if ($info == null) {
		halt(404);
	}

	$_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['UserSimulation'] = null;
	$_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['UserSimulation'] = [];
	unset($_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['UserSimulation']);

	$_SESSION = [];

	// session_destroy();

	$_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['Username'] = 		$info['Username'];
	$_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['EmailAddress'] = $info['EmailAddress'];
	$_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['Forename'] = 		$info['Forename'];
	$_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['Surname'] = 			$info['Surname'];
	$_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['UserID'] = 			$info['UserID'];
	$_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['LoggedIn'] = 		1;

	$userObject = new \User($info['UserID'], true);
	nezamy_app()->user = $userObject;

	AuditLog::new('UserSimulation-Exited', 'Stopped simulating ' . $name);

	header("Location: " . autoUrl("users/" . $target));
} catch (Exception $e) {
	reportError($e);
	header("Location: " . autoUrl(""));
}