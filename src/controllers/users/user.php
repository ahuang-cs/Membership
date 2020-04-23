<?php

$use_white_background = true;

use Brick\PhoneNumber\PhoneNumber;
use Brick\PhoneNumber\PhoneNumberParseException;
use Brick\PhoneNumber\PhoneNumberFormat;

$db = app()->db;

$userInfo = $db->prepare("SELECT Forename, Surname, EmailAddress, Mobile, ASANumber, ASAMember, ASAPrimary, ASACategory, ASAPaid, ClubMember, ClubPaid, ClubCategory, RR FROM users WHERE UserID = ? AND Active");
$userInfo->execute([$id]);

$qualifications;
try {
  $qualifications = $db->prepare("SELECT `Name`, Info, `From`, `To` FROM qualifications INNER JOIN qualificationsAvailable ON qualifications.Qualification = qualificationsAvailable.ID WHERE UserID = ? ORDER BY `Name` ASC");
  $qualifications->execute([$id]);
} catch (Exception $e) {
  // Do nothing just handle table not existing
}

$logins = $db->prepare("SELECT `Time`, `IPAddress`, Browser, `Platform`, `GeoLocation` FROM userLogins WHERE UserID = ? ORDER BY `Time` DESC LIMIT 1");
$logins->execute([$id]);
$loginInfo = $logins->fetch(PDO::FETCH_ASSOC);

$info = $userInfo->fetch(PDO::FETCH_ASSOC);

if ($info == null) {
  halt(404);
}

$userObj = new \User($id);

$par = $coa = $com = $gal = $adm = "";

$swimmers = null;
if ($userObj->hasPermission('Parent')) {
  $swimmers = $db->prepare("SELECT MemberID id, MForename fn, MSurname sn, SquadName squad, SquadFee fee, ClubPays exempt FROM members INNER JOIN squads ON members.SquadID = squads.SquadID WHERE members.UserID = ?");
  $swimmers->execute([$id]);
}

// Is this parent also a swimmer member?
$swimmerToo = $db->prepare("SELECT MemberID FROM members WHERE UserID = ? AND ASANumber = ?");
$swimmerToo->execute([
  $id,
  $info['ASANumber']
]);
$swimmerToo = $swimmerToo->fetchColumn();

$bankName = $bank = $has_logo = $logo_path = null;
if (userHasMandates($id)) {
  $bankName = mb_strtoupper(bankDetails($id, "account_holder_name"));
  if ($bankName != "UNKNOWN") {
    $bankName = $bankName . ', ';
  } else {
    $bankName = null;
  }
  $bank = mb_strtoupper(bankDetails($id, "bank_name"));
  $logo_path = getBankLogo($bank);
}

$json = $userObj->getUserOption('MAIN_ADDRESS');
$addr = null;
if ($json != null) {
  $addr = json_decode($json);
}

$number = null;
try {
  $number = PhoneNumber::parse($info['Mobile']);
}
catch (PhoneNumberParseException $e) {
  $number = false;
}

$accessLevel = "";
$perms = $userObj->getPrintPermissions();
foreach ($perms as $key => $value) {
  $accessLevel .= $value . ', ';
}

$pageHead = [
  'body' => [
    'data-spy="scroll"',
    'data-target="#member-page-menu"'
  ]
];

$fluidContainer = true;

