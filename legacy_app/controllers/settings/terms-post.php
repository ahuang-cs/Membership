<?php

try {
  

  if (isset($_POST['TermsAndConditions'])) {
    nezamy_app()->tenant->setKey('TermsAndConditions', $_POST['TermsAndConditions']);
  }

  if (isset($_POST['PrivacyPolicy'])) {
    nezamy_app()->tenant->setKey('PrivacyPolicy', $_POST['PrivacyPolicy']);
  }

  if (isset($_POST['WelcomeLetter'])) {
    nezamy_app()->tenant->setKey('WelcomeLetter', $_POST['WelcomeLetter']);
  }

  $_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['PCC-SAVED'] = true;
} catch (Exception $e) {
  $_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['PCC-ERROR'] = true;
}

header("Location: " . autoUrl("settings/codes-of-conduct/terms-and-conditions"));