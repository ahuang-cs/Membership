<?php

use Respect\Validation\Validator as v;

if (v::email()->validate($_POST['reply'])) {
  setUserOption($_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['UserID'], 'NotifyReplyAddress', $_POST['reply']);
  $_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['SetReplySuccess'] = true;
} else {
  $_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['SetReplyFalse'] = true;
}

header("Location: " . autoUrl("notify/reply-to"));