<?php

/*if ((!isset($preventLoginRedirect)) && (empty($_SESSION['LoggedIn']))) {
  $preventLoginRedirect = false;
  $_SESSION['requestedURL'] = mysqli_real_escape_string(LINK, $_SERVER['REQUEST_URI']);
}
elseif (!isset($preventLoginRedirect)) {
  $preventLoginRedirect = false;
  $_SESSION['requestedURL'] = mysqli_real_escape_string(LINK, $_SERVER['REQUEST_URI']);
}*/

function verifyUser($user, $password) {
  global $db;

  $username = trim($user);
  $password = trim($password);

  try {
    $query = $db->prepare("SELECT Password, UserID, AccessLevel FROM users WHERE EmailAddress = :user LIMIT 1");
    $query->execute(['user' => $username]);
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    $count = sizeof($result);

    if ($count == 1) {
      $hash = $result[0]['Password'];

      if (password_verify($password, $hash)) {
        if ($result[0]['AccessLevel'] != 'Parent') {
          return true;
        } else {
          // Verify parent has connected child
          $sql = "SELECT COUNT(*) FROM `members` WHERE `UserID` = ?";
        	try {
        		$query = $db->prepare($sql);
        		$query->execute([$result[0]['UserID']]);
            if ($query->fetchColumn() == 0) {
              halt(404);
            }
        	} catch (PDOException $e) {
        		halt(500);
        	}
        }
      }
    }
  } catch (PDOException $e) {
    halt(500);
  }
  return false;
}

function notifySend($to, $subject, $emailMessage, $name = null, $emailaddress = null, $from = ["Email" => "noreply@" . EMAIL_DOMAIN, "Name" => CLUB_NAME]) {

  $fontUrl = "https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700";
  $fontStack = '"Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"';
  $image = "<h1>" . CLUB_NAME . "</h1>";
  if (defined("IS_CLS") && IS_CLS) {
    $fontUrl = "https://fonts.googleapis.com/css?family=Open+Sans:400,700";
    $fontStack = '"Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"';
    $image = "<img src=\"" . autoUrl("public/img/notify/NotifyLogo.png") . "\"
    style=\"width:300px;max-width:100%;\" srcset=\"" .
    autoUrl("public/img/notify/NotifyLogo@2x.png") . " 2x, " .
    autoUrl("public/img/notify/NotifyLogo@3x.png") . " 3x\" alt=\"" . CLUB_NAME . " Logo\">";
  }

  $head = "
  <!DOCTYPE html>
  <html lang=\"en-gb\">
  <head>
    <meta charset=\"utf-8\">
    <link href=\"" . $fontUrl . "\" rel=\"stylesheet\" type=\"text/css\">
    <style type=\"text/css\">

      html, body {
        font-family: " . $fontStack . ";
        font-size: 16px;
        background: #e3eef6;
      }

      p, h1, h2, h3, h4, h5, h6, ul, ol, img, .table, blockquote {
        margin: 0 0 16px 0;
        font-family: " . $fontStack . ";
      }

      .small {
        font-size: 11px;
        color: #868e96;
        margin-bottom: 11px;
      }

      .text-center {
        text-align: center;
      }

      .bottom {
        margin: 16px 0 0 0;
      }

      cell {
        display: table;
        background: #eee;
        padding: 1rem;
        margin 0 0 1rem 0;
        width: 100%;
      }

    </style>
  </head>";

  $cellClass = 'style="display:table;background:#eee;padding:10px;margin 0 0 10px 0;"';
  $htmlMessage = str_replace('class="cell"', $cellClass, $emailMessage);

  $address = "<p class=\"small\" align=\"center\"><strong>" . CLUB_NAME . "</strong><br>";
  $club = json_decode(CLUB_JSON);
  for ($i = 0; $i < sizeof($club->ClubAddress); $i++) {
    $address .= $club->ClubAddress[$i] . "<br>";
  }
  $address .= "</p>";

  $message = "<body>
  <div style=\"background:#e3eef6;\">
    <table style=\"width:100%;border:0px;text-align:left;padding:10px 0px 10px 0px;background:#e3eef6;\"><tr><td align=\"center\">
      <table style=\"width:100%;max-width:700px;border:0px;text-align:center;background:#ffffff;padding:10px 10px 0px 10px;\"><tr><td>" . $image . "</td></tr></table>
      <table style=\"width:100%;max-width:700px;border:0px;text-align:left;background:#ffffff;padding:0px 10px;\"><tr><td>
      " . $htmlMessage . "
      </td></tr></table>
      <table style=\"width:100%;max-width:700px;border:0px;background:#f8fcff;padding:0px 10px;\"><tr><td>
      <div
class=\"bottom text-center\">";
$message .= $address;
$message .= "<p class=\"small\" align=\"center\">This email was sent automatically by the " . CLUB_NAME . " Membership System.</p>";
if (!defined('IS_CLS') || !IS_CLS) {
  $message .= '<p class="small" align="center">The Membership System was built by Chester-le-Street ASC.</p>';
}
$message .= "<p class=\"small\" align=\"center\">Have questions? Contact us at <a
href=\"mailto:" . $club->ClubEmails->Main . "\">" . $club->ClubEmails->Main . "</a>.</p>
<p class=\"small\" align=\"center\">To control your email options, go to <a href=\"" .
autoUrl("myaccount/email") . "\">My Account</a>.</p>";
if ($from['Unsub']['Allowed']) {
  $message .= '<p class="small" align="center"><a href="' . autoUrl("notify/unsubscribe/" . dechex($from['Unsub']['User']) . '/' . urlencode($emailaddress) . '/' . urlencode($from['Unsub']['List'])) . '">Click to Unsubscribe</a></p>';
}
$message .= "
<p class=\"small\" align=\"center\">&copy; " . CLUB_NAME . " " . date("Y") . "</p>
      </div>
      </table>
    </table>
    </div>
    </body>
    </html>";

  if ($from['PlainText']) {
    $message = $emailMessage;
  }

  if ($emailaddress != null && $name != null) {

    $email = new \SendGrid\Mail\Mail();
    $email->setReplyTo(CLUB_EMAIL, CLUB_NAME);
    $email->setFrom($from['Email'], $from['Name']);
    $email->setSubject($subject);
    $email->addTo($emailaddress, $name);
    $text_plain = str_replace("&pound;", "£", str_replace("&copy;", "©",
    strip_tags(str_replace(["</h1>", "</h2>", "</h3>", "</h4>", "</h5>",
    "</h6>", "</p>", "</li>"], "\r\n\n", $message))));
    $email->addContent("text/plain", $text_plain);
    if ($from['PlainText']) {
      $email->addContent(
        "text/html", $emailMessage . $address
      );
    } else {
      $email->addContent(
        "text/html", $head . $message
      );
    }

    if ($from['Email'] == "notify@" . EMAIL_DOMAIN || $from['Email'] == "payments@" . EMAIL_DOMAIN) {
      $email->addHeader("List-Archive", autoUrl("myaccount/notify/history"));
    }

    $email->addHeader("List-Help", autoUrl("notify"));

    if ($from['Unsub']['Allowed']) {
      //$email->addHeader("List-Unsubscribe", "<mailto:unsubscribe+" . dechex($from['Unsub']['User']) . "-" . urlencode($from['Unsub']['List']) . "-accounts@chesterlestreetasc.co.uk>, <" . autoUrl("notify/unsubscribe/" . dechex($from['Unsub']['User']) . '/' . urlencode($emailaddress) . '/' . urlencode($from['Unsub']['List'])) . ">");
    }

    if (IS_CLS === true) {
      if ($from['Email'] == "notify@" . EMAIL_DOMAIN) {
        $email->addHeader("List-ID", "CLS ASC Targeted Lists <targeted-lists@account." . EMAIL_DOMAIN . ">");
      } else if ($from['Email'] == "payments@" . EMAIL_DOMAIN) {
        $email->addHeader("List-ID", "Direct Debit Payment Information <payment-news@account." . EMAIL_DOMAIN . ">");
      } else if ($from['Name'] == CLUB_NAME . " Security") {
        $email->addHeader("List-ID", "Account Security Updates <account-updates@account." . EMAIL_DOMAIN . ">");
      }

      if ($from['Email'] == "payments@" . EMAIL_DOMAIN) {
        $email->setReplyTo("payments+replytoautoemail@chesterlestreetasc.co.uk", "Payments Team");
      } else if ($from['Email'] == "galas@" . EMAIL_DOMAIN) {
        $email->setReplyTo("galas+replytoautoemail@" . EMAIL_DOMAIN, "Gala Administrator");
      } else if ($from['Name'] == "Chester-le-Street ASC Security") {
        $email->setReplyTo("support+security-replytoautoemail@" . EMAIL_DOMAIN, CLUB_SHORT_NAME . " Support");
      } else {
        $email->setReplyTo("enquiries+replytoautoemail@" . EMAIL_DOMAIN, CLUB_SHORT_NAME . " Enquiries");
      }

      if ($from['Reply-To'] != null) {
        $email->setReplyTo($from['Reply-To']);
      }
    }

    if ($from['CC'] != null) {
      $email->addCcs($from['CC']);
    }

    if ($from['BCC'] != null) {
      $email->addBccs($from['BCC']);
    }

    $sendgrid = new \CLSASC\SuperMailer\SuperMailer(SENDGRID_API_KEY);
    try {
      $response = $sendgrid->send($email);
    } catch (Exception $e) {
      //echo $e;
      return false;
    }
    return true;

  } else {
    // Using PHP Mail is a last resort if stuff goes really wrong
    if (mail($to,$subject,$head . $message,$headers)) {
      return true;
    }

  }

  return false;
}

