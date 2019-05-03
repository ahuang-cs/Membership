<?php

global $db;

define("PAYMENT_EMAILS", [
  "Email" => "payments@chesterlestreetasc.co.uk",
  "Name" => CLUB_SHORT_NAME . " Payments"
]);

//$token = "testsecret";

$raw_payload = file_get_contents('php://input');

$headers = getallheaders();
$provided_signature = $headers["Webhook-Signature"];

$calculated_signature = hash_hmac("sha256", $raw_payload, GOCARDLESS_WEBHOOK_KEY);

if ($provided_signature == $calculated_signature) {
  $payload = json_decode($raw_payload, true);

  global $db;

  // Check if $event["id"] has been done before
  $getCount = $db->prepare("SELECT COUNT(*) FROM paymentWebhookOps WHERE EventID = ?");
  // Add $event["id"] to list to show it's done
  $addEvent = $db->prepare("INSERT INTO paymentWebhookOps (EventID) VALUES (?)");

  // Each webhook may contain multiple events to handle, batched together
  foreach ($payload["events"] as $event) {
    print("Processing event " . $event["id"] . "\n");

    $getCount->execute([$event["id"]]);
    $count = $getCount->fetchColumn();

    if ($count == 0) {

      switch ($event["resource_type"]) {
        case "mandates":
          process_mandate_event($event);
          break;
				case "payments":
          process_payment_event($event);
          break;
        default:
          print("Don't know how to process an event with resource_type " . $event["resource_type"] . "\n");
          break;
      }

      $addEvent->execute([$event["id"]]);
    } else {
      echo "Event " . $event["id"] . " already processed\r\n";
    }
  }
  header("HTTP/1.1 200 OK");
} else {
  header("HTTP/1.1 498 Invalid Token");
}

