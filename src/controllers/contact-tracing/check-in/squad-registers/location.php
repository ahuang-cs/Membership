<?php

use function GuzzleHttp\json_decode;

$db = app()->db;
$tenant = app()->tenant;

$getLocation = $db->prepare("SELECT `ID`, `Name`, `Address` FROM covidLocations WHERE `ID` = ? AND `Tenant` = ?");
$getLocation->execute([
  $id,
  $tenant->getId()
]);
$location = $getLocation->fetch(PDO::FETCH_ASSOC);

if (!$location) {
  halt(404);
}

$userSquads = $db->prepare("SELECT SquadName, SquadID FROM squadReps INNER JOIN squads ON squadReps.Squad = squads.SquadID WHERE User = ? AND Squad = ? ORDER BY SquadFee DESC, SquadName ASC");
$userSquads->execute([
  $_SESSION['TENANT-' . app()->tenant->getId()]['UserID'],
  $_GET['squad'],
]);

$squad = $userSquads->fetch(PDO::FETCH_ASSOC);

if (!$squad) {
  http_response_code(302);
  header("location: " . autoUrl('contact-tracing/check-in/' . $id));
} else {

  // Get Squad Members
  $getMembers = $db->prepare("SELECT MemberID, MForename, MSurname FROM members INNER JOIN squadMembers ON squadMembers.Member = members.MemberID WHERE squadMembers.Squad = ? AND members.Tenant = ? ORDER BY MForename ASC, MSurname ASC;");
  $getMembers->execute([
    $_GET['squad'],
    $tenant->getId(),
  ]);

  $pagetitle = htmlspecialchars($squad['SquadName']) . ' Squad Check In to ' . htmlspecialchars($location['Name']) . ' - Contact Tracing';

  $addr = json_decode($location['Address']);

  include BASE_PATH . 'views/header.php';

?>

  <div class="bg-light mt-n3 py-3 mb-3">
    <div class="container">

      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= htmlspecialchars(autoUrl('contact-tracing')) ?>">Tracing</a></li>
          <li class="breadcrumb-item"><a href="<?= htmlspecialchars(autoUrl('contact-tracing/locations')) ?>">Locations</a></li>
          <li class="breadcrumb-item active" aria-current="page">Check In</li>
        </ol>
      </nav>

      <div class="row align-items-center">
        <div class="col">
          <h1>
            <?= htmlspecialchars($squad['SquadName']) ?> Squad Check in to <?= htmlspecialchars($location['Name']) ?>
          </h1>
          <p class="lead mb-0">
            <?= htmlspecialchars($addr->streetAndNumber) ?>
          </p>
        </div>
      </div>
    </div>
  </div>

  <div class="container">

    <div class="row">
      <div class="col-lg-8">

        <form method="post">

          <?php if ($member = $getMembers->fetch(PDO::FETCH_ASSOC)) { ?>

            <p>
              Tick all members who are present.
            </p>

            <div class="mb-3">
              <?php do { ?>
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="<?= htmlspecialchars('member-' . $member['MemberID']) ?>" name="<?= htmlspecialchars('member-' . $member['MemberID']) ?>">
                  <label class="custom-control-label my-1" for="<?= htmlspecialchars('member-' . $member['MemberID']) ?>"><?= htmlspecialchars($member['MForename'] . ' ' . $member['MSurname']) ?></label>
                </div>
              <?php } while ($member = $getMembers->fetch(PDO::FETCH_ASSOC)); ?>
            </div>

          <?php } else { ?>
            <div class="alert alert-warning">
              <p class="mb-0">
                <strong>There are no members in this squad</strong>
              </p>
              <p class="mb-0">
                Please check with a member of club staff
              </p>
            </div>
          <?php } ?>

          <p>
            <button type="submit" class="btn btn-success">
              Check In
            </button>
          </p>
        </form>

      </div>
    </div>

  </div>

<?php

  $footer = new \SCDS\Footer();
  $footer->addJs('public/js/NeedsValidation.js');
  $footer->addJs('public/js/contact-tracing/check-in.js');
  $footer->render();
}