function getAttendanceByID($link, $id, $weeks = "all") {
  global $db;

  $output = "";
  $startWeek = 1;

  // Get the last four weeks to calculate attendance
  $latestWeek = $db->query("SELECT MAX(WeekID) FROM `sessionsWeek`;")->fetchColumn();

  if ($weeks != "all") {
    $startWeek = $latestWeek - $weeks;
    if ($startWeek < 1) {
      $startWeek = 1;
    }
  }

  $member = [
    "week" => $startWeek,
    "member" => $id
  ];

  $numPresent = $db->prepare("SELECT COUNT(*) FROM `sessionsAttendance` WHERE WeekID >= :week && MemberID = :member && AttendanceBoolean = 1");
  $numPresent->execute($member);
  $numPresent = $numPresent->fetchColumn();
  $totalNum = $db->prepare("SELECT COUNT(*) FROM `sessionsAttendance` WHERE WeekID >= :week && MemberID = :member");
  $totalNum->execute($member);
  $totalNum = $totalNum->fetchColumn();

  if ($totalNum == 0) {
    return "No Data 0";
  }

  return number_format(($numPresent/$totalNum)*100, 1, ".", "");
}

function mySwimmersTable($link, $userID) {
  // Get the last four weeks to calculate attendance
  $sql = "SELECT `WeekID` FROM `sessionsWeek` ORDER BY `WeekDateBeginning` DESC
  LIMIT 4;";
  $resultWeeks = mysqli_query($link, $sql);
  $weekCount = mysqli_num_rows($resultWeeks);
    if ($weekCount > 0) {
    $sqlWeeks = "";
    // Produce stuff for query
    for ($y=0; $y<$weekCount; $y++) {
      $attRow = mysqli_fetch_array($resultWeeks, MYSQLI_ASSOC);
      $weekID[$y] = $attRow['WeekID'];
      if ($y < ($weekCount-1)) {
        $sqlWeeks .= "`WeekID` = '$weekID[$y]' OR ";
      }
      else {
        $sqlWeeks .= "`WeekID` = '$weekID[$y]'";
      }
    }
  }

  // Get the information about the swimmer
  $sqlSwim = "SELECT members.MemberID, members.MForename, members.MSurname,
  members.ClubPays, users.Forename, users.Surname, users.EmailAddress,
  members.ASANumber, squads.SquadName, squads.SquadFee FROM ((members INNER JOIN
  users ON members.UserID = users.UserID) INNER JOIN squads ON members.SquadID =
  squads.SquadID) WHERE members.UserID = '$userID';";
  $result = mysqli_query($link, $sqlSwim);
  $swimmerCount = mysqli_num_rows($result);
  $output = "";
  if ($swimmerCount > 0) {
    $output = '
    <div class="table-responsive">
      <table class="table table-hover">
        <thead class="thead-light">
          <tr>
            <th>Name</th>
            <th>Squad</th>
            <th>Fee</th>
            <th>ASA Number</th>
            <th><abbr title="Approximate attendance over the last 4
            weeks">Attendance</abbr></th>
          </tr>
        </thead>
        <tbody>';
    $resultX = mysqli_query($link, $sqlSwim);
    for ($i = 0; $i < $swimmerCount; $i++) {
      $swimmersRowX = mysqli_fetch_array($resultX, MYSQLI_ASSOC);
      $swimmerLink = autoUrl("swimmers/" . $swimmersRowX['MemberID'] . "");
      $output .= "<tr>
        <td><a href=\"" . $swimmerLink . "\">" . $swimmersRowX['MForename'] . " " .
        $swimmersRowX['MSurname'] . "</a></td>
        <td>" . $swimmersRowX['SquadName'] . "</td>";
        if ($swimmersRowX['ClubPays'] == 0) {
          $output .= "<td>&pound;" . $swimmersRowX['SquadFee'] . "</td>";
        } else {
          $output .= "<td>&pound;0.00 - Exempt</td>";
        }
        $output .= "
        <td><a
        href=\"https://www.swimmingresults.org/biogs/biogs_details.php?tiref=" .
        $swimmersRowX['ASANumber'] . "\" target=\"_blank\" title=\"ASA
        Biographical Data\">" . $swimmersRowX['ASANumber'] . " <i class=\"fa
        fa-external-link\" aria-hidden=\"true\"></i></a></td>";

        // Get member ID for finding attendance
        $id = $swimmersRowX['MemberID'];

        $output .= "
        <td>" . getAttendanceByID($link, $id, 4) . "%</td>
      </tr>";
    }
    $output .= '
        </tbody>
      </table>
    </div>';
  }
  return $output;
}

function mySwimmersMedia($link, $userID) {
  $sqlSwim = "SELECT members.MemberID, members.MForename, members.MSurname,
  members.ClubPays, users.Forename, users.Surname, users.EmailAddress,
  members.ASANumber, squads.SquadName, squads.SquadFee FROM ((members INNER JOIN
  users ON members.UserID = users.UserID) INNER JOIN squads ON members.SquadID =
  squads.SquadID) WHERE members.UserID = '$userID';";
  $result = mysqli_query($link, $sqlSwim);
  $swimmerCount = mysqli_num_rows($result);
  $swimmerS = $swimmers = '';
  if ($swimmerCount == 0 || $swimmerCount > 1) {
    $swimmerS = 'swimmers';
  }
  else {
    $swimmerS = 'swimmer';
  }
  $swimmers = '<p class="lead border-bottom border-gray pb-2 mb-0">You have ' .
  $swimmerCount . ' ' . $swimmerS . '</p>';
  if ($swimmerCount == 0) {
    $swimmers .= '<p><a href="' . autoUrl("myaccount/addswimmer") . '"
    class="btn btn-outline-dark">Add a Swimmer</a></p>';
  }
  $output = "";
  if ($swimmerCount > 0) {
    $output = '
    <div class="">
    <h2>My Swimmers</h2>' . $swimmers;
    $resultX = mysqli_query($link, $sqlSwim);
    for ($i = 0; $i < $swimmerCount; $i++) {
      $swimmersRowX = mysqli_fetch_array($resultX, MYSQLI_ASSOC);
      $swimmerLink = autoUrl("swimmers/" . $swimmersRowX['MemberID'] . "");
      $output .= "<div class=\"media text-muted pt-3\"><p class=\"media-body
      pb-3 mb-0 lh-125 border-bottom border-gray\"><strong class=\"d-block
      text-gray-dark\"><a href=\"" . $swimmerLink . "\">" .
      $swimmersRowX['MForename'] . " " . $swimmersRowX['MSurname'] .
      "</a></strong>
        " . $swimmersRowX['SquadName'] . " Squad, ";
        if ($swimmersRowX['ClubPays'] == 0) {
          $output .= $swimmersRowX['SquadFee'];
        } else {
          $output .= "&pound;0.00 <em>(Exempt)</em>";
        }
        $output .= ", " . getAttendanceByID($link,
        $swimmersRowX['MemberID'], 4) . "% <abbr title=\"Attendance over the
        last four weeks\">Attendance</abbr>
    </div>";
    }
    $output .= '
    <span class="d-block text-right mt-3">
          <a href="' . autoUrl('swimmers') . '">Go to My Swimmers</a>
        </span></div>';
  }
  else {
    $output .= '
    <div class="">
    <h2>My Swimmers</h2>
    <p class="mb-0">It looks like you have no swimmers connected to your
    account. Why don\'t you <a href="' . autoUrl("myaccount/addswimmer") . '"
    >add one now</a>?</p>
    </div>';
  }
  return $output;
}

