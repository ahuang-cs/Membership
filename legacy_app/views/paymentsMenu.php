<?php if (!isset($renewal_trap) || !$renewal_trap) {
$access = $_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['AccessLevel']; ?>



<?php } else {
  include 'renewalTitleBar.php';
}
