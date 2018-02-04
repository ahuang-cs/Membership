<?php
include_once "../database.php";
$access = $_SESSION['AccessLevel'];
$swimmerCount = 0;

if ($access == "Committee" || $access == "Admin" || $access == "Coach") {
  if (isset($_REQUEST["squadID"])) {
    // get the squadID parameter from URL
    $squadID = mysqli_real_escape_string($link, $_REQUEST["squadID"]);

    $response = "";

    if ($squadID != null) {
      $sql = "SELECT * FROM (sessions INNER JOIN squads ON sessions.SquadID = squads.SquadID) WHERE squads.SquadID = '$squadID';";
      $result = mysqli_query($link, $sql);
      $swimmerCount = mysqli_num_rows($result);
      $content = '<option>Choose the session from the menu</option>';
      for ($i=0; $i<$swimmerCount; $i++) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $content .= "<option value=\"" . $row['SessionID'] . "\">" . $row['SessionDay'] . " " . $row['SessionName'] . "</option>";
      }
    }

    if ($swimmerCount > 0) {
      echo $content;
    }
    else {
      echo "<option selected value=\"0\">Couldn't find anything</option>";
    }
  }

  if (isset($_REQUEST["sessionID"])) {
      $date = "4";
    if (isset($_REQUEST["date"])) {
      $date = $_REQUEST["date"];
    }

    $sessionID = mysqli_real_escape_string($link, $_REQUEST["sessionID"]);
    $response = $content = $modalOutput = "";

    if ($sessionID != null) {
      $sql = "SELECT `WeekDateBeginning` FROM `sessionsWeek` WHERE `WeekID` = '$date';";
      $result = mysqli_query($link, $sql);
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
      $date = strtotime($row['WeekDateBeginning']);
      $dayAdd = 0;
      switch ($row['SessionDay']) {
          case "Sunday":
              $dayAdd = 0;
              break;
          case "Monday":
              $dayAdd = 1;
              break;
          case "Tuesday":
              $dayAdd = 2;
              break;
          case "Wednesday":
              $dayAdd = 3;
              break;
          case "Thursday":
              $dayAdd = 4;
              break;
          case "Friday":
              $dayAdd = 5;
              break;
          case "Saturday":
              $dayAdd = 6;
              break;
      }
      $date = strtotime($date. ' + ' . $dayAdd . ' days');
      $sql = "SELECT * FROM (sessions INNER JOIN squads ON sessions.SquadID = squads.SquadID) WHERE sessions.SessionID = '$sessionID';";
      $result = mysqli_query($link, $sql);
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

      $content .= "<h2>Take register</h2><p>for " . $row['SquadName'] . " Squad, " . $row['SessionDay'] . " " . $row['SessionName'] . "</p><p>" . $date . "</p>";
      $sql = "SELECT * FROM ((sessions INNER JOIN members ON sessions.SquadID = members.SquadID) INNER JOIN squads ON sessions.SquadID = squads.SquadID) WHERE sessions.SessionID = '$sessionID' ORDER BY members.MForename, members.MSurname ASC";
      $result = mysqli_query($link, $sql);
      $swimmerCount = mysqli_num_rows($result);
      $content .= "<div class=\"table-responsive\"><table class=\"table table-striped\"><thead><tr><th>Name</th><th>Notes</th></tr></thead><tbody>";
      for ($i=0; $i<$swimmerCount; $i++) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $content .= "
        <tr>
          <td>
            <div class=\"custom-control custom-checkbox\">
            <input type=\"checkbox\" class=\"custom-control-input\" name=\"Member-" . $row['MemberID'] . "\" value=\"1\" id=\"Member-" . $row['MemberID'] . "\">
            <label class=\"custom-control-label\" for=\"Member-" . $row['MemberID'] . "\">" . $row['MForename'] . " " . $row['MSurname'] . "</label>
            </div>
          </td>
          <td>";
          if ($row['MedicalNotes'] != "") {
            //ref=\"" . autoUrl("swimmers/" . $row['MemberID']) . "\" target=\"_blank\">
            $content .= "<a data-toggle=\"modal\" href=\"#medicalModal" . $row['MemberID'] . "\"><span class=\"badge badge-danger\">MEDICAL</span></a>";
            $modalOutput .= '
            <!-- Modal -->
            <div class="modal fade" id="medicalModal' . $row['MemberID'] . '" tabindex="-1" role="dialog" aria-labelledby="medicalModalTitle' . $row['MemberID'] . '" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="medicalModalTitle' . $row['MemberID'] . '">Medical Information for ' . $row['MForename'] . ' ' . $row['MSurname'] . '</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                  ' . $row['MedicalNotes'] . '
                  </div>
                </div>
              </div>
            </div>
            ';
          }
          if ($row['OtherNotes'] != "") {
            $content .= " <a data-toggle=\"modal\" href=\"#notesModal" . $row['MemberID'] . "\"><span class=\"badge badge-info\">OTHER</span></a>";
            $modalOutput .= '
            <!-- Modal -->
            <div class="modal fade" id="notesModal' . $row['MemberID'] . '" tabindex="-1" role="dialog" aria-labelledby="notesModalTitle' . $row['MemberID'] . '" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="notesModalTitle' . $row['MemberID'] . '">Other Notes for ' . $row['MForename'] . ' ' . $row['MSurname'] . '</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                  ' . $row['OtherNotes'] . '
                  </div>
                </div>
              </div>
            </div>
            ';
          }
          $content .= "
          </td>
        </tr>";
      }
      $content .= '</tbody></table></div><p class="mb-0"><button type="submit" class="btn btn-success">Save Register</button></p>';
    }

    if ($swimmerCount > 0) {
      echo $content;
      echo $modalOutput;
    }
    else {
      echo "<p class=\"lead\">No swimmers were found for this squad and session</p>";
    }
  }

}
?>
