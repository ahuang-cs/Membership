<?php

global $db;

$access = $_SESSION['AccessLevel'];
$sql = "";
if (isset($_POST["search"])) {
  // get the search term parameter from post
  $search = $_POST["search"];
  $sql = "SELECT `Forename`, `Surname`, `MandateID`, `users`.`UserID` FROM users LEFT JOIN `paymentPreferredMandate` ON users.userID = paymentPreferredMandate.UserID WHERE Surname LIKE ? AND `AccessLevel` = 'Parent' ORDER BY Forename, Surname ASC;";
}

$getSearch = $db->prepare($sql);
$getSearch->execute([
  '%' . trim($_POST["search"]) . '%'
]);

$target = $_POST['target'];

if ($row = $getSearch->fetch(PDO::FETCH_ASSOC)) {
  $output = '
  <div class="table-responsive-md">
    <table class="table table-hover mb-0">
      <thead class="thead-light">
        <tr>
          <th>Name</th>
					<th>DD Status</th>
        </tr>
      </thead>
      <tbody>';
  do {
    $url = null;
    if ($target == "currentfees") {
      $url = autoUrl("payments/current/" . $row['UserID'] . "");
    } else if ($target == "transactionhistory") {
      $url = autoUrl("payments/history/users/" . $row['UserID'] . "");
    }
    $output .= "<tr>
      <td><a href=\"" . $url . "\">" . htmlspecialchars($row['Forename'] . " " . $row['Surname']) . "</a></td>";
			if ($row['MandateID'] == null || $row['MandateID'] == "") {
			$output .= "<td>No Direct Debit set up</td>";
		} else {
			$output .= "<td></td>";
		}
    $output .= "</tr>";
  } while ($row = $getSearch->fetch(PDO::FETCH_ASSOC));
  $output .= '
      </tbody>
    </table>
  </div>';
}
else {
  $output = "<div class=\"alert alert-warning mb-0\"><strong>No users found for that name</strong> <br>Please try another search</div>";
}
echo $output;
?>