function process_mandate_event($event) {
	global $link;
	include BASE_PATH . 'controllers/payments/GoCardlessSetup.php';
  switch ($event["action"]) {
    case "created":
      if (mandateExists($event["links"]["mandate"])) {
        print("Mandate " . $event["links"]["mandate"] . " has been created!\n");
      } else {
        $mandateObject = $client->mandates()->get($event["links"]["mandate"]);
        $customer = $mandateObject->links->customer;
        $query = $db->prepare("SELECT `UserID` FROM `users` WHERE `EmailAddress` = ?");
        $query->execute([($client->customers()->get($customer))->email]);

        if ($row = $query->fetch(PDO::FETCH_ASSOC)) {
          $user = $row['UserID'];

          $mandate = $event["links"]["mandate"];
          $customer = $customer;
          $bankAccount = $mandateObject->links->customer_bank_account;

      	  $bank = $client->customerBankAccounts()->get($bankAccount);
      	  $accHolderName = $bank->account_holder_name;
      	  $accNumEnd = $bank->account_number_ending;
      	  $bankName = $bank->bank_name;

          try {
            $addMandate = $db->prepare("INSERT INTO `paymentMandates` (`UserID`, `Name`, `Mandate`, `Customer`, `BankAccount`, `BankName`, `AccountHolderName`, `AccountNumEnd`, `InUse`) VALUES (?, 'Mandate', ?, ?, ?, ?, ?, ?, ?)");
            $addMandate->execute([
              $user,
              $mandate,
              $customer,
              $bankAccount,
              $bankName,
              $accHolderName,
              $accNumEnd,
              true
            ]);

            $checkDefault = $db->prepare("SELECT COUNT(*) AS ManCount, MandateID FROM `paymentMandates` WHERE `UserID` = ?");
            $checkDefault->execute([$user]);
            $result = $checkDefault->fetch(PDO::FETCH_ASSOC);
            if ($result['ManCount'] == 1) {
              $preferredCount = $db->prepare("SELECT COUNT(*) FROM paymentPreferredMandate WHERE UserID = ?");
              $preferredCount->execute([$user]);

              if ($preferredCount->fetchColumn() == 1) {
                // If no set mandate, add default
                $setDefault = $db->prepare("INSERT INTO `paymentPreferredMandate` (MandateID, UserID) VALUES (?, ?)");
                $setDefault->execute([$result['MandateID'], $user]);
              } else {
                // Else set default
                $setDefault = $db->prepare("UPDATE `paymentPreferredMandate` SET `MandateID` = ? WHERE `UserID` = ?");
                $setDefault->execute([$result['MandateID'], $user]);
              }
            }
          } catch (Exception $e) {

          }

          print("Mandate " . $event["links"]["mandate"] . " has been created! We found the user in question.\n");
        }
      }
      break;
  	case "cancelled":
      print("Mandate " . $event["links"]["mandate"] . " has been cancelled!\n");
			$mandate = $event["links"]["mandate"];

      try {
        $cancelMandate = $db->prepare("UPDATE `paymentMandates` SET `InUse` = '0' WHERE `Mandate` = ?");
        $cancelMandate->execute([$mandate]);

        $unsetDefault = $db->prepare("SELECT users.UserID, `Forename`, `Surname`, `EmailAddress`,
  			`MandateID` FROM `paymentMandates` INNER JOIN `users` ON users.UserID =
  			paymentMandates.UserID WHERE `Mandate` = ?");
        $unsetDefault->execute([$mandate]);
        if ($row = $unsetDefault->fetch(PDO::FETCH_ASSOC)) {
          $mandateID = $user['MandateID'];
          $unsetDefaultMandate = $db->prepare("DELETE FROM `paymentPreferredMandate` WHERE `MandateID` = ?");
          $unsetDefaultMandate->execute([$mandateID]);
        }

        $userID = $user['UserID'];
        $getNextMandate = $db->prepare("SELECT * FROM `paymentMandates` WHERE
        `UserID` = ? AND `InUse` = ?");
        $getNextMandate->execute([$userID, true]);

        if ($row = $getNextMandate->fetch(PDO::FETCH_ASSOC)) {
          $mandateID = $row['MandateID'];
          $setNewDefault = $db->prepare("INSERT INTO `paymentPreferredMandate` (`UserID`, `MandateID`) VALUES (?, ?)");
          $setNewDefault->execute([$userID, $mandateID]);

          $message = "<h1>Hello " . htmlspecialchars($user['Forename'] . " " . $user['Surname']) . ".</h1>
  				<p>Your Direct Debit Mandate for " . CLUB_NAME . " has been cancelled. As you had more than one direct debit set up, we've switched your default direct debit to the next available one in our list. You may want to check the details about this before we take any payments from you in order to ensure your're happy with us taking funds from that account.</p>
  				<p>Go to " . autoUrl("") . " to make any changes.</p>
  				<p>Thank you, <br>" . CLUB_NAME . "";
  				notifySend(
            $user['EmailAddress'],
            "Your Direct Debit Mandate has been Cancelled",
            $message,
            $user['Forename'] . " " . $user['Surname'],
            $user['EmailAddress'],
            PAYMENT_EMAILS
          );
        } else {
          $message = "<h1>Hello " . htmlspecialchars($user['Forename'] . " " . $user['Surname']) . ".</h1>
  				<p>Your Direct Debit Mandate for " . CLUB_NAME . " has been Cancelled. As this was your only mandate with us, you must set up a new direct debit as soon as possible at " . autoUrl("") . "</p>
          <p>If you are leaving the club you can ignore the above.</p>
  				<p>Thank you, <br>" . CLUB_NAME . "</p>";
  				notifySend(
            $user['EmailAddress'],
            "Your Direct Debit Mandate has been Cancelled",
            $message,
            $user['Forename'] . " " .	$user['Surname'],
            $user['EmailAddress'],
            PAYMENT_EMAILS
          );
        }

      } catch (Exception $e) {}

      break;
		case "transferred":
			print("Mandate " . $event["links"]["mandate"] . " has been transferred to a new bank!\n");
			$mandate = $event["links"]["mandate"];
			$bankAccount = ($client->mandates()->get($mandate))->links->customer_bank_account;

			$bank = $client->customerBankAccounts()->get($bankAccount);
		  $accHolderName = $bank->account_holder_name;
		  $accNumEnd = $bank->account_number_ending;
		  $bankName = $bank->bank_name;

      $setNewBank = $db->prepare("UPDATE `paymentMandates` SET `BankAccount` = ?, `AccountHolderName` = ?, `AccountNumEnd` = ?, `BankName` = ? WHERE `Mandate` = ?");
      $setNewBank->execute([
        $bankAccount,
        $accHolderName,
        $accNumEnd,
        $bankName,
        $mandate
      ]);

			break;
		case "expired":
			print("Mandate " . $event["links"]["mandate"] . " has expired!\n");
			$mandate = mysqli_real_escape_string($link, $event["links"]["mandate"]);
			$sql = "UPDATE `paymentMandates` SET `InUse` = '0' WHERE `Mandate` = '$mandate';";
			mysqli_query($link, $sql);

			// Get the user ID, set to another bank if possible and let them know.
			$sql = "SELECT users.UserID, `Forename`, `Surname`, `EmailAddress`,
			`MandateID` FROM `paymentMandates` INNER JOIN `users` ON users.UserID =
			paymentMandates.UserID WHERE `Mandate` = '$mandate';";
			$user = mysqli_fetch_array(mysqli_query($link, $sql), MYSQLI_ASSOC);
      $mandateID = mysqli_real_escape_string($link, $user['MandateID']);

			// If default unset
			$sql = "DELETE FROM `paymentPreferredMandate` WHERE `MandateID` =
			'$mandateID';";
			mysqli_query($link, $sql);

			$userID = mysqli_real_escape_string($link, $user['UserID']);

			$sql = "SELECT * FROM `paymentMandates` WHERE `UserID` = '$userID' AND
			`InUse` = '1';";
      $res = mysqli_query($link, $sql);
			$rows = mysqli_num_rows($res);

			if ($rows == 0) {
				$message = "<h1>Hello " . $user['Forename'] . " " . $user['Surname'] . ".</h1>
				<p>Your Direct Debit Mandate for " . CLUB_NAME . " has expired. As this was your only mandate with us, you must set up a new direct debit as soon as possible at " . autoUrl("payments") . "</p>
				<p>Thank you, <br>" . CLUB_NAME . "";
				notifySend($user['EmailAddress'], "Your Direct Debit Mandate has
				Expired", $message, $user['Forename'] . " " . $user['Surname'],
				$user['EmailAddress'],PAYMENT_EMAILS);
			} else {
        $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
        $mandateID = mysqli_real_escape_string($link, $row['MandateID']);
  			$sql = "INSERT INTO `paymentPreferredMandate` (`UserID`, `MandateID`) VALUES ('$userID', '$mandateID');";
        mysqli_query($link, $sql);
				$message = "<h1>Hello " . $user['Forename'] . " " . $user['Surname'] . ".</h1>
				<p>Your Direct Debit Mandate for " . CLUB_NAME . " has expired. As you had more than one direct debit set up, we've switched your default direct debit to the next available one in our list. You may want to check the details about this before we take any payments from you in order to ensure your're happy with us taking funds from that account.</p>
				<p>Go to " . autoUrl("payments") . " to make any changes.</p>
				<p>Thank you, <br>" . CLUB_NAME . "";
				notifySend($user['EmailAddress'], "Your Direct Debit Mandate has
				Expired", $message, $user['Forename'] . " " . $user['Surname'],
				$user['EmailAddress'], PAYMENT_EMAILS);
			}

			break;
    default:
      print("Don't know how to process a mandate " . $event["action"] . " event\n");
      break;
  }
}

function process_payment_event($event) {
	global $link;
	include BASE_PATH . 'controllers/payments/GoCardlessSetup.php';
  switch ($event["action"]) {
  	case "created":
      print("Payment " . $event["links"]["payment"] . " has been created!\n");
      if (!paymentExists($event["links"]["payment"])) {
        // Create a new Payment
        $PMkey = mysqli_real_escape_string($link, $event["links"]["payment"]);
        $payment = $client->payments()->get($PMkey);
        $status = mysqli_real_escape_string($link, $payment->status);
        $date = mysqli_real_escape_string($link, date("Y-m-d", strtotime($payment->created_at)));
        $amount = mysqli_real_escape_string($link, $payment->amount);
        $name = mysqli_real_escape_string($link, $payment->description);
        $currency = mysqli_real_escape_string($link, $payment->currency);
        $mandate = mysqli_real_escape_string($link, $payment->links->mandate);

        $sql = "SELECT `MandateID`, `UserID` FROM `paymentMandates` WHERE `Mandate` = '$mandate';";
        $result = mysqli_query($link, $sql);
        if (mysqli_num_rows($result) == 1) {
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
          $user = mysqli_real_escape_string($link, $row['UserID']);
          $mandate = mysqli_real_escape_string($link, $row['MandateID']);
          $sql = "INSERT INTO `payments`
          (`Date`, `Status`, `UserID`, `MandateID`, `Name`, `Amount`, `Currency`, `PMkey`, `Type`)
          VALUES
          ('$date', '$status', '$user', '$mandate', '$name', '$amount', '$currency', '$PMkey', 'Payment');";
          mysqli_query($link, $sql);
        }
      }
      break;
		case "customer_approval_granted":
        if (updatePaymentStatus($event["links"]["payment"])) {
  			  print("Payment " . $event["links"]["payment"] . ": Customer Approval has been Granted\n");
        } else {
          print("Failed " . $event["links"]["payment"]);
        }
			break;
		case "customer_approval_denied":
			print("Mandate " . $event["links"]["mandate"] . " has expired!\n");
			break;
    case "submitted":
      if (updatePaymentStatus($event["links"]["payment"])) {
        print("Payment " . $event["links"]["payment"] . ": Submitted\n");
      } else {
        print("Event Failed " . $event["links"]["payment"]);
      }
      break;
		case "confirmed":
      if (updatePaymentStatus($event["links"]["payment"])) {
        print("Payment " . $event["links"]["payment"] . ": Confirmed\n");
      } else {
        print("Event Failed " . $event["links"]["payment"]);
      }
      break;
		case "chargeback_cancelled":
      if (updatePaymentStatus($event["links"]["payment"])) {
        print("Payment " . $event["links"]["payment"] . ": Chargeback Cancelled\n");
      } else {
        print("Event Failed " . $event["links"]["payment"]);
      }
      break;
    case "paid_out":
      if (updatePaymentStatus($event["links"]["payment"])) {
        print("Payment " . $event["links"]["payment"] . ": Paid Out\n");
      } else {
        print("Event Failed " . $event["links"]["payment"]);
      }
      break;
		case "late_failure_settled":
      if (updatePaymentStatus($event["links"]["payment"])) {
        print("Payment " . $event["links"]["payment"] . ": Late Failure Settled\n");
      } else {
        print("Event Failed " . $event["links"]["payment"]);
      }
      break;
		case "chargeback_settled":
      if (updatePaymentStatus($event["links"]["payment"])) {
        print("Payment " . $event["links"]["payment"] . ": Chargeback Settled\n");
      } else {
        print("Event Failed " . $event["links"]["payment"]);
      }
      break;
    case "failed":
      if (updatePaymentStatus($event["links"]["payment"])) {
        print("Payment " . $event["links"]["payment"] . ": Payment Failed\n");
      } else {
        print("Event Failed " . $event["links"]["payment"]);
      }
      break;
    case "charged_back":
      if (updatePaymentStatus($event["links"]["payment"])) {
        print("Payment " . $event["links"]["payment"] . ": Charged Back\n");
      } else {
        print("Event Failed " . $event["links"]["payment"]);
      }
      break;
		case "cancelled":
      if (updatePaymentStatus($event["links"]["payment"])) {
        print("Payment " . $event["links"]["payment"] . ": Cancelled\n");
      } else {
        print("Event Failed " . $event["links"]["payment"]);
      }
      break;
		case "resubmission_requested":
      if (updatePaymentStatus($event["links"]["payment"])) {
        print("Payment " . $event["links"]["payment"] . ": Resubmission Requested\n");
      } else {
        print("Event Failed " . $event["links"]["payment"]);
      }
      break;
    default:
      print("Don't know how to process a payment " . $event["action"] . " event\n");
      break;
  }
}