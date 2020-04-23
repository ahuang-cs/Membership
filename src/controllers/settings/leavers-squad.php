<?php

$dateDeparture = new DateTime('first day of next month', new DateTimeZone('Europe/London'));

$fluidContainer = true;

$db = app()->db;
$getSquads = $db->query("SELECT SquadName `name`, SquadID id FROM squads ORDER BY SquadFee ASC, `name` ASC");

$systemInfo = app()->system;
$leavers = $systemInfo->getSystemOption('LeaversSquad');

$pagetitle = "Leaver's Squad";

include BASE_PATH . 'views/header.php';

?>

<div class="container-fluid">
  <div class="row justify-content-between">
    <aside class="col-md-3 d-none d-md-block">
      <?php
        $list = new \CLSASC\BootstrapComponents\ListGroup(file_get_contents(BASE_PATH . 'controllers/settings/SettingsLinkGroup.json'));
        echo $list->render('settings-leavers-squad');
      ?>
    </aside>
    <main class="col-md-9">
      <h1>Set Leaver's Squad</h1>
      <form method="post">

        <?php if (isset($_SESSION['PCC-SAVED']) && $_SESSION['PCC-SAVED']) { ?>
        <div class="alert alert-success">Changes to leaver's squad saved.</div>
        <?php unset($_SESSION['PCC-SAVED']); } ?>

        <?php if (isset($_SESSION['PCC-ERROR']) && $_SESSION['PCC-ERROR']) { ?>
        <div class="alert alert-danger">Changes were not saved.</div>
        <?php unset($_SESSION['PCC-ERROR']); } ?>

        <div id="leavers-squad-help">
          <p>
            Setting a leaver's squad allows parents to indicate a swimmer is leaving <?=htmlspecialchars(env('CLUB_NAME'))?>. This will remove the swimmer on the first day of the next calendar month.
          </p>
          <p>
            e.g. if today a parent told the system a swimmer was leaving, that swimmer would be removed from squad registers on <?=$dateDeparture->format("j F Y")?>.
          </p>
        </div>

        <div class="form-group">
          <label for="leavers-squad">Leaver's Squad</label>
          <select class="custom-select" id="leavers-squad" name="leavers-squad" aria-describedby="leavers-squad-help">
            <option <?php if ($leavers == $null) { ?>selected<?php } ?>>
              Select an option
            </option>
            <?php while ($squad = $getSquads->fetch(PDO::FETCH_ASSOC)) { ?>
            <option value="<?=htmlspecialchars($squad['id'])?>"
              <?php if ($leavers == $squad['id']) { ?>selected<?php } ?>>
              <?=htmlspecialchars($squad['name'])?> Squad
            </option>
            <?php } ?>
          </select>
        </div>

        <p>
          <button class="btn btn-success" type="submit">
            Save
          </button>
        </p>
      </form>
    </main>
  </div>
</div>

<?php

$footer = new \SCDS\Footer();
$footer->useFluidContainer();
$footer->render();