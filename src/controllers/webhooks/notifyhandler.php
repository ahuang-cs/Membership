<?php

ignore_user_abort(true);
set_time_limit(0);

$sql = "SELECT * FROM `notify` INNER JOIN `users` ON notify.UserID = users.UserID WHERE `Status` = 'Queued' LIMIT 4;";
$result = mysqli_query($link, $sql);

for ($i = 0; $i < mysqli_num_rows($result); $i++) {
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$emailid = $row['EmailID'];
	if ($row['EmailComms'] == 1 || $row['ForceSend'] == 1) {
		//$to = $row['EmailAddress'];
    $to = $row['Forename'] . " " . $row['Surname'] . " <" . $row['EmailAddress'] . ">";
		$name = $row['Forename'] . " " . $row['Surname'];
		$emailaddress = $row['EmailAddress'];
		$subject = $row['Subject'];
		$message = "<p>Dear " . $row['Forename'] . " " . $row['Surname'] . ",</p>" . $row['Message'];

		if (notifySend($to, $subject, $message, $name, $emailaddress)) {
			$sql = "UPDATE `notify` SET `Status` = 'Sent' WHERE `EmailID` = '$emailid';";
			mysqli_query($link, $sql);
		} else {
      $sql = "UPDATE `notify` SET `Status` = 'Failed' WHERE `EmailID` = '$emailid';";
  		mysqli_query($link, $sql);
    }
	} else {
		$sql = "UPDATE `notify` SET `Status` = 'No_Sub' WHERE `EmailID` = '$emailid';";
		mysqli_query($link, $sql);
	}
}
