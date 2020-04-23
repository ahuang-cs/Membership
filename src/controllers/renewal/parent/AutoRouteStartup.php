<?php

// Welcome to the Parent Renewal Autorouter
// The script takes the user's saved state and continues where left off
// Also controls single session progress.

$db = app()->db;

function renewalProgress($user) {
  $db = app()->db;
  $details = null;
	if (user_needs_registration($user)) {
		$details = $db->prepare("SELECT * FROM `renewalProgress` WHERE `RenewalID` = 0 AND `UserID` = ?");
	} else {
		$details = $db->prepare("SELECT * FROM `renewals` LEFT JOIN
		`renewalProgress` ON renewals.ID = renewalProgress.RenewalID WHERE
		`StartDate` <= CURDATE() AND CURDATE() <= `EndDate` AND `UserID` = ? ORDER
		BY renewals.ID DESC, renewalProgress.ID DESC");
	}
	$details->execute([$user]);
	$details = $details->fetch(PDO::FETCH_ASSOC);
	return $details;
}

function latestRenewal() {
	$db = app()->db;
	$latest = $db->query("SELECT * FROM `renewals` WHERE `StartDate` <= CURDATE()
	AND CURDATE() <= `EndDate` ORDER BY renewals.ID DESC LIMIT 1");
	$latestRenewal = $latest->fetch(PDO::FETCH_ASSOC);
	return $latestRenewal;
}

function getNextSwimmer($user, $current = 0, $rr_only = false) {
	$db = app()->db;

	if ($rr_only) {
		$query = $db->prepare("SELECT `MemberID` FROM `members` WHERE `UserID` = ? AND `MemberID` > ? AND `RR` = ?");
		$query->execute([
			$user,
			$current,
			true
		]);
		$next = $query->fetchColumn();
		return $next;
	} else {
		$query = $db->prepare("SELECT `MemberID` FROM `members` WHERE `UserID` = ? AND `MemberID` > ?");
		$query->execute([
			$user,
			$current
		]);
		$next = $query->fetchColumn();
		return $next;
	}
}

function isPartialRegistration() {
	$db = app()->db;
	// Is user RR?
	$query = $db->prepare("SELECT RR FROM users WHERE UserID = ?");
	$query->execute([$_SESSION['UserID']]);
	$userRR = bool($query->fetchColumn());

	$query = $db->prepare("SELECT COUNT(*) FROM `members` WHERE UserID = ?");
	try {
		$query->execute([$_SESSION['UserID']]);
	} catch (PDOException $e) {
		halt(500);
	}
	$total_swimmers = (int) $query->fetchColumn();
	
	$query = $db->prepare("SELECT COUNT(*) FROM `members` WHERE UserID = ? AND RR = ?");
	try {
		$query->execute([$_SESSION['UserID'], 1]);
	} catch (PDOException $e) {
		halt(500);
	}
	$new_swimmers = (int) $query->fetchColumn();
	if ($userRR && $total_swimmers > $new_swimmers) {
		return true;
	}
	return false;
}

//$currentRenewal = renewalProgress($user);
$currentRenewalDetails = renewalProgress($_SESSION['UserID']);

$renewal = null;

if ($currentRenewalDetails == null) {
	// Create a new Progress Record
	$latestRenewal = latestRenewal();

	if (user_needs_registration($_SESSION['UserID'])) {
		$renewal = 0;
	} else if ($latestRenewal == null) {
    halt(404);
	} else {
		$renewal = $latestRenewal['ID'];
	}

	$date = date("Y-m-d");

  $addRenewal = $db->prepare("INSERT INTO `renewalProgress` (`UserID`, `RenewalID`, `Date`, `Stage`, `Substage`, `Part`) VALUES (?, ?, ?, '0', '0', '0')");
  $addRenewal->execute([
    $_SESSION['UserID'],
    $renewal,
    $date
	]);
	$currentRenewalDetails = renewalProgress($_SESSION['UserID']);
} else {
	$row = latestRenewal();
	$renewal = $row['ID'];
	if (user_needs_registration($_SESSION['UserID'])) {
		$renewal = 0;
	} else if ($row == null) {
		halt(404);
	}
}

$renewalName = 'Renewal';
if (isset($currentRenewalDetails['Name'])) {
	$renewalName = $currentRenewalDetails['Name'];
} else if (user_needs_registration($_SESSION['UserID'])) {
	$renewalName = '';
}

$stage = $currentRenewalDetails['Stage'];
$substage = $currentRenewalDetails['Substage'];
$part = $currentRenewalDetails['Part'];

// End of startup code
