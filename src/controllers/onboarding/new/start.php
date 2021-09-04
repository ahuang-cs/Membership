<?php

use SCDS\CSRF;

$user = app()->user;
$db = app()->db;
$tenant = app()->tenant;

$swimmers = $db->prepare("SELECT MForename `first`, MSurname `last`, MemberID `id`, RRTransfer trans FROM members WHERE Active AND Tenant = ? AND members.UserID IS NULL ORDER BY MemberID DESC, `first` ASC, `last` ASC");
$swimmers->execute([
  $tenant->getId()
]);

$getSquads = $db->prepare("SELECT SquadName FROM squads INNER JOIN squadMembers ON squads.SquadID = squadMembers.Squad WHERE Member = ?");

$swimmer = $swimmers->fetch(PDO::FETCH_ASSOC);

$user = null;

if (isset($_GET['user'])) {
  $getUser = $db->prepare("SELECT Forename firstName, Surname lastName, EmailAddress email, Mobile phone FROM users WHERE UserID = ? AND Tenant  = ?");
  $getUser->execute([
    $_GET['user'],
    $tenant->getId(),
  ]);
  $user = $getUser->fetch(PDO::FETCH_OBJ);
}

$email = $first = $last = $mobile = '';

$pagetitle = "New Onboarding Session";
include BASE_PATH . "views/header.php";

?>

<div class="bg-light mt-n3 py-3 mb-3">
  <div class="container-xl">

    <!-- Page header -->
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= htmlspecialchars(autoUrl('onboarding')) ?>">Onboarding</a></li>
        <li class="breadcrumb-item active" aria-current="page">New</li>
      </ol>
    </nav>

    <div class="row align-items-center">
      <div class="col-lg-8">
        <h1>
          Create an onboarding session
        </h1>
        <p class="lead mb-0">
          Onboarding is the replacement for assisted registration.
        </p>
      </div>
    </div>
  </div>
</div>

<div class="container-xl">

  <div class="row">
    <div class="col-lg-8">

      <div id="onboarding-app">

      </div>

      <?php if ($swimmer) { ?>

        <form method="post" class="needs-validation" novalidate id="form">

          <?= CSRF::write(); ?>

          <h2>User details</h2>

          <?php if (!$user) { ?>

            <p>
              Enter the user's email address here to begin creating a new user.
            </p>

            <p>
              For existing users, please start onboarding from their user page.
            </p>


            <div class="mb-3">
              <label for="user-email" class="form-label">Email address</label>
              <input type="email" name="user-email" class="form-control" id="user-email" placeholder="name@example.com" required value="<?= htmlspecialchars($email) ?>">
            </div>

            <!-- <p>
              <button type="submit" class="btn btn-primary" id="check-button">
                Check
              </button>
            </p> -->

            <div class="info ">
              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <label class="form-label" for="first">First name</label>
                    <input type="text" class="form-control" id="first" name="first" placeholder="First" required value="<?= htmlspecialchars($first) ?>">
                    <div class="invalid-feedback">
                      Please enter a first name.
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="mb-3">
                    <label class="form-label" for="last">Last name</label>
                    <input type="text" class="form-control" id="last" name="last" placeholder="Last" required value="<?= htmlspecialchars($last) ?>">
                    <div class="invalid-feedback">
                      Please enter a last name.
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label" for="phone">Mobile Number</label>
                <input type="tel" pattern="\+{0,1}[0-9]*" class="form-control" id="phone" name="phone" placeholder="01234 567891" required value="<?= htmlspecialchars($mobile) ?>">
                <div class="invalid-feedback">
                  Please enter a valid mobile number.
                </div>
              </div>


            </div>

          <?php } else { ?>

            <div class="card card-body mb-3">
              <h3 class="card-title">
                <?= htmlspecialchars($user->firstName . ' ' . $user->lastName) ?>
              </h3>

              <dl class="row mb-0">
                <dt class="col-3">
                  Email
                </dt>
                <dd class="col-9 text-truncate">
                  <a href="<?= htmlspecialchars("mailto:" . $user->email) ?>"><?= htmlspecialchars($user->email) ?></a>
                </dd>

                <dt class="col-3">
                  Phone
                </dt>
                <dd class="col-9 mb-0">
                  <?= htmlspecialchars($user->phone) ?>
                </dd>
              </dl>
            </div>

          <?php } ?>

          <h2>Select members</h2>

          <div class="alert alert-warning">
            <p class="mb-0">
              <strong>
                Only members without a linked user/parent account are shown.
              </strong>
            </p>
          </div>

          <?php do {
            $getSquads->execute([
              $swimmer['id']
            ]);
          ?>
            <div class="card card-body mb-3">
              <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" id="member-<?= htmlspecialchars($swimmer['id']) ?>" name="member-<?= htmlspecialchars($swimmer['id']) ?>" data-collapse="member-<?= htmlspecialchars($swimmer['id']) ?>-collapse" value="1" autocomplete="off">
                <label class="form-check-label" for="member-<?= htmlspecialchars($swimmer['id']) ?>">
                  <p class="mb-0 fw-bold">
                    <?= htmlspecialchars($swimmer['first'] . ' ' . $swimmer['last']) ?>
                  </p>
                  <ul class="mb-0 list-unstyled">
                    <?php if ($squad = $getSquads->fetch(PDO::FETCH_ASSOC)) {
                      do { ?>
                        <li><?= htmlspecialchars($squad['SquadName']) ?></li>
                      <?php } while ($squad = $getSquads->fetch(PDO::FETCH_ASSOC));
                    } else { ?>
                      <li>No squads</li>
                    <?php } ?>
                  </ul>
                </label>
              </div>

              <div class="collapse" id="member-<?= htmlspecialchars($swimmer['id']) ?>-collapse">
                <div class="form-check custom-control-inline mt-2">
                  <input type="radio" id="member-rr-yes-<?= htmlspecialchars($swimmer['id']) ?>" name="member-rr-<?= htmlspecialchars($swimmer['id']) ?>" class="form-check-input" checked value="yes">
                  <label class="form-check-label" for="member-rr-yes-<?= htmlspecialchars($swimmer['id']) ?>">Require registration</label>
                </div>
                <div class="form-check custom-control-inline mb-0">
                  <input type="radio" id="member-rr-no-<?= htmlspecialchars($swimmer['id']) ?>" name="member-rr-<?= htmlspecialchars($swimmer['id']) ?>" class="form-check-input" value="no">
                  <label class="form-check-label" for="member-rr-no-<?= htmlspecialchars($swimmer['id']) ?>">Add to account quietly</label>
                </div>
              </div>
            </div>
          <?php } while ($swimmer = $swimmers->fetch(PDO::FETCH_ASSOC)); ?>

          <p>
            <button type="submit" class="btn btn-success">
              Next
            </button>
          </p>

        </form>

      <?php } else { ?>

        <div class="alert alert-warning">
          <p class="mb-0">
            <strong>There are no unlinked members at this time</strong>
          </p>
          <p class="mb-0">
            Add members before proceeding.
          </p>
        </div>

      <?php } ?>

    </div>
  </div>

</div>

<script>

</script>

<?php

$footer = new \SCDS\Footer();
$footer->addJs('js/NeedsValidation.js');
$footer->render();