function generateRandomString($length) {
  $characters =
  '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

function courseLengthString($string) {
  $courseLength;
  if ($string == "SHORT") {
    $courseLength = "Short Course";
  }
  else if ($string == "LONG") {
    $courseLength = "Long Course";
  }
  else {
    $courseLength = "Non Standard Pool Distance";
  }
  return $courseLength;
}

function upcomingGalas($link, $links = false, $userID = null) {
  $sql = "SELECT * FROM `galas` WHERE `galas`.`ClosingDate` >= CURDATE() ORDER BY `galas`.`ClosingDate` ASC;";
  $result = mysqli_query($link, $sql);
  $count = mysqli_num_rows($result);
  if ($count > 0) {
    $output= "<div class=\"media\">";
    for ($i = 0; $i < $count; $i++) {
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
      $output .= " <ul class=\"media-body pt-2 pb-2 mb-0 lh-125 ";
      if ($i != $count-1) {
        if (app('request')->ajax) {
          $output .= "border-bottom border-white";
        } else {
          $output .= "border-bottom border-gray";
        }
      }
      $output .= " list-unstyled\"> <li><strong class=\"d-block
      text-gray-dark\">";
      if ($links == true) {
        $output .= $row['GalaName'] . " (" .
        courseLengthString($row['CourseLength']) . ") <a href=\"" .
        autoUrl("galas/competitions/" . $row['GalaID'] . "") . "\"><span
        class=\"small\">Edit Gala and View Statistics</span></a></li>";         } else {
        $output .= "" . $row['GalaName'] . " (" .
        courseLengthString($row['CourseLength']) . ")</li>";
      }
      $output .= "</strong></li>";
      $output .= "<li>" . $row['GalaVenue'] . "<br>";
      $output .= "<li>Closing Date " . date('jS F Y',
      strtotime($row['ClosingDate'])) . "</li>";
      if ($userID == null) {
        $output .= "<li>Finishes on " . date('jS F Y',
        strtotime($row['GalaDate'])) . "</li>";
      }
      if ($row['GalaFee'] > 0) {
        $output .= "<li>Entry Fee of &pound;" .
        number_format($row['GalaFee'],2,'.','') . "/Swim</li>";
      }
      else {
        $output .= "<li>Entry fee varies by event</li>";
      }
      $output .= "</ul>";
    }
    $output .= "</div>";
  }
  else {
    $output .= "<p class=\"lead mb-0 mt-2\">There are no galas available to enter</p>";
  }
  return $output;
}

function upcomingGalasBySearch($link, $searchSQL = null) {
  $sql = "";
  if ($searchSQL != null) {
    $sql = "SELECT * FROM (((`galaEntries` INNER JOIN `galas` ON
    galaEntries.GalaID = galas.GalaID) INNER JOIN `members` ON
    galaEntries.MemberID = members.MemberID) INNER JOIN `squads` ON
    members.SquadID = squads.SquadID) WHERE " . $searchSQL . " ORDER BY
    `members`.`MForename`, `members`.`MSurname` ASC;";
    $result = mysqli_query($link, $sql);
    $count = mysqli_num_rows($result);
    $swimsArray = ['50Free', '100Free', '200Free', '400Free', '800Free',
    '1500Free', '50Breast', '100Breast',  '200Breast', '50Fly', '100Fly',
    '200Fly', '50Back', '100Back', '200Back', '100IM', '150IM', '200IM',
    '400IM'];
    $swimsTextArray = ['50 Free','100 Free','200 Free','400 Free','800
    Free','1500 Free','50 Breast','100 Breast','200 Breast','50 Fly','100
    Fly','200 Fly','50 Back','100 Back','200 Back','100 IM','150 IM','200
    IM','400 IM',];
    if ($count > 0) {
      $output = "<p>" . $count . " entries for the selected competition(s)</p>";
      $output .= "<div class=\"table-responsive\"><table class=\"table
      table-hover\"><thead><tr><th>Gala</th><th>Swimmer</th><th>Squad</th><th>Swims</th><th>Gala
      Fee</th></tr></thead><tbody>";
      for ($i = 0; $i < $count; $i++) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $galaDate = new DateTime($row['GalaDate']);
        $theDate = new DateTime('now');
        $galaDate = $galaDate->format('Y-m-d');
        $theDate = $theDate->format('Y-m-d');
        $counter = 0;
        if ($galaDate >= $theDate) {
          $output .= "<tr><td>" . $row['GalaName'] . "<br><small><a href=\"" .
          autoUrl("galas/entries/" . $row['EntryID'] . "") . "\">View
          Entry</a></small></td>";
          $output .= "<td>" . $row['MForename'] . " " . $row['MSurname'] .
          "</td>";
          $output .= "<td>" . $row['SquadName'] . "</td>";
          $output .= "<td><ul class=\"list-unstyled\">";
          for ($j=0; $j<sizeof($swimsArray); $j++) {
            if ($row[$swimsArray[$j]] == 1) {
              $output .= "<li>" . $swimsTextArray[$j] . "</li>";
              $counter++;
            }
          }
          $output .= "</ul></td>";
          if ($row['GalaFeeConstant'] == 1) {
            $output .= "<td>&pound;" .
            number_format($row['GalaFee']*$counter,2,'.','') . "
            Total</td></tr>";
          }
          else {
            $output .= "<td>Fee Unavailable</td></tr>";
          }
        }
      }
      $output .= "</tbody></table></div>";
    }
    else {
      $output = "<p class=\"lead\">There are no entries to display</p>";
    }
  }
  else {
    $output = "<p class=\"lead\">Search for entries to display results</p>";
  }
  return $output;
}

function enteredGalas($link, $userID) {
  $sql = "SELECT * FROM ((galaEntries INNER JOIN members ON galaEntries.MemberID =
  members.MemberID) INNER JOIN galas ON galaEntries.GalaID = galas.GalaID) WHERE
  `UserID` = '$userID' ORDER BY `galas`.`GalaDate` DESC;";
  $result = mysqli_query($link, $sql);
  $count = mysqli_num_rows($result);
  if ($count > 0) {
    $output = "<div class=\"media pt-0\">";
    for ($i = 0; $i < $count; $i++) {
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
      $endDate = new DateTime($row['GalaDate']);
      $theDate = new DateTime('now');
      $endDate = $endDate->format('Y-m-d');
      $theDate = $theDate->format('Y-m-d');
      if ($endDate >= $theDate) {
        $output .= "<ul class=\"media-body pt-2 pb-2 mb-0 lh-125 border-bottom
        border-gray list-unstyled\"> <li><strong><a href=\"" .
        autoUrl("galas/entries/" . $row['EntryID'] . "") . "\">" .
        $row['MForename'] . " " . $row['MSurname'] . "</a></strong></li>";
        $output .= "<li>" . $row['GalaName'] . " (" .
        courseLengthString($row['CourseLength']) . ")</li>";
        $output .= "<li>" . $row['GalaVenue'] . "</li>";
        $output .= "<li>Closing Date is " . date('j F Y',
        strtotime($row['ClosingDate'])) . "</li>";
        if ($row['GalaFee'] > 0) {
          $output .= "<li>Entry Fee &pound;" .
          number_format($row['GalaFee'],2,'.','') . "/Swim</li>";
        }
        else {
          $output .= "<li>Entry fee varies by event</li>";
        }
        $output .= "</ul>";
      }
    }
    $output .= "</div>";
  }
  else {
    $output = "<p class=\"lead\">There are no upcoming galas that you have
    entered</p>";
  }
  return $output;
}

function enteredGalasMedia($link, $userID) {
  $sql = "SELECT * FROM ((galaEntries INNER JOIN members ON galaEntries.MemberID =
  members.MemberID) INNER JOIN galas ON galaEntries.GalaID = galas.GalaID) WHERE
  `UserID` = '$userID' ORDER BY `galas`.`GalaDate` DESC, `galas`.`ClosingDate`
  ASC LIMIT 3;";
  $result = mysqli_query($link, $sql);
  $count = mysqli_num_rows($result);
  if ($count > 0) {
    $output = "<div class=\"\">
    <h2 class=\"border-bottom border-gray pb-2 mb-0\">My Entries</h2>";
    for ($i = 0; $i < $count; $i++) {
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
      $endDate = new DateTime($row['GalaDate']);
      $theDate = new DateTime('now');
      $endDate = $endDate->format('Y-m-d');
      $theDate = $theDate->format('Y-m-d');
      if ($endDate >= $theDate) {
        $output .= "<div class=\"media text-muted pt-3\"><p class=\"media-body
        pb-3 mb-0 lh-125 border-bottom border-gray\"><strong class=\"d-block
        text-gray-dark\"><a href=\"" . autoUrl("galas/entries/" .
        $row['EntryID'] . "") . "\">" . $row['MForename'] . " " .
        $row['MSurname'] . "</a></strong> " . $row['GalaName'] . " </div>";
      }
    }
    if ($count > 3) {
      $output .= '
      <span class="d-block text-right mt-3">
        <a href="' . autoUrl('galas') . '">View all</a>
      </span></div>';
    }
    else {
      $output .= '
      <span class="d-block text-right mt-3">
        No more galas to show
      </span></div>';
    }
  }
  else {
    $output = "<div class=\"\">
    <h2>My Entries</h2><p class=\"mb-0\">There are no upcoming galas that you
    have entered.</p></div>";
  }
  return $output;
}


