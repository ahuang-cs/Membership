<?php

use Respect\Validation\Validator as v;

// Assign form content to SESSION
$_SESSION['RequestTrial-FC'] = $_POST;

if (!v::email()->validate($_POST['email-addr'])) {
  $_SESSION['RequestTrial-Errors']['Email'] = "The email address is invalid";
  header("Location: " . app('request')->curl);
}

$dob = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'];

if (!v::date()->validate($dob)) {
  $_SESSION['RequestTrial-Errors']['DOB'] = "The date of birth provided is not valid";
  header("Location: " . app('request')->curl);
}

if ($_POST['forename'] == "") {
  $_SESSION['RequestTrial-Errors']['Parent-FN'] = "No parent first name";
  header("Location: " . app('request')->curl);
}

if ($_POST['surname'] == "") {
  $_SESSION['RequestTrial-Errors']['Parent-LN'] = "No parent last name";
  header("Location: " . app('request')->curl);
}

if ($_POST['swimmer-forename'] == "") {
  $_SESSION['RequestTrial-Errors']['Swimmer-FN'] = "No swimmer first name";
  header("Location: " . app('request')->curl);
}

if ($_POST['swimmer-surname'] == "") {
  $_SESSION['RequestTrial-Errors']['Swimmer-LN'] = "No swimmer last name";
  header("Location: " . app('request')->curl);
}

if ($_POST['sex'] == "") {
  $_SESSION['RequestTrial-Errors']['Swimmer-LN'] = "No swimmer sex provided";
  header("Location: " . app('request')->curl);
}

if ($_POST['experience'] == "") {
  $_SESSION['RequestTrial-Errors']['Swimmer-Experience'] = "No experience option selected";
  header("Location: " . app('request')->curl);
}

/*
if (true) {
  $_SESSION['RequestTrial-Errors']['TESTING'] = "Testing system";
  header("Location: " . app('request')->curl);
}
*/

if (isset($_SESSION['RequestTrial-Errors'])) {
  die();
}

$parent = $_POST['forename'] . ' ' . $_POST['surname'];

$swimmer = $_POST['swimmer-forename'] . ' ' . $_POST['swimmer-surname'];
$asa = trim($_POST['swimmer-asa']);
$biog_link = 'https://www.swimmingresults.org/biogs/biogs_details.php?tiref=' . $asa;

$exp = "none";
if ($_POST['experience'] == 2) {
  $exp = "ducklings (pre stages)";
} else if ($_POST['experience'] == 3) {
  $exp = "school swimming lessons";
} else if ($_POST['experience'] == 4) {
  $exp = "ASA/Swim England Learn to Swim Stage 1-7";
} else if ($_POST['experience'] == 5) {
  $exp = "ASA/Swim England Learn to Swim Stage 8-10";
} else if ($_POST['experience'] == 6) {
  $exp = "swimming club";
}

$email_club = '
<p>' . $parent . ' has requested a trial for ' . $swimmer . '. To contact ' . $_POST['forename'] . ', email ' . $_POST['email-addr'] . ' or reply to this email. Here are the full request details.</p>';

$age = $age = date_diff(date_create($dob), date_create('today'))->y;

$email_club .= '
<h2>General Information</h2>
<p>' . $swimmer . '\'s date of birth is ' . date("j F Y", strtotime($dob)) . '. They are ' . $age . ' years old. They are ' . $_POST['sex'] . '</p>

<h2>Previous experience and achievements</h2>
<p>Their previous experience is ' . $exp . '.</p>';

if ($_POST['swimmer-xp'] != "") {
  $email_club .= '<p>' . $_POST['swimmer-xp'] . '</p>';
}

if ($_POST['swimmer-club'] != "" || $_POST['swimmer-asa'] != "") {
  $email_club .= '<h2>Previous Club(s)</h2><p>';
  if ($_POST['swimmer-club'] != "") {
    $email_club .= 'This swimmer swims or has swam at ' . $_POST['swimmer-club'];
  }
  if ($_POST['swimmer-club'] != "" && $_POST['swimmer-asa'] != "") {
    $email_club .= ' ';
  }
  if ($_POST['swimmer-asa'] != "") {
    $email_club .= 'Their ASA Number is ' . $asa . '. <a href="' . $biog_link . '">View their biog</a>.';
  }
  $email_club .= '</p>';
}

if ($_POST['swimmer-med'] != "") {
  $email_club .= '<h2>Medical Information</h2><p>' . $_POST['swimmer-med'] . '</p>';
}

if ($_POST['questions'] != "") {
  $email_club .= '<h2>Questions and Notes</h2><p>' . $_POST['questions'] . '</p>';
}

$email_club .= '<h2>What Next></h2>
<p>Before replying, please wait a moment to check that this parent isn\'t applying for any more trials. Once you are sure of that, contact them by email to arrange a trial date. Head to ' . autoUrl("trials") . ' continue the trial process.</p>';

$hash = sha1($_POST['email-addr']);

$email_parent = '
<p>Hello ' . $parent . '</p>
<p>Thank\'s for your interest in a trial for ' . $swimmer . ' at ' . CLUB_NAME . '. We\'re working through your request and will get back to you as soon as we can.</p>
<p>In the meantime, you may wish to <a href="' . CLUB_WEBSITE . '">visit our website</a>.</p>';

$to_club = notifySend(null, 'New Trial Request', $email_club, 'Club Admins', CLUB_TRIAL_EMAIL, ["Email" => "join@" . EMAIL_DOMAIN, "Name" => CLUB_NAME, 'Reply-To' => $_POST['email-addr']]);

$to_parent = notifySend(null, 'Your Trial Request', $email_parent, $parent, $_POST['email-addr']);

if ($to_club && $to_parent) {
  global $db;

  $query = $db->prepare("SELECT COUNT(*) FROM joinParents WHERE Hash = ?");
  $query->execute([$hash]);
  if ($query->fetchColumn() == 0) {
    $query = $db->prepare("INSERT INTO joinParents (Hash, First, Last, Email) VALUES (?, ?, ?, ?)");
    $query->execute([
      $hash,
      htmlspecialchars(trim($_POST['forename'])),
      htmlspecialchars(trim($_POST['surname'])),
      trim($_POST['email-addr'])
    ]);
  }

  $query = $db->prepare("INSERT INTO joinSwimmers (Parent, First, Last, DoB, XP, XPDetails, Medical, Questions, Club, ASA, Sex) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $query->execute([
    $hash,
    htmlspecialchars(trim($_POST['swimmer-forename'])),
    htmlspecialchars(trim($_POST['swimmer-surname'])),
    $dob,
    $_POST['experience'],
    trim($_POST['swimmer-xp']),
    trim($_POST['swimmer-med']),
    trim($_POST['questions']),
    trim($_POST['swimmer-club']),
    trim($_POST['swimmer-asa']),
    trim($_POST['sex'])
  ]);

  $_SESSION['RequestTrial-Success'] = true;
  $_SESSION['RequestTrial-AddAnother'] = [
    'forename' => $_POST['forename'],
    'surname' => $_POST['surname'],
    'email-addr' => $_POST['email-addr']
  ];
} else {
  $_SESSION['RequestTrial-Success'] = false;
}

header("Location: " . app('request')->curl);