$pagetitle = htmlspecialchars($info['Forename'] . ' ' . $info['Surname']) . " Information";
$title = null;
include BASE_PATH . "views/header.php";
?>
<div class="container-fluid">

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?=autoUrl("users")?>">Users</a></li>
      <li class="breadcrumb-item active" aria-current="page">
        <?=htmlspecialchars(mb_substr($info["Forename"], 0, 1, 'utf-8') . mb_substr($info["Surname"], 0, 1, 'utf-8'))?>
      </li>
    </ol>
  </nav>

  <?php if (isset($_SESSION['User-Update-Email-Error']) && $_SESSION['User-Update-Email-Error']) { ?>
  <div class="alert alert-danger">
    <strong>We were not able to update the user's email address because it was not valid</strong>
  </div>
  <?php unset($_SESSION['User-Update-Email-Error']); } ?>

  <?php if (isset($_SESSION['User-Update-Email-Success']) && $_SESSION['User-Update-Email-Success']) { ?>
  <div class="alert alert-success">
    <strong>We've updated the user's email address</strong>
  </div>
  <?php unset($_SESSION['User-Update-Email-Success']); } ?>

  <?php if (isset($_SESSION['NotifyIndivSuccess']) && $_SESSION['NotifyIndivSuccess']) { ?>
  <div class="alert alert-success">
    <strong>We've sent your email to <?=htmlspecialchars($info['Forename'])?></strong>
  </div>
  <?php unset($_SESSION['NotifyIndivSuccess']); } ?>

  <div class="row mb-3">
    <div class="col-sm-9 col-md-10 col-lg-11">
      <h1 class="mb-0">
        <?=htmlspecialchars($info['Forename'] . ' ' . $info['Surname'])?>
        <small><?=htmlspecialchars($accessLevel)?></small>
      </h1>
      <?php if ($_SESSION['AccessLevel'] == 'Admin') { ?>
      <div class="mb-3 d-md-none"></div>
      <?php } ?>
    </div>
    <?php if ($_SESSION['AccessLevel'] == 'Admin') { ?>
    <div class="col text-sm-right">
      <p class="mb-0">
        <a href="<?=htmlspecialchars(autoUrl("users/" . $id . "/edit"))?>" class="btn btn-success">
          Edit
        </a>
      </p>
    </div>
    <?php } ?>
  </div>

  <div class="row justify-content-between">
    <div class="col-md-4 col-lg-3 col-xl-3">
      <div class="position-sticky top-3 card mb-3">
        <div class="card-header">
          Jump to
        </div>
        <div class="list-group list-group-flush" id="member-page-menu">
          <a href="#basic-information" class="list-group-item list-group-item-action">
            Basic information
          </a>
          <?php if ($userObj->hasPermission('Parent') && bool($info['RR'])) { ?>
          <a href="#user-registration" class="list-group-item list-group-item-action">
            User registration
          </a>
          <?php } ?>
          <?php if ($addr != null) { ?>
          <a href="#residential-address" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            Residential address
          </a>
          <?php } ?>
          <?php if ($userObj->hasPermission('Coach') && $_SESSION['AccessLevel'] == 'Admin') { ?>
          <a href="#squads" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            Squads
          </a>
          <?php } ?>
          <?php if ($userObj->hasPermission('Parent')) { ?>
          <a href="#payment-information" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            Payment information
          </a>
          <a href="#members" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            Members
          </a>
          <?php if ($_SESSION['AccessLevel'] == 'Admin' && (env('GOCARDLESS_ACCESS_TOKEN') || env('GOCARDLESS_SANDBOX_ACCESS_TOKEN')) && !userHasMandates($id)) { ?>
          <a href="#direct-debit-mandate-opt-out" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            Direct debit opt-out
          </a>
          <?php } ?>
          <?php } ?>
          <?php if ($_SESSION['AccessLevel'] == 'Admin') { ?>
          <a href="#squad-rep-settings" class="list-group-item list-group-item-action">
            Squad rep settings
          </a>
          <a href="#targeted-list-settings" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            Targeted list settings
          </a>
          <a href="#team-manager-settings" class="list-group-item list-group-item-action">
            Team manager settings
          </a>
          <?php } ?>
          <a href="#qualifications" class="list-group-item list-group-item-action">
            Qualifications
          </a>
          <a href="#simulate-user" class="list-group-item list-group-item-action">
            Simulate user
          </a>
          <a href="#advanced-information" class="list-group-item list-group-item-action">
            Advanced information
          </a>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="mb-4">
        <h2 id="basic-information">
          Basic Information
        </h2>
        <p class="lead">
          Basic contact details.
        </p>

        <div class="row">
          <div class="col-sm-6 col-md-4">
            <h3 class="h6">Name</h3>
            <p><?=htmlspecialchars($info['Forename'] . ' ' . $info['Surname'])?></p>
          </div>
          <div class="col-sm-6 col-md-4">
            <h3 class="h6">Email</h3>
            <p class="text-truncate"><a
                href="<?=htmlspecialchars(autoUrl("users/" . $id . "/email"))?>"><?=htmlspecialchars($info['EmailAddress'])?></a>
            </p>
          </div>
          <?php if ($number !== false) { ?>
          <div class="col-sm-6 col-md-4">
            <h3 class="h6">Phone</h3>
            <p><a
                href="<?=htmlspecialchars($number->format(PhoneNumberFormat::RFC3966))?>"><?=htmlspecialchars($number->format(PhoneNumberFormat::NATIONAL))?></a>
            </p>
          </div>
          <?php } ?>
          <?php if (bool($info['ASAMember'])) { ?>
          <div class="col-sm-6 col-md-4">
            <h3 class="h6">Swim England Number</h3>
            <p><a target="_blank" href="<?=htmlspecialchars('https://www.swimmingresults.org/membershipcheck/member_details.php?myiref=' . urlencode($info['ASANumber']))?>"><?=htmlspecialchars($info['ASANumber'])?> <i class="fa fa-external-link" aria-hidden="true"></i></a>
            </p>
          </div>

          <div class="col-sm-6 col-md-4">
            <h3 class="h6">Swim England Category</h3>
            <p><?php if($info['ASACategory'] != 0) { ?><?=htmlspecialchars($info['ASACategory'])?><?php } else { ?><strong><span class="text-danger">Not set</span></strong><?php } ?>
            </p>
          </div>

          <div class="col-sm-6 col-md-4">
            <h3 class="h6">Swim England Payment</h3>
            <p><?php if(bool($info['ASAPaid'])) { ?>Club pays <?=htmlspecialchars($info['Forename'])?>'s SE Membership<?php } else { ?><?=htmlspecialchars($info['Forename'])?> pays their own SE Membership<?php } ?>
            </p>
          </div>
          <?php } ?>

          <div class="col-sm-6 col-md-4">
            <h3 class="h6">Club Membership</h3>
            <p><?php if (bool($info['ClubMember'])) { ?><?=htmlspecialchars($info['Forename'])?> is a club member<?php } else { ?><?=htmlspecialchars($info['Forename'])?> is not a club member<?php } ?></p>
          </div>

          <?php if (bool($info['ClubMember'])) { ?>
          <div class="col-sm-6 col-md-4">
            <h3 class="h6">Club Membership Category</h3>
            <p><?php if($info['ClubCategory'] != null) { ?><?=htmlspecialchars($info['ClubCategory'])?><?php } else { ?><strong><span class="text-danger">Not set</span></strong><?php } ?>
            </p>
          </div>

          <div class="col-sm-6 col-md-4">
            <h3 class="h6">Club Membership Payment</h3>
            <p><?php if(bool($info['ClubPaid'])) { ?>Club pays <?=htmlspecialchars($info['Forename'])?>'s Club Membership<?php } else { ?><?=htmlspecialchars($info['Forename'])?> pays their own Club Membership<?php } ?>
            </p>
          </div>
          <?php } ?>

          <?php if ($swimmerToo) { ?>
          <div class="col-sm-6 col-md-4">
            <h3 class="h6">Sport</h3>
            <p><a href="<?=htmlspecialchars(autoUrl("members/" . $swimmerToo))?>"><?=htmlspecialchars($info['Forename'])?> is also a member</a></p>
          </div>
          <?php } ?>
        </div>
      </div>

      <hr>

      <?php if ($userObj->hasPermission('Parent') && bool($info['RR'])) { ?>
      <div class="mb-4">
        <h2 id="user-registration">
          User registration
        </h2>
        <p class="lead">Registration is still pending for this user.</p>
        <p>
          <button id="registration-resend-button" class="btn btn-primary" data-ajax-url="<?=htmlspecialchars(autoUrl("users/ajax/resend-registration-email"))?>" data-user-name="<?=htmlspecialchars($info['Forename'] . ' ' . $info['Surname'])?>" data-user-edit-link="<?=htmlspecialchars(autoUrl("users/" . $id . "/edit"))?>" data-user="<?=htmlspecialchars($id)?>">
            Resend registration email <span class="fa fa-chevron-right"></span>
          </button>
        </p>
        <div id="resend-status"></div>
      </div>

      <hr>
      <?php } ?>

      <?php if ($addr != null) { ?>
      <div class="mb-4">
        <h2 id="residential-address">
          Residential address
        </h2>
        <address>
          <?=htmlspecialchars($addr->streetAndNumber)?><br>
          <?php if (isset($addr->flatOrBuilding)) { ?>
          <?=htmlspecialchars($addr->flatOrBuilding)?><br>
          <?php } ?>
          <?=htmlspecialchars(mb_strtoupper($addr->city))?><br>
          <?php if (isset($addr->county)) { ?>
          <?=htmlspecialchars($addr->county)?><br>
          <?php } ?>
          <?=htmlspecialchars(mb_strtoupper($addr->postCode))?>
        </address>
      </div>

      <hr>
      <?php } ?>

      <?php if ($userObj->hasPermission('Coach') && $_SESSION['AccessLevel'] == 'Admin') { ?>
      <div class="mb-4" id="coach-squad" data-squad-list="<?=htmlspecialchars(json_encode(false))?>">
        <h2 id="squads">
          Squads
        </h2>
        <p class="lead">
          Assign <?=htmlspecialchars($info['Forename'])?> as a coach for a squad.
        </p>

        <div id="coach-squad-list" data-user-id="<?=htmlspecialchars($id)?>" data-ajax-url="<?=htmlspecialchars(autoUrl("users/squads/list"))?>"></div>

        <p id="coach-squad-assign-container" class="d-none">
          <button id="coach-squad-assign" class="btn btn-primary" data-user-id="<?=htmlspecialchars($id)?>" data-ajax-url="<?=htmlspecialchars(autoUrl("users/squads/assign-delete"))?>">
            Assign a squad
          </button>
        </p>
      </div>

      <hr>
      <?php } ?>

      <?php if ($userObj->hasPermission('Parent')) { ?>
      <div class="mb-4">
        <div class="row">
          <div class="col-sm-6 col-md-8">
            <h2 id="payment-information">
              Payment information
            </h2>
            <p class="lead">
              Account details and monthly fees paid by this user.
            </p>
            
            <div class="row">
              <div class="col-md-6">
                <h3 class="h6">Squad Fees</h3>
                <p><?=monthlyFeeCost($db, $id, "string")?></p>
              </div>
              <div class="col-md-6">
                <h3 class="h6">Extra Fees</h3>
                <p><?=monthlyExtraCost($db, $id, "string")?></p>
              </div>
              <div class="col-md-6">
                <h3 class="h6">Direct Debit</h3>
                <?php if (userHasMandates($id)) { ?>
                <?php if ($logo_path) { ?>
                <img class="img-fluid mb-3" style="max-height:35px;" src="<?=$logo_path?>.png"
                  srcset="<?=$logo_path?>@2x.png 2x, <?=$logo_path?>@3x.png 3x">
                <?php } ?>
                <p class="mb-0"><?=$bankName?><abbr
                    title="<?=htmlspecialchars(mb_strtoupper(bankDetails($id, "bank_name")))?>"><?=htmlspecialchars(getBankName(bankDetails($id, "bank_name")))?></abbr>
                </p>
                <p class="mono">******<?=mb_strtoupper(bankDetails($id, "account_number_end"))?></p>
                <?php } else { ?>
                <p>No Direct Debit set up</p>
                <?php } ?>
              </div>
              <div class="col-md-6">
                <h3 class="h6">Account balance</h3>
                <p>
                  &pound;<?=(string) (\Brick\Math\BigDecimal::of((string) getAccountBalance($id)))->withPointMovedLeft(2)->toScale(2)?>
                </p>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-md-4">
          <?php if ($_SESSION['AccessLevel'] == 'Admin') { ?>
            <div class="card">
              <div class="card-header">
                Payment links
              </div>
              <div class="list-group list-group-flush">
                <a href="<?=htmlspecialchars(autoUrl("users/" . $id . "/membership-fees"))?>" class="list-group-item list-group-item-action">Annual membership fees <span class="fa fa-chevron-right"></span></a>
                <a href="<?=autoUrl("users/" . $id . "/pending-fees")?>" class="list-group-item list-group-item-action">Pending payments <span class="fa fa-chevron-right"></span></a>
                <a href="<?=autoUrl("payments/history/users/" . $id)?>" class="list-group-item list-group-item-action">Previous bills <span class="fa fa-chevron-right"></span></a>
                <a href="<?=autoUrl("users/" . $id . "/mandates")?>" class="list-group-item list-group-item-action">Direct debit mandates <span class="fa fa-chevron-right"></span></a>
              </div>
            </div>
          <?php } ?>
          </div>
        </div>
      </div>

      <hr>

      <div class="mb-4">
        <h2 id="members">
          Members
        </h2>
        <p class="lead">
          Members linked to this account
        </p>

        <div class="row">
          <?php while ($s = $swimmers->fetch(PDO::FETCH_ASSOC)) { ?>
          <div class="col-sm-6 col-md-4">
            <h3 class="h6"><a href="<?=autoUrl("swimmers/" . $s['id'])?>"
                title="Full information about <?=htmlspecialchars($s['fn'] . ' ' . $s['sn'])?>"><?=htmlspecialchars($s['fn'] . ' ' . $s['sn'])?></a>
            </h3>
            <?php if ($s['exempt'] || (int) $s['fee'] == 0) { ?>
            <p><?=htmlspecialchars($s['squad'])?> Squad, No squad fee</p>
            <?php } else { ?>
            <p><?=htmlspecialchars($s['squad'])?> Squad,
              &pound;<?=(string) (\Brick\Math\BigDecimal::of((string) $s['fee']))->toScale(2)?></p>
            <?php } ?>
          </div>
          <?php } ?>
        </div>
      </div>

      <hr>

      <?php if ($_SESSION['AccessLevel'] == 'Admin' && (env('GOCARDLESS_ACCESS_TOKEN') || env('GOCARDLESS_SANDBOX_ACCESS_TOKEN')) && !userHasMandates($id)) { ?>
      <div class="mb-4">
        <h2 id="direct-debit-mandate-opt-out">
          Direct debit mandate settings
        </h2>
        <p class="lead">
          Authorise a direct debit opt out for this parent
        </p>

        <p>
          <a href="<?=autoUrl("users/" . $id . "/authorise-direct-debit-opt-out")?>" class="btn btn-primary">
            Authorise opt out <span class="fa fa-chevron-right"></span>
          </a>
        </p>
      </div>

      <hr>
      <?php } ?>

      <?php } ?>

      <?php if ($_SESSION['AccessLevel'] == 'Admin') { ?>
      <div class="row">
        <div class="col-12">
          <div class="mb-4">
            <h2 id="squad-rep-settings">
              Squad rep settings
            </h2>
            <p class="lead">
              Make this user a squad rep.
            </p>

            <p>
              <a href="<?=autoUrl("users/" . $id . "/rep")?>" class="btn btn-primary">
                Rep settings <span class="fa fa-chevron-right"></span>
              </a>
            </p>
          </div>
          <hr>
        </div>

        <div class="col-12">
          <div class="mb-4">
            <h2 id="targeted-list-settings">
              Targeted list settings
            </h2>
            <p class="lead">
              Grant a user permission to use a notify targeted list
            </p>

            <p>
              <a href="<?=autoUrl("users/" . $id . "/targeted-lists")?>" class="btn btn-primary">
                Notify lists <span class="fa fa-chevron-right"></span>
              </a>
            </p>
          </div>
          <hr>
        </div>

        <div class="col-12">
          <div class="mb-4">
            <h2 id="team-manager-settings">
              Team manager settings
            </h2>
            <p class="lead">
              Make this parent a team manager for a specific gala.
            </p>

            <p>
              <a href="<?=autoUrl("users/" . $id . "/team-manager")?>" class="btn btn-primary">
                Team manager settings <span class="fa fa-chevron-right"></span>
              </a>
            </p>
          </div>
          <hr>
        </div>
      </div>
      <?php } ?>

      <div class="mb-4 d-none">
        <h2>
          Access Control
        </h2>
        <p class="lead">
          Access Control oversees access to resources.
        </p>

        <div class="row">
          <div class="col-sm-6 col-md-4">
            <div class="input-group">
              <div class="input-group-prepend">
                <label class="input-group-text" for="accountType">Account Type</label>
              </div>
              <select class="custom-select" id="accountType" name="accountType" data-user-id="<?=htmlspecialchars($id)?>" data-ajax-url="<?=htmlspecialchars(autoUrl('users/ajax/userSettings/'))?>">
                <option <?=$par?> value="Parent">Parent (Default)</option>
                <option <?=$coa?> value="Coach">Coach</option>
                <option <?=$gal?> value="Galas">Galas</option>
                <option <?=$adm?> value="Admin">Admin</option>
              </select>
            </div>
            <div class="mt-2" id="accountTypeOutput"></div>
          </div>
        </div>
        <hr>
      </div>

      <div class="alert alert-info p-3 mb-4">
        <h2 id="qualifications">Qualifications</h2>
        <p class="lead">Qualification tracking for coaching qualifications, team managers and more is coming soon.</p>
        <p>The system will be able to warn you in advance of qualifications expiring and details of qualifications will be visible to both staff and the users who hold those qualifications.</p>
        <p class="mb-0">We'll be trialling this with one customer club before rolling it out to all clubs by the end of summer.</p>
      </div>

      <hr>

      <!-- <div class="mb-4">
        <h2>
          Qualifications
        </h2>

        <p class="lead">
          The membership tracks qualifications to assist you in your compliance
          requirements.
        </p>

        <div class="row">
          <?php
        $count = 0;
        while ($qualification = $qualifications->fetch(PDO::FETCH_ASSOC)) {
          $count += 1; ?>
          <div class="col-sm-6 col-md-4">
            <h3 class="h6"><?=htmlspecialchars($qualification['Name'])?></h3>
            <p><?=htmlspecialchars($qualification['Info'])?></p>
            <p>
              Valid since <?=date("d/m/Y", strtotime($qualification['From']))?><?php if ($qualification['To'] != null) { ?>,
              <strong>Expires <?=date("d/m/Y", strtotime($qualification['To']))?></strong><?php } ?>.
            </p>
          </div>
          <?php } ?>
          <?php if ($count == 0) { ?>
          <div class="col">
            <div class="alert alert-info">
              <strong>This user has no listed qualifications</strong><br>
              They may not have had any added
            </div>
          </div>
          <?php } ?>
        </div>

        <p>
          <a href="<?=htmlspecialchars(autoUrl("users/" . $id . "/qualifications"))?>" class="btn btn-primary">
            <span class="sr-only">View or add</span> Qualifications <span class="fa fa-chevron-right"></span>
          </a>
        </p>
      </div> -->

      <div class="mb-4">
        <h2 id="simulate-user">Simulate this user</h2>
        <p class="lead">
          Act as this user.
        </p>

        <p>
          You can use this feature to provide help and support to other users. It
          will be as if you have logged in as this user.
        </p>

        <p><a href="<?=autoUrl("users/simulate/" . $id)?>" class="btn btn-primary">Simulate this user <span
              class="fa fa-chevron-right"></span> </a></p>
      </div>

      <hr>

      <h2 id="advanced-information">
        Advanced Information
      </h2>
      <p class="lead">
        For troubleshooting.
      </p>

      <div class="mb-4">
        <div class="row">
          <div class="col-sm-6 col-md-4">
            <h3 class="h6">Last Login</h3>
            <?php
            $details = "";
            if ($loginInfo == null) {
              // User has never logged in
              $details = "This user has never logged in";
            } else {
              $time = new DateTime($loginInfo['Time'], new DateTimeZone('UTC'));
              $time->setTimezone(new DateTimeZone('Europe/London'));
              $details = $time->format('H:i T \o\n j F Y') . " from " . htmlspecialchars($loginInfo['Browser']) . " on " . htmlspecialchars($loginInfo['Platform']) . " (" . htmlspecialchars($loginInfo['IPAddress']) . ")";
            }?>
            <p><?=$details?></p>
          </div>

          <div class="col-sm-6 col-md-4">
            <h3 class="h6">Delete account</h3>
            <p>
              <button data-ajax-url="<?=htmlspecialchars(autoUrl("users/delete-user"))?>" data-users-url="<?=htmlspecialchars(autoUrl("users"))?>" data-user-id="<?=htmlspecialchars($id)?>" data-user-name="<?=htmlspecialchars($info['Forename'] . ' ' . $info['Surname'])?>" id="delete-button" class="btn btn-danger">
                Delete account
              </button>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal for use by JS code -->
<div class="modal fade" id="main-modal" tabindex="-1" role="dialog" aria-labelledby="main-modal-title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="main-modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="main-modal-body">
        ...
      </div>
      <div class="modal-footer" id="main-modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
        <button type="button" id="modal-confirm-button" class="btn btn-success">Confirm</button>
      </div>
    </div>
  </div>
</div>

<?php $footer = new \SCDS\Footer();
$footer->addJs("public/js/users/main.js");
$footer->useFluidContainer();
$footer->render();