function closedGalas($link, $links = false) {
  $sql = "SELECT * FROM `galas` ORDER BY `galas`.`GalaDate` DESC LIMIT 0, 15;";
  $result = mysqli_query($link, $sql);
  $count = mysqli_num_rows($result);
  if ($count > 0) {
    $output = "<div class=\"table-responsive\"><table class=\"table
    table-hover\"><thead><tr><th>Gala
    Name</th><th>Course</th><th>Venue</th><th>Closing Date</th><th>Last day of
    Gala</th><th>Gala Fee</th></tr></thead><tbody>";
    for ($i = 0; $i < $count; $i++) {
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
      $endDate = new DateTime($row['GalaDate']);
      $entryDate = new DateTime($row['ClosingDate']);
      $theDate = new DateTime('now');
      $endDate = $endDate->format('Y-m-d');
      $entryDate = $entryDate->format('Y-m-d');
      $theDate = $theDate->format('Y-m-d');
      if ($endDate >= $theDate && $theDate > $entryDate) {
        if ($links == true) {
          $output .= "<tr><td>" . $row['GalaName'] . "<br><small><a href=\"" .
          autoUrl("galas/competitions/" . $row['GalaID'] . "") . "\">Edit
          Gala</a></small></td>";
        }
        else {
          $output .= "<tr><td>" . $row['GalaName'] . "</td>";
        }
        $output .= "<td>" . $row['CourseLength'] . "</td>";
        $output .= "<td>" . $row['GalaVenue'] . "</td>";
        $output .= "<td>" . date('j F Y', strtotime($row['ClosingDate'])) .
        "</td>";
        $output .= "<td>" . date('j F Y', strtotime($row['GalaDate'])) .
        "</td>";
        if ($row['GalaFee'] > 0) {
          $output .= "<td>&pound;" . number_format($row['GalaFee'],2,'.','') .
          "/Swim</td></tr>";
        }
        else {
          $output .= "<td>Variable by Swim</td></tr>";
        }
      }
    }
    $output .= "</tbody></table></div>";
  }
  else {
    $output = "<p class=\"lead\">There are no upcoming galas with closed
    entries</p>";
  }
  return $output;
}

function myMonthlyFeeTable($link, $userID) {
  $sql = "SELECT squads.SquadName, squads.SquadID, squads.SquadFee,
  members.MForename, members.MSurname FROM (members INNER JOIN squads ON
  members.SquadID = squads.SquadID) WHERE members.UserID = '$userID' AND
  members.ClubPays = '0' ORDER BY `squads`.`SquadFee` DESC;";
  $result = mysqli_query($link, $sql);
  $count = mysqli_num_rows($result);
  $totalsArray = [];
  $squadsOutput = "";
  $totalCost = 0;
  $reducedCost = 0;
  for ($i = 0; $i < $count; $i++) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $totalsArray[$i] = $row['SquadFee'];
    $totalCost += $totalsArray[$i];
    $squadsOutput .= "<tr><td>" . $row['SquadName'] . " Squad <br>for " .
    $row['MForename'] . " " . $row['MSurname'] . "</td><td>&pound;" .
    number_format($row['SquadFee'],2,'.','') . "</td></tr>";
  }
  for ($i = 0; $i < $count; $i++) {
    if ($i == 2) {
      $totalsArray[$i] = $totalsArray[$i]*0.8;
    }
    elseif ($i > 2) {
      $totalsArray[$i] = $totalsArray[$i]*0.6;
    }
    $reducedCost += $totalsArray[$i];
  }
  $sql = "SELECT extras.ExtraName, extras.ExtraFee, members.MForename ,
  members.MSurname FROM ((extras INNER JOIN extrasRelations ON extras.ExtraID =
  extrasRelations.ExtraID) INNER JOIN members ON members.MemberID =
  extrasRelations.MemberID) WHERE extrasRelations.UserID = '$userID' ORDER BY
  `extras`.`ExtraFee` DESC;";
  $result = mysqli_query($link, $sql);
  $count = mysqli_num_rows($result);
  $monthlyExtras = "";
  $monthlyExtrasTotal = 0;
  for ($i=0; $i<$count; $i++) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $monthlyExtras .= "<tr><td>" . $row['ExtraName'] . " <br>for " .
    $row['MForename'] . " " . $row['MSurname'] . "</td><td>&pound;" .
    number_format($row['ExtraFee'],2,'.','') . "</td></tr>";
    $monthlyExtrasTotal += $row['ExtraFee'];
  }
  if ($monthlyExtrasTotal+$reducedCost > 0) {
    $output = "<div class=\"table-responsive\"><table class=\"table mb-0\">
    <thead class=\"thead-light\">
      <tr>
        <th>Fee Information</th>
        <th>Price</th>
      </tr>
    </thead>
    <tbody>
    <tr><td>The monthly subtotal for Squad Fees is</td><td>&pound;" .
    number_format($totalCost,2,'.','') . "</td></tr>";
    if (($totalCost - $reducedCost) > 0) {
      $output .= "<tr><td>The monthly total payable for squads with discounts is</td><td>&pound;" . number_format($reducedCost,2,'.','') .
      "</td></tr>";
    }
    $output .= "<tr><td>The monthly subtotal for extras is</td><td>&pound;" . number_format($monthlyExtrasTotal,2,'.','') .
    "</td></tr> <tr class=\"bg-light\"><td><strong>The monthly total
    is</strong></td><td>&pound;" . number_format(($reducedCost +
    $monthlyExtrasTotal),2,'.','') . "</td></tr> </tbody></table></div>";
    return $output;
  }
  else {
    return "<p class=\"mb-0\">You have no monthly fees to pay. You may need to
    add a swimmer to your account to see your fees.</p>";
  }
}

function myMonthlyFeeMedia($link, $userID) {
  $sql = "SELECT squads.SquadName, squads.SquadID, squads.SquadFee,
  members.MForename, members.MSurname FROM (members INNER JOIN squads ON
  members.SquadID = squads.SquadID) WHERE members.UserID = '$userID' AND
  members.ClubPays = '0' ORDER BY `squads`.`SquadFee` DESC;";
  $result = mysqli_query($link, $sql);
  $count = mysqli_num_rows($result);
  $totalsArray = [];
  $squadsOutput = "";
  $totalCost = 0;
  $reducedCost = 0;
  for ($i = 0; $i < $count; $i++) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $totalsArray[$i] = $row['SquadFee'];
    $totalCost += $totalsArray[$i];
    $squadsOutput .= "<tr><td>" . $row['SquadName'] . " Squad <br>for " .
    $row['MForename'] . " " . $row['MSurname'] . "</td><td>&pound;" .
    number_format($row['SquadFee'],2,'.','') . "</td></tr>";
  }
  for ($i = 0; $i < $count; $i++) {
    if ($i == 2) {
      $totalsArray[$i] = $totalsArray[$i]*0.8;
    }
    elseif ($i > 2) {
      $totalsArray[$i] = $totalsArray[$i]*0.6;
    }
    $reducedCost += $totalsArray[$i];
  }
  $monthlyExtrasTotal = monthlyExtraCost($link, $userID, "decimal");
  if ($monthlyExtrasTotal+$reducedCost > 0) {
    $output = "<div class=\"\">
    <h2>My Fees</h2><p class=\"lead border-bottom border-gray pb-2
    mb-0\">Showing monthly fees</p>
    <div class=\"table-responsive\"><table class=\"table mb-0\">
    <tbody>
    <tr><td>The monthly subtotal for Squad Fees is</td><td>&pound;" .
    number_format($totalCost,2,'.','') . "</td></tr>";
    if (($totalCost - $reducedCost) > 0) {
      $output .= "<tr><td>The monthly total payable for squads (with any
      deductions) is</td><td>&pound;" . number_format($reducedCost,2,'.','') .
      "</td></tr>";
    }
    $output .= "<tr><td>The monthly total for extras, such as CrossFit,
    is</td><td>&pound;" . number_format($monthlyExtrasTotal,2,'.','') .
    "</td></tr>
    <tr class=\"bg-light\"><td><strong>The monthly total
    is</strong></td><td>&pound;" . number_format(($reducedCost +
    $monthlyExtrasTotal),2,'.','') . "</td></tr>
    </tbody></table></div>
    </div>";
    return $output;
  }
  else {
    return "<div class=\"\">
    <h2>My Fees</h2>
    <p class=\"mb-0\">You have no monthly fees to pay. You may need to add a
    swimmer to see any fees.</p>
    </div>";
  }
}

