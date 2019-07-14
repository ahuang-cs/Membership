<?php

global $db;
$galaDetails = $db->prepare("SELECT GalaName `name`, GalaDate `ends`, CoachEnters FROM galas WHERE GalaID = ?");
$galaDetails->execute([$id]);
$gala = $galaDetails->fetch(PDO::FETCH_ASSOC);

if ($gala == null) {
  halt(404);
}

$squads = $db->query("SELECT SquadName `name`, SquadID `id` FROM squads ORDER BY SquadFee DESC, SquadName ASC;");

$galaDate = new DateTime($gala['ends'], new DateTimeZone('Europe/London'));
$nowDate = new DateTime('now', new DateTimeZone('Europe/London'));

$getSessions = $db->prepare("SELECT `Name`, `ID` FROM galaSessions WHERE Gala = ? ORDER BY `ID` ASC");
$getSessions->execute([$id]);
$session = $getSessions->fetch(PDO::FETCH_ASSOC);

$pagetitle = 'Invite parents to enter ' . htmlspecialchars($gala['name']);

include BASE_PATH . 'views/header.php';

$squadString = "";
$squadIDs = [];
while ($squad = $squads->fetch(PDO::FETCH_ASSOC)) {
  if (isset($_POST['squad-' . $squad['id']]) && $_POST['squad-' . $squad['id']]) {
    if ($squads != "") {
      $squadString .= " AND ";
    }
    $squadString .= " members.SquadID = ? ";
    $squadIDs[] = $squad['id'];
  }
}

$date = new DateTime('-' . (int) $_POST['min-age'] . ' years', new DateTimeZone('Europe/London'));

$squadIDs[] = $date->format("Y-m-d");

$db->prepare("SELECT UserID, MForename, MSurname, Forename, Surname FROM members INNER JOIN users ON users.UserID = members.MemberID WHERE " . $squadString . " AND members.DateOfBirth >= ?");
$db->execute($squadIDs);