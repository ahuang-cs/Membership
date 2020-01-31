<?php

function memberUser($member) {
  global $db;

  // Is this parent also a swimmer member?
  $swimmerToo = $db->prepare("SELECT COUNT(*) FROM members INNER JOIN users ON members.ASANumber = users.ASANUmber WHERE MemberID = ?");
  $swimmerToo->execute([
    $member
  ]);
  $swimmerToo = $swimmerToo->fetchColumn();

  if ($swimmerToo > 0) {
    return true;
  }

  return false;
}

function userMember($member) {
  global $db;

  // Is this parent also a swimmer member?
  $swimmerToo = $db->prepare("SELECT COUNT(*) FROM members INNER JOIN users ON members.ASANumber = users.ASANUmber WHERE users.UserID = ?");
  $swimmerToo->execute([
    $member
  ]);
  $swimmerToo = $swimmerToo->fetchColumn();

  if ($swimmerToo > 0) {
    return true;
  }

  return false;
}