function adminSwimmersTable($link, $squadID = null) {
  if ($squadID != null) {
    $sqlSwim = "SELECT members.MemberID, members.MForename, members.MSurname,
    members.ASANumber, squads.SquadName, members.DateOfBirth FROM (members INNER
    JOIN squads ON members.SquadID = squads.SquadID) WHERE members.SquadID =
    '$squadID' ORDER BY `members`.`MForename` , `members`.`MSurname` ASC ;";
  }
  else {
    $sqlSwim = "SELECT members.MemberID, members.MForename, members.MSurname,
    members.ASANumber, squads.SquadName, members.DateOfBirth FROM (members INNER
    JOIN squads ON members.SquadID = squads.SquadID) ORDER BY
    `members`.`MForename` , `members`.`MSurname` ASC;";
  }
  $result = mysqli_query($link, $sqlSwim);
  $swimmerCount = mysqli_num_rows($result);
  if ($swimmerCount > 0) {
    $output = '
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Name</th>
            <th>Squad</th>
            <th>Date of Birth</th>
            <th>Age</th>
            <th><abbr title="Age at end of year">AEoY</abbr></th>
            <th>ASA Number</th>
          </tr>
        </thead>
        <tbody>';
    $resultX = mysqli_query($link, $sqlSwim);
    for ($i = 0; $i < $swimmerCount; $i++) {
      $swimmersRowX = mysqli_fetch_array($resultX, MYSQLI_ASSOC);
      $swimmerLink = autoUrl("swimmers/" . $swimmersRowX['MemberID'] . "");
      $DOB = date('j F Y', strtotime($swimmersRowX['DateOfBirth']));
      $age = date_diff(date_create($swimmersRowX['DateOfBirth']),
      date_create('today'))->y;
      $ageEoY = date('Y') - date('Y', strtotime($swimmersRowX['DateOfBirth']));
      $output .= "<tr>
        <td><a href=\"" . $swimmerLink . "\">" . $swimmersRowX['MForename'] . " " .
        $swimmersRowX['MSurname'] . "</a></td>
        <td>" . $swimmersRowX['SquadName'] . "</td>
        <td>" . $DOB . "</td>
        <td>" . $age . "</td>
        <td>" . $ageEoY . "</td>
        <td><a
        href=\"https://www.swimmingresults.org/biogs/biogs_details.php?tiref=" .
        $swimmersRowX['ASANumber'] . "\" target=\"_blank\" title=\"ASA
        Biographical Data\">" . $swimmersRowX['ASANumber'] . " <i class=\"fa
        fa-external-link\" aria-hidden=\"true\"></i></a></td>
      </tr>";
    }
    $output .= '
        </tbody>
      </table>
    </div>';
  }
  else {
    $output = "<div class=\"alert alert-warning\"><strong>No members found for
    that squad</strong> <br>Please try another search</div>";
  }
  return $output;
}

function squadInfoTable($link, $enableLinks = false) {
  $sql = "SELECT squads.SquadID, squads.SquadName, squads.SquadFee,
  squads.SquadCoach, squads.SquadTimetable, squads.SquadCoC FROM squads ORDER BY
  `squads`.`SquadFee` DESC;";
  $result = mysqli_query($link, $sql);
  $count = mysqli_num_rows($result);
  $output = "";
  if ($count > 0) {
    $output = '
    <div class="table-responsive">
      <table class="table table-striped">
        <thead class="thead-light">
          <tr>
            <th>Name</th>
            <th>Fee</th>
            <th>Coach</th>
            <th>Timetable</th>
            <th>Code of Conduct</th>
          </tr>
        </thead>
        <tbody>';
    $result = mysqli_query($link, $sql);
    for ($i = 0; $i < $count; $i++) {
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
      $output .= "<tr>";
      if ($enableLinks) {
        $output .= "<td><a href=\"" . autoUrl("squads/" . $row['SquadID']) . "\">" .
        $row['SquadName'] . "</a></td>";
      }
      else {
        $output .= "<td>" . $row['SquadName'] . "</td>";
      }
        $output .= "<td>&pound;" . $row['SquadFee'] . "</td>
        <td>" . $row['SquadCoach'] . "</td>
        <td>";
        if ($row['SquadTimetable'] != "") {
          $output .= "<a href=\"" . $row['SquadTimetable'] . "\"
          target=\"_blank\">Timetable</a>";
        }
          $output .= "</td>
        <td>";
        if ($row['SquadCoC'] != "") {
          $output .= "<a href=\"" . autoUrl("pages/codeofconduct/" . $row['SquadCoC']) . "\"
          target=\"_blank\">Code of Conduct</a>";
        }
        $output .= "</td>
      </tr>";
    }
    $output .= '
        </tbody>
      </table>
    </div>';
  }
  return $output;
}

function creditWallet($id, $amount, $description) {
  // Get the balance
  $sql = "SELECT `Balance` FROM `wallet` WHERE UserID = '$id';";
  $result = mysqli_query(LINK, $sql);
  $row = mysqli_fetch_array($result);
  $balance = $row['Balance'];

  // The new balance
  $newBalance = $balance + $amount;

  // Update the balance andd insert description
  $sql = "
  INSERT INTO walletHistory (Amount, Balance, TransactionDesc, UserID) VALUES
  ('$amount', '$newBalance', '$description', '$id'); UPDATE wallet SET
  Balance='$newBalance' WHERE UserID = '$id';";
  mysqli_query(LINK, $sql);
}

function debitWallet($id, $amount, $description) {
  // Get the balance
  $sql = "SELECT `Balance` FROM `wallet` WHERE UserID = '$id';";
  $result = mysqli_query(LINK, $sql);
  $row = mysqli_fetch_array($result);
  $balance = $row['Balance'];

  // The new balance
  $newBalance = $balance - $amount;
  $amount = 0 - $amount;

  // Update the balance andd insert description
  $sql = "INSERT INTO walletHistory (Amount, Balance, TransactionDesc, UserID)
  VALUES ('$amount', '$newBalance', '$description', '$id');";
  mysqli_query(LINK, $sql);
  $sql = "UPDATE wallet SET Balance='$newBalance' WHERE UserID = '$id';";
  mysqli_query(LINK, $sql);
  echo $newBalance;
}


function autoUrl($relative) {
  // Returns an absolute URL
  return ROOT_URL . $relative;
}

function monthlyFeeCost($link, $userID, $format = "decimal") {
  global $db;
  $query = $db->prepare("SELECT squads.SquadName, squads.SquadID, squads.SquadFee FROM (members
  INNER JOIN squads ON members.SquadID = squads.SquadID) WHERE members.UserID =
  ? AND `ClubPays` = '0' ORDER BY `squads`.`SquadFee` DESC");
  $query->execute([$userID]);

  $totalCost = 0;
  $reducedCost = 0;

  $i = 0;
  while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $squadCost = $row['SquadFee'];
    if (defined("IS_CLS") && IS_CLS) {
      if ($i < 2) {
        $totalCost += $squadCost;
        $reducedCost += $squadCost;
      }
      else if ($i == 2) {
        $totalCost += $squadCost*0.8;
        $reducedCost += $squadCost*0.8;
      }
      else {
        $totalCost += $squadCost*0.6;
        $reducedCost += $squadCost*0.6;
      }
    } else {
      $totalCost += $squadCost;
      $reducedCost += $squadCost;
    }
    $i++;
  }

  $format = strtolower($format);
  if ($format == "decimal") {
    return $reducedCost;
  }
  else if ($format == "int") {
    return ((int) ($reducedCost*100));
  }
  else if ($format == "string") {
    return "&pound;" . number_format($reducedCost,2,'.','');
  }
}

function monthlyExtraCost($link, $userID, $format = "decimal") {
  global $db;
  $query = $db->prepare("SELECT extras.ExtraName, extras.ExtraFee FROM ((members
  INNER JOIN `extrasRelations` ON members.MemberID = extrasRelations.MemberID)
  INNER JOIN `extras` ON extras.ExtraID = extrasRelations.ExtraID) WHERE
  members.UserID = ?");
  $query->execute([$userID]);
  $totalCost = 0;

  while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $totalCost += $row['ExtraFee'];
  }

  $format = strtolower($format);
  if ($format == "decimal") {
    return $totalCost;
  }
  else if ($format == "int") {
    return ((int) ($totalCost*100));
  }
  else if ($format == "string") {
    return "&pound;" . number_format($totalCost,2,'.','');
  }
}

function swimmers($link, $userID, $fees = false) {
  $sql = "SELECT squads.SquadName, squads.SquadFee, members.MForename,
  members.MSurname, members.ClubPays FROM (members INNER JOIN squads ON
  members.SquadID = squads.SquadID) WHERE members.UserID = '$userID' ORDER BY
  `squads`.`SquadFee` DESC;";
  $result = mysqli_query($link, $sql);
  $count = mysqli_num_rows($result);
  $content = "";
  if ($count > 0) {
    $content .= "<ul class=\"mb-0 list-unstyled\">";

    for ($i = 0; $i < $count; $i++) {
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

      $content .= "<li>" . $row['MForename'] . " " . $row['MSurname'];
      if ($fees) {
        $content .= ", " . $row['SquadName'] . " - &pound;";
        if ($row['ClubPays'] == 0) {
          $content .= number_format($row['SquadFee'],2,'.','');
        } else {
          $content .= "0.00 <em>(Exempt)</em>";
        }
      }
      $content .= "</li>";
    }

    $content .= "</ul>";
  } else {
    $content = '<span class="text-muted small">No swimmers on this
    account</span>';
  }

  return $content;

}

