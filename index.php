<?php
  $pagetitle = "Homepage";
  include "header.php";
  $userID = $_SESSION['UserID'];

  $sqlSwim = "SELECT members.MemberID, members.MForename, members.MSurname, users.Forename, users.Surname, users.EmailAddress, members.ASANumber, squads.SquadName, squads.SquadFee FROM ((members INNER JOIN users ON members.UserID = users.UserID) INNER JOIN squads ON members.SquadID = squads.SquadID) WHERE members.UserID = '$userID';";
  $result = mysqli_query($link, $sqlSwim);
  $swimmerCount = mysqli_num_rows($result);
  $swimmersRow = mysqli_fetch_array($result, MYSQLI_ASSOC);
?>

<style>
body {
  padding-top: 3.5rem !important;
}
</style>

<div class="nav-scroller bg-white box-shadow mb-3">
  <nav class="nav nav-underline">
    <a class="nav-link" href="#dash">Dashboard</a>
    <a class="nav-link" href="#swimmers">My Swimmers</a>
    <a class="nav-link" href="#fees">My Fees</a>
    <a class="nav-link" href="#entries">My Recent Entries</a>
  </nav>
</div>

<div class="container">
  <div class="d-flex align-items-center p-3 my-3 text-white bg-primary rounded box-shadow" id="dash">
    <img class="mr-3" src="https://www.chesterlestreetasc.co.uk/apple-touch-icon-ipad-retina.png" alt="" width="48" height="48">
    <div class="lh-100">
      <h6 class="mb-0 text-white lh-100"><?php echo $_SESSION['Forename'] . " " . $_SESSION['Surname'] ?></h6>
      <small><?php echo $_SESSION['AccessLevel'] ?></small>
    </div>
  </div>

<hr>

<?php if ($_SESSION['AccessLevel'] == "Parent") { ?>
<!-- My Swimmers Section -->
<section id="swimmers">
<?php
  echo mySwimmersMedia($link, $userID);
?>
</section>

<!-- My Fees Section -->
<section id="fees">
<?php
  echo myMonthlyFeeMedia($link, $userID);
?>
</section>

<!-- My Swimmers Section -->
<section id="entries">
<?php
  echo enteredGalasMedia($link, $userID);
?>
</section>
<?php }
elseif ($_SESSION['AccessLevel'] == "Coach") { ?>
<div class="my-3 p-3 bg-white rounded box-shadow">
  <h2 class="border-bottom border-gray pb-2 mb-0">Quick Tasks</h2>
  <div class="media text-muted pt-3">
    <p class="media-body pb-3 mb-0 lh-125 border-bottom border-gray">
      <a href="<?php echo autoUrl('attendance') ?>"><strong class="d-block text-gray-dark">Register</strong></a>
      Take the register for your squad
    </p>
  </div>
  <div class="media text-muted pt-3">
    <p class="media-body pb-3 mb-0 lh-125 border-bottom border-gray">
      <a href="<?php echo autoUrl('swimmers') ?>"><strong class="d-block text-gray-dark">Swimmer Notes</strong></a>
      Check important medical and other notes from parents
    </p>
  </div>
  <span class="d-block text-right mt-3">
    <a href="#">More tasks</a>
  </span>
</div>
<?php }
else { ?>
  <div class="my-3 p-3 bg-white rounded box-shadow">
    <h2 class="border-bottom border-gray pb-2 mb-0">Gala Tasks</h2>
    <div class="media text-muted pt-3">
      <p class="media-body pb-3 mb-0 lh-125 border-bottom border-gray">
        <a href="<?php echo autoUrl('galas') ?>"><strong class="d-block text-gray-dark">Check Entries</strong></a>
        Check entries for galas
      </p>
    </div>
    <div class="media text-muted pt-3">
      <p class="media-body pb-3 mb-0 lh-125 border-bottom border-gray">
        <a href="<?php echo autoUrl('galas/addgala') ?>"><strong class="d-block text-gray-dark">Add a Gala</strong></a>
        Add a gala to the system to allow entries
      </p>
    </div>
    <span class="d-block text-right mt-3">
      <a href="#">More tasks</a>
    </span>
  </div>
<?php } ?>
</div>


<?php include "footer.php"; ?>