function paymentHistory($link, $user, $type = null) {
  $sql = "SELECT * FROM `payments` WHERE `UserID` = '$user' ORDER BY `PaymentID`
  DESC LIMIT 0, 5;";
  $paymentResult = mysqli_query($link, $sql);
  $count = mysqli_num_rows($paymentResult);
  if ($count > 0) { ?>
        <?php for ($i = 0; $i < $count; $i++) {
        $row = mysqli_fetch_array($paymentResult, MYSQLI_ASSOC);
        if ($type == null) {
          $statementUrl = autoUrl("payments/statement/" . $row['PMkey']);
        } else if ($type == "admin") {
          $statementUrl = autoUrl("payments/history/statement/" . $row['PMkey']);
        }?>
        <div class="media pt-2">
          <?php if ($i != $count-1) { ?>
          <div class="media-body pb-2 mb-0 border-bottom border-gray">
          <?php } else { ?>
          <div class="media-body pb-0 mb-0">
          <?php } ?>
            <p class="mb-0">
              <strong>
                <a href="<?php echo $statementUrl; ?>" title="Transaction Statement">
                  <?php echo $row['Name']; ?>
                </a>
              </strong>
            </p>
            <p class="mb-0">
              <?php echo date('j F Y', strtotime($row['Date'])); ?>
            </p>
            <p class="mb-0">
              &pound;<?php echo number_format(($row['Amount']/100),2,'.',''); ?>
            </p>
          <p class="mb-0">
            Status: <?php echo paymentStatusString($row['Status']); ?>
          </p>
        </div>
      </div>
      <?php } ?>
  <?php } else { ?>
  <div class="alert alert-warning mb-0">
    <strong>You have no previous payments</strong> <br>
    Payments and Refunds will appear here once they have been requested from
    your bank.
  </div>
  <?php }
}

function feesToPay($link, $user) {
  $user = mysqli_real_escape_string($link, $user);
  $sql = "SELECT * FROM `paymentsPending` WHERE `UserID` = '$user' AND `PMkey`
  IS NULL AND `Status` = 'Pending' ORDER BY `Date` DESC LIMIT 0, 30;";
  $pendingResult = mysqli_query($link, $sql);
  $count = mysqli_num_rows($pendingResult);
  if ($count > 0) { ?>
    <?php for ($i = 0; $i < $count; $i++) {
    $row = mysqli_fetch_array($pendingResult, MYSQLI_ASSOC);	?>
    <div class="media pt-2">
      <?php if ($i != $count-1) { ?>
      <div class="media-body pb-2 mb-0 border-bottom border-gray">
      <?php } else { ?>
      <div class="media-body pb-0 mb-0">
      <?php } ?>
        <p class="mb-0">
          <strong>
            <?php echo $row['Name']; ?>
          </strong>
        </p>
        <p class="mb-0">
          <?php echo date('j F Y', strtotime($row['Date'])); ?>
        </p>
        <p class="mb-0">
          <?php if ($row['Type'] == 'Payment') { ?>
          &pound;<?php echo number_format(($row['Amount']/100),2,'.',''); ?>
          <?php } else { ?>
          -&pound;<?php echo number_format(($row['Amount']/100),2,'.',''); ?> (Credit)
          <?php } ?>
        </p>
      </div>
    </div>
    <?php } ?>
  <?php } else { ?>
  <div class="alert alert-warning mb-0">
    <strong>You have no current fees</strong> <br>
    Fee will appear here when they have been added to your account and have not
    been requested from the bank
  </div>
  <?php }
}

function getBillingDate($link, $user) {
  $sql = "SELECT * FROM `paymentSchedule` WHERE `UserID` = '$user';";
  if (mysqli_num_rows(mysqli_query($link, $sql)) > 0) {
    $row = mysqli_fetch_array(mysqli_query($link, $sql), MYSQLI_ASSOC);
    $ordinal = null;
    if ($row['Day']%10 == 1) {
      $ordinal = "st";
    }
    else if ($row['Day']%10 == 2) {
      $ordinal = "nd";
    }
    else {
      $ordinal = "rd";
    }
    return $row['Day'] . $ordinal;
  } else {
    return "1st";
  }
}

function userHasMandates($user) {
  global $link;
  $user = mysqli_real_escape_string($link, $user);
  $sql = "SELECT * FROM `paymentPreferredMandate` WHERE `UserID` = '$user';";
  if (mysqli_num_rows(mysqli_query($link, $sql)) == 1) {
    return true;
  }
  return false;
}

function paymentExists($payment) {
  global $link;
  $payment = mysqli_real_escape_string($link, $payment);
  $sql = "SELECT * FROM `payments` WHERE `PMkey` = '$payment';";
  $count = mysqli_num_rows(mysqli_query($link, $sql));
  if ($count == 1) {
    return true;
  } else {
    return false;
  }
}

function mandateExists($mandate) {
  global $link;
  $mandate = mysqli_real_escape_string($link, $mandate);
  $sql = "SELECT * FROM `paymentMandates` WHERE `Mandate` = '$mandate';";
  $count = mysqli_num_rows(mysqli_query($link, $sql));
  if ($count == 1) {
    return true;
  } else {
    return false;
  }
}

function updatePaymentStatus($PMkey) {
  global $link;
  require BASE_PATH . 'controllers/payments/GoCardlessSetup.php';
  $sql2bool = null;
  $PMkey_unescaped = $PMkey;
  $PMkey = mysqli_real_escape_string($link, $PMkey);
  $payment = $client->payments()->get($PMkey);
  $status = mysqli_real_escape_string($link, $payment->status);
  $sql = "UPDATE `payments` SET `Status` = '$status' WHERE `PMkey` = '$PMkey';";

  // Test failure condition
  // $status = "failed";
  if ($status == "paid_out") {
    $sql2 = "UPDATE `paymentsPending` SET `Status` = 'Paid' WHERE `PMkey` =
    '$PMkey';";
    $sql2bool = mysqli_query($link, $sql2);
  } else if ($status == "failed") {
    global $db;
    try {
      $query = $db->prepare("SELECT payments.UserID, Name, Amount, Forename, Surname FROM payments INNER JOIN users ON payments.UserID = users.UserID WHERE PMkey = ?");
      $query->execute([$PMkey]);
      $details = $query->fetch(PDO::FETCH_ASSOC);

      $new_day = date("Y-m-d", strtotime("+10 days"));

      $query = $db->prepare("SELECT COUNT(*) FROM paymentRetries WHERE PMKey = ?");
      $query->execute([$PMkey]);
      $num_retries = $query->fetchColumn();

      $subject = "Payment Failed for " . $details['Name'];
      $message = '
      <p>Your Direct Debit payment of £' . number_format($details['Amount']/100, 2, '.', '') . ', ' . $details['Name'] . ' has failed.</p>';
      if ($num_retries < 3) {
        $message .= '<p>We will automatically retry this payment on ' . date("j F Y", strtotime("+10 days")) . ' (in ten days time).</p>';
        if ($num_retries < 2) {
          $message .= '<p>You don\'t need to take any action. Should this payment fail, we will retry the payment up to ' . (2-$num_retries) . ' times.</p>';
        } else if ($num_retries == 2) {
          $message .= '<p>You don\'t need to take any action. Should this payment fail, you will need to contact the club treasurer as we will have retried this direct debit payment 3 times.</p>';
        }
      } else {
        $message .= '<p>We have retried this payment request three times and it has still not succeeded. As a result, you will need to contact the club treasurer to take further action. Failure to pay may lead to the suspension or termination of your membership.</p>';
      }

      $message .= '<p>Kind regards,<br>The ' . CLUB_NAME . ' Team</p>';
      $query = $db->prepare("INSERT INTO notify (UserID, Status, Subject, Message, ForceSend, EmailType) VALUES (?, ?, ?, ?, ?, ?)");
      $query->execute([$details['UserID'], 'Queued', $subject, $message, 1, 'Payments']);

      if ($num_retries < 3) {
        $query = $db->prepare("INSERT INTO paymentRetries (UserID, Day, PMKey, Tried) VALUES (?, ?, ?, ?)");
        $query->execute([$details['UserID'], $new_day, $PMkey, false]);
      }

      $sql2bool = true;
    } catch (Exception $e) {
      $sql2bool = false;
      echo "Failure in event process";
    }
  } else if ($status == "customer_approval_denied") {
    global $db;
    try {
      $query = $db->prepare("SELECT payments.UserID, Name, Amount, Forename, Surname FROM payments INNER JOIN users ON payments.UserID = users.UserID WHERE PMkey = ?");
      $query->execute([$PMkey]);
      $details = $query->fetch(PDO::FETCH_ASSOC);

      $subject = "Payment Failed for " . $details['Name'];
      $message = '
      <p>Your Direct Debit payment of £' . number_format($details['Amount']/100, 2, '.', '') . ', ' . $details['Name'] . ' has failed because customer approval was denied. This means your bank requires two people two authorise a direct debit mandate on your account and that this authorisation has not been given. You will be contacted by the treasurer to arrange payment.</p>
      <p>Kind regards,<br>The ' . CLUB_NAME . ' Team</p>';
      $query = $db->prepare("INSERT INTO notify (UserID, Status, Subject, Message, ForceSend, EmailType) VALUES (?, ?, ?, ?, ?, ?)");
      $query->execute([$details['UserID'], 'Queued', $subject, $message, 1, 'Payments']);

      $sql2bool = true;
    } catch (Exception $e) {
      $sql2bool = false;
    }
  } else if ($status == "charged_back") {
    global $db;
    try {
      $query = $db->prepare("SELECT payments.UserID, Name, Amount, Forename, Surname FROM payments INNER JOIN users ON payments.UserID = users.UserID WHERE PMkey = ?");
      $query->execute([$PMkey]);
      $details = $query->fetch(PDO::FETCH_ASSOC);

      $subject = $details['Name'] . " Charged Back";
      $message = '
      <p>Your Direct Debit payment of £' . number_format($details['Amount']/100, 2, '.', '') . ', ' . $details['Name'] . ' has been charged back to us. You will be contacted by the treasurer to arrange payment of any outstanding amount.</p>
      <p>Please note that fraudulently charging back a Direct Debit payment is a criminal offence, covered by the 2006 Fraud Act. We recommend that if your are unsure about the amount we are charging you, you should try and contact us first.</p>
      <p>Kind regards,<br>The ' . CLUB_NAME . ' Team</p>';
      $query = $db->prepare("INSERT INTO notify (UserID, Status, Subject, Message, ForceSend, EmailType) VALUES (?, ?, ?, ?, ?, ?)");
      $query->execute([$details['UserID'], 'Queued', $subject, $message, 1, 'Payments']);

      $sql2bool = true;
    } catch (Exception $e) {
      $sql2bool = false;
    }
  } else {
    $sql2bool = true;
  }
  if (mysqli_query($link, $sql) && $sql2bool) {
    return true;
  } else {
    return false;
  }
}

function paymentStatusString($status) {
  switch ($status) {
    case "paid_out":
      return "Paid to " . CLUB_SHORT_NAME;
    case "paid_manually":
      return "Paid Manually";
    case "pending_customer_approval":
      return "Waiting for the customer to approve this payment";
    case "pending_submission":
      return "Payment has been created, but not yet submitted to the bank";
    case "submitted":
      return "Payment has been submitted to the bank";
    case "confirmed":
      return "Payment has been confirmed as collected";
    case "cancelled":
      return "Payment cancelled";
    case "customer_approval_denied":
      return "The customer has denied approval for the payment.
      You should contact the customer directly";
    case "failed":
      return "The payment failed to be processed";
    case "charged_back":
      return "The payment has been charged back";
      case "cust_not_dd":
        return "The customer does not have a Direct Debit set up";
    default:
      return "Unknown Status Code";
  }
}

function bankDetails($user, $detail) {
  global $link;
  $user = mysqli_real_escape_string($link, $user);
  $sql = "SELECT * FROM `paymentPreferredMandate`
  INNER JOIN `paymentMandates` ON
  paymentPreferredMandate.MandateID = paymentMandates.mandateID
  WHERE paymentPreferredMandate.UserID = '$user';";
  $result = mysqli_query($link, $sql);
  if (mysqli_num_rows($result) != 1) {
    return "Unknown";
  }

  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

  switch ($detail) {
    case "bank_name":
      return $row['BankName'];
    case "account_holder":
      return $row['AccountHolderName'];
    case "account_number_end":
      return $row['AccountNumEnd'];
    case "mandate":
      return $row['Mandate'];
    case "bank_account":
      return $row['BankAccount'];
    case "customer":
      return $row['Customer'];
    default:
      return "Unknown";
  }
}

function getUserName($user) {
  global $link;
  $user = mysqli_real_escape_string($link, $user);
  $sql = "SELECT `Forename`, `Surname` FROM `users` WHERE `UserID` = '$user';";
  $result = mysqli_query($link, $sql);
  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $row['Forename'] . " " . $row['Surname'];
  }
  return false;
}

function getSwimmerName($swimmer) {
  global $link;
  $swimmer = mysqli_real_escape_string($link, $swimmer);
  $sql = "SELECT `MForename`, `MSurname` FROM `members` WHERE `MemberID` =
  '$swimmer';";
  $result = mysqli_query($link, $sql);
  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $row['MForename'] . " " . $row['MSurname'];
  }
  return false;
}

function setupPhotoPermissions($id) {
  global $link;
  $id = mysqli_real_escape_string($link, $id);
  $sql = "SELECT * FROM `memberPhotography` WHERE `MemberID` = '$id';";
  if (mysqli_num_rows(mysqli_query($link, $sql)) == 0) {
    $sql = "INSERT INTO `memberPhotography` (`MemberID`, `Website`, `Social`,
    `Noticeboard`, `FilmTraining`, `ProPhoto`) VALUES ('$id', '0', '0', '0',
    '0', '0');";
    if (mysqli_query($link, $sql)) {
      return true;
    }
  }
  return false;
}

function setupMedicalInfo($id) {
  global $link;
  $id = mysqli_real_escape_string($link, $id);
  $sql = "SELECT * FROM `memberMedical` WHERE `MemberID` = '$id';";
  if (mysqli_num_rows(mysqli_query($link, $sql)) == 0) {
    $sql = "INSERT INTO `memberMedical` (`MemberID`, `Conditions`, `Allergies`,
    `Medication`) VALUES ('$id', '', '', '');";
    if (mysqli_query($link, $sql)) {
      return true;
    }
  }
  return false;
}

function ordinal($num) {
  $ordinal = null;
  if ($num%10 == 1) {
    $ordinal = "st";
  }
  else if ($num%10 == 2) {
    $ordinal = "nd";
  }
  else if ($num%10 == 3) {
    $ordinal = "rd";
  }
  else {
    $ordinal = "th";
  }
  return $num . $ordinal;
}

use Symfony\Component\DomCrawler\Crawler;

function curl($url) {
  $ch = curl_init();  // Initialising cURL
  curl_setopt($ch, CURLOPT_URL, $url);    // Setting cURL's URL option with the $url variable passed into the function
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Setting cURL's option to return the webpage data
  $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
  curl_close($ch);    // Closing cURL
  return $data;   // Returning the data from the function
}

function curl_scrape_between($data, $start, $end) {
  //echo $data . "<br>";
  $data = stristr($data, $start); // Stripping all data from before $start
  //echo $data . "<br>";
  $data = substr($data, strlen($start));  // Stripping $start
  //echo $data . "<br>";
  $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
  //echo $stop . "<br>";
  $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
  //echo $data . "<br>";
  return $data;   // Returning the scraped data from the function
}

function getTimes($asa) {
  global $link;
  $curlres =
  curl('https://www.swimmingresults.org/biogs/biogs_details.php?tiref=' .
  $asa);

  $start = '<table width="100%" style="page-break-before:always">';
  $end = '</table>';

  $output = curl_scrape_between($curlres, $start, $end);
  $output = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $output);
  $output = preg_replace('/(<[^>]+) width=".*?"/i', '$1', $output);

  $crawler = new Crawler($output);
  $crawler = $crawler->filter('tr > td');

  $array = ['Event', 'CY_SC', 'SCPB', 'CY_LC', 'LCPB'];
  $count = 0;

  foreach ($crawler as $domElement) {
    $col = $count%5;
    if ($col == 0) {
      if ($domElement->textContent == "") {
        $array['Event'][] = null;
      } else {
        $array['Event'][] = mysqli_real_escape_string($link, trim($domElement->textContent));
      }
    } else if ($col == 1) {
      if ($domElement->textContent == "") {
        $array['CY_SC'][] = null;
      } else {
        $array['CY_SC'][] = mysqli_real_escape_string($link, trim($domElement->textContent));
      }
    } else if ($col == 2) {
      if ($domElement->textContent == "") {
        $array['SCPB'][] = null;
      } else {
        $array['SCPB'][] = mysqli_real_escape_string($link, trim($domElement->textContent));
      }
    } else if ($col == 3) {
      if ($domElement->textContent == "") {
        $array['CY_LC'][] = null;
      } else {
        $array['CY_LC'][] = mysqli_real_escape_string($link, trim($domElement->textContent));
      }
    } else if ($col == 4) {
      if ($domElement->textContent == "") {
        $array['LCPB'][] = null;
      } else {
        $array['LCPB'][] = mysqli_real_escape_string($link, trim($domElement->textContent));
      }
    }
    $count++;
  }

  return $array;
}

function getTimesInFull($asa, $swim, $course) {
  global $link;

  $swimsArray = [
    '50Free' => '1',
    '100Free' => '2',
    '200Free' => '3',
    '400Free' => '4',
    '800Free' => '5',
    '1500Free' => '6',
    '50Breast' => '7',
    '100Breast' => '8',
    '200Breast' => '9',
    '50Fly' => '10',
    '100Fly' => '11',
    '200Fly' => '12',
    '50Back' => '13',
    '100Back' => '14',
    '200Back' => '15',
    '100IM' => '18',
    '200IM' => '16',
    '400IM' => '17'
  ];

  $courseArray = [
    'Long' => 'L',
    'LongCourse' => 'L',
    'LC' => 'L',
    'Short' => 'S',
    'ShortCourse' => 'S',
    'SC' => 'S'
  ];

  $swim = $swimsArray[$swim];
  $course = $courseArray[$course];


  $curlres =
  curl('https://www.swimmingresults.org/individualbest/personal_best_time_date.php?back=biogs&tiref=' .
  $asa . '&mode=A&tstroke=' . $swim . '&tcourse=' . $course);

  $start = '<p class="rnk_sj">Swims in Date Order</p><table id="rankTable"><tbody>';
  $end = '</tbody></table>';

  $output = curl_scrape_between($curlres, $start, $end);
  $output = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $output);
  $output = preg_replace('/(<[^>]+) width=".*?"/i', '$1', $output);

  $crawler = new Crawler($output);
  $crawler = $crawler->filter('tr > td');

  $array = ['Time', 'FINA', 'Round', 'Date', 'Meet', 'Venue', 'Club', 'Level'];
  $count = 0;

  foreach ($crawler as $domElement) {
    $col = $count%8;
    if ($col == 0) {
      if ($domElement->textContent == "") {
        $array['Time'][] = null;
      } else {
        $array['Time'][] = mysqli_real_escape_string($link, trim($domElement->textContent));
      }
    } else if ($col == 1) {
      if ($domElement->textContent == "") {
        $array['FINA'][] = null;
      } else {
        $array['FINA'][] = mysqli_real_escape_string($link, trim($domElement->textContent));
      }
    } else if ($col == 2) {
      if ($domElement->textContent == "") {
        $array['ROUND'][] = 'H';
      } else {
        $array['ROUND'][] = mysqli_real_escape_string($link, trim($domElement->textContent));
      }
    } else if ($col == 3) {
      if ($domElement->textContent == "") {
        $array['Date'][] = null;
      } else {
        $date = date_parse_from_format("d/m/y", $domElement->textContent);
        $date = $date['year'] . "-" . sprintf('%02d', $date['month']) . "-" .
        sprintf('%02d', $date['day']);
        $array['Date'][] = mysqli_real_escape_string($link, trim($date));
      }
    } else if ($col == 4) {
      if ($domElement->textContent == "") {
        $array['Meet'][] = null;
      } else {
        $array['Meet'][] = mysqli_real_escape_string($link, trim($domElement->textContent));
      }
    } else if ($col == 5) {
      if ($domElement->textContent == "") {
        $array['Venue'][] = null;
      } else {
        $array['Venue'][] = mysqli_real_escape_string($link, trim($domElement->textContent));
      }
    } else if ($col == 4) {
      if ($domElement->textContent == "") {
        $array['License'][] = null;
      } else {
        $array['License'][] = mysqli_real_escape_string($link, trim($domElement->textContent));
      }
    } else if ($col == 4) {
      if ($domElement->textContent == "") {
        $array['Level'][] = null;
      } else {
        $array['Level'][] = mysqli_real_escape_string($link, trim($domElement->textContent));
      }
    }
    $count++;
  }

  return $array;

  //return $crawler;
}

function user_needs_registration($user) {
  /*
  global $db;
  $sql = "SELECT `RR`, `AccessLevel` FROM `users` WHERE `UserID` = ?";
  try {
  	$query = $db->prepare($sql);
  	$query->execute([$user]);
  } catch (PDOException $e) {
  	halt(500);
  }

  $row = $query->fetch(PDO::FETCH_ASSOC);

  if (!$row) {
    return false;
  } else if ($row['AccessLevel'] != "Parent" || ($row['AccessLevel'] == "Parent" && $row['RR'] == 0)) {
    return false;
  } else {
    return true;
  }
  */
  return false;
}

function getPostContent($id) {
  global $db;
  $sql = "SELECT `Content` FROM `posts` WHERE `ID` = ?";
  try {
  	$query = $db->prepare($sql);
  	$query->execute([$id]);
  } catch (PDOException $e) {
  	halt(500);
  }

  $row = $query->fetch(PDO::FETCH_ASSOC);
  if (!$row) {
    return false;
  }

  $markdown = new ParsedownExtra();
  // Safe mode is disabled during the transition to markdown
  // $markdown->setSafeMode(true);

  return $markdown->text($row['Content']);
}

function isSubscribed($user, $email_type) {
  global $db;
  $sql = "SELECT `Subscribed` FROM `users` LEFT JOIN `notifyOptions` ON `users`.`UserID` = `notifyOptions`.`UserID` WHERE (`users`.`UserID` = :user AND `EmailType` = :type) OR (`users`.`UserID` = :user AND `EmailType` IS NULL)";
  $array = [
    'user' => $user,
    'type' => $email_type
  ];
  try {
    $query = $db->prepare($sql);
    $query->execute($array);
  } catch (Exception $e) {
    halt(500);
  }
  $row = $query->fetchColumn();

  if ($row == null) {
    // Try user account info
    $sql = "SELECT `EmailComms` FROM `users` WHERE `UserID` = :user";
    $array = [
      'user' => $user
    ];
    try {
      $query = $db->prepare($sql);
      $query->execute($array);
    } catch (Exception $e) {
      halt(500);
    }
    $row = $query->fetchColumn();
    if ($row) {
      return true;
    }
  }

  if ($row) {
    return true;
  }

  return false;
}

function updateSubscription($post, $list, $user = null) {
	global $db;
  if (isset($_SESSION['UserID'])) {
    $user = $_SESSION['UserID'];
  }
	$email = 0;
	$email_update = false;
	if ($post) {
		$email = 1;
	}

	if ($email != isSubscribed($user, $list)) {
		$email_update = true;
		$_SESSION['OptionsUpdate'] = true;
	}

	$sql = "SELECT COUNT(*) FROM `notifyOptions` WHERE `UserID` = ? AND `EmailType` = ?";
	try {
		$query = $db->prepare($sql);
		$query->execute([$user, $list]);
	} catch (Exception $e) {
		halt(500);
	}
	if ($query->fetchColumn() == 0) {
		// INSERT
		$sql = "INSERT INTO `notifyOptions` (`UserID`, `EmailType`, `Subscribed`) VALUES (?, ?, ?)";
		try {
			$query = $db->prepare($sql);
			$query->execute([$user, $list, $email]);
		} catch (Exception $e) {
			halt(500);
		}
	} else {
		// UPDATE
		$sql = "UPDATE `notifyOptions` SET `Subscribed` = ? WHERE `UserID` = ? AND `EmailType` = ?";
		try {
			$query = $db->prepare($sql);
			$query->execute([$email, $user, $list]);
		} catch (Exception $e) {
			halt(500);
		}
	}
}

function getUserOption($userID, $option) {
  global $db;
  $query = $db->prepare("SELECT Value FROM userOptions WHERE User = ? AND Option = ?");
  $query->execute([$userID, $option]);
  $result = $query->fetchColumn();

  if ($result == null || $result == "") {
    return null;
  }
  return $result;
}

function setUserOption($userID, $option, $value) {
  try {
    global $db;
    $query = $db->prepare("SELECT COUNT(*) FROM userOptions WHERE User = ? AND Option = ?");
    $query->execute([$userID, $option]);
    $result = $query->fetchColumn();

    if ($result == 0) {
      $query = $db->prepare("INSERT INTO userOptions (User, Option, Value) VALUES (?, ?, ?)");
      $query->execute([$userID, $option, $value]);
    } else {
      $query = $db->prepare("UPDATE userOptions SET Value = ? WHERE User = ? AND Option = ?");
      $query->execute([$value, $userID, $option]);
    }
  } catch (Exception $e) {
    return false;
  }
  return true;
}

$count = 0;

/*
if ( (empty($_SESSION['LoggedIn']) || empty($_SESSION['Username'])) && ($preventLoginRedirect != true)) {
  // Allow access to main page
  header("Location: " . autoUrl("login.php"));
}
elseif (((!empty($_SESSION['LoggedIn'])) || (!empty($_SESSION['Username']))) && ($preventLoginRedirect == true)) {
  // Don't show login etc if logged in
  header("Location: " . autoUrl(""));
}